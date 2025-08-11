<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Shared\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponService
{
    /**
     * Validate mã + tính giảm giá theo snapshot (ưu tiên pivot nếu user đã claim).
     * Trả về:
     * [
     *   'coupon'               => Coupon (có thể kèm pivot),
     *   'discount'             => float (đã round),
     *   'total_after_discount' => float,
     * ]
     * Ném ValidationException nếu không hợp lệ.
     */
    public static function validateAndApply(string $code, Collection $cartItems, ?User $user = null): array
    {
        $now    = now();
        $pivot  = null;
        $coupon = null;

        // 1) Ưu tiên mã đã claim (có snapshot ở pivot)
        if ($user) {
            $coupon = $user->coupons()
                ->withTrashed()                 // cho phép dùng bản đã claim dù coupon bị soft-delete
                ->where('coupons.code', $code)  // qualify tên bảng
                ->withPivot([
                    'code','title','amount','used_at',
                    'discount_type','discount_value',
                    'min_order_value','max_discount_value',
                    'valid_categories','valid_products',
                    'start_date','end_date',
                    'user_group','usage_limit',
                    'order_id','discount_applied',
                    'created_at','updated_at',
                ])
                ->first();

            if ($coupon) {
                $pivot = $coupon->pivot;
            }
        }

        // 2) Nếu chưa claim, fallback mã public
        if (!$coupon) {
            $coupon = Coupon::with('restriction')->where('code', $code)->first();
            if (!$coupon) {
                throw ValidationException::withMessages(['coupon' => 'Mã giảm giá không tồn tại.']);
            }
        }

        // 3) Đọc snapshot (ưu tiên pivot)
        $discountType     = $pivot?->discount_type      ?? $coupon->discount_type;
        $discountValue    = $pivot?->discount_value     ?? $coupon->discount_value;
        $minOrderValue    = $pivot?->min_order_value    ?? $coupon->restriction?->min_order_value;
        $maxDiscountValue = $pivot?->max_discount_value ?? $coupon->restriction?->max_discount_value;
        $validProductIds  = collect($pivot?->valid_products   ?? $coupon->restriction?->valid_products   ?? []);
        $validCategoryIds = collect($pivot?->valid_categories ?? $coupon->restriction?->valid_categories ?? []);
        $startDate        = $pivot?->start_date ?? $coupon->start_date;
        $endDate          = $pivot?->end_date   ?? $coupon->end_date;
        $usedAt           = $pivot?->used_at    ?? null;
        $lockedOrderId    = $pivot?->order_id   ?? null;

        $hasSnapshot = $pivot !== null;

        // 4) Validate thời gian (luôn theo snapshot nếu có)
        if ($startDate && $now->lt($startDate)) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá chưa được bắt đầu.']);
        }
        if ($endDate && $now->gt($endDate)) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã hết hạn.']);
        }

        // 4b) Điều kiện toàn cục:
        if (!$hasSnapshot) {
            if (!$coupon->is_active) {
                throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã bị vô hiệu hoá.']);
            }
            if ($coupon->is_expired) {
                throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã hết hạn.']);
            }
            if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
                throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã hết lượt sử dụng.']);
            }
            if ($user && $coupon->user_group && $coupon->user_group !== ($user->user_group ?? 'guest')) {
                throw ValidationException::withMessages(['coupon' => 'Mã không áp dụng cho nhóm người dùng của bạn.']);
            }
        } else {
            // ĐÃ claim: so group theo snapshot (nếu snapshot có lưu)
            $requiredGroup = $pivot?->user_group;
            if ($user && $requiredGroup && $requiredGroup !== ($user->user_group ?? 'guest')) {
                throw ValidationException::withMessages(['coupon' => 'Mã không áp dụng cho nhóm người dùng của bạn.']);
            }
        }

        // Xem là ĐÃ DÙNG nếu có used_at HOẶC đã gắn vào một order
        if ($usedAt || $lockedOrderId) {
            throw ValidationException::withMessages(['coupon' => 'Bạn đã sử dụng mã này rồi.']);
        }

        // 5) Điều kiện giỏ hàng
        $total = $cartItems->sum(fn ($item) => $item->price * $item->quantity);

        if ($minOrderValue && $total < $minOrderValue) {
            throw ValidationException::withMessages(['coupon' => '🛒 Đơn hàng chưa đạt giá trị tối thiểu để dùng mã.']);
        }

        $cartProductIds  = $cartItems->pluck('product_id')->map(fn ($id) => (int) $id);
        $cartCategoryIds = $cartItems->pluck('category_id')->map(fn ($id) => (int) $id);

        // Phạm vi OR: chỉ cần khớp sản phẩm HOẶC danh mục
        $passesProduct  = $validProductIds->isEmpty()  || $cartProductIds->intersect($validProductIds)->isNotEmpty();
        $passesCategory = $validCategoryIds->isEmpty() || $cartCategoryIds->intersect($validCategoryIds)->isNotEmpty();

        if (!($passesProduct || $passesCategory)) {
            throw ValidationException::withMessages(['coupon' => 'Mã không áp dụng cho sản phẩm/danh mục trong giỏ hàng.']);
        }

        // 6) Tính giảm
        if ($discountType === 'percent') {
            $discount = $total * ($discountValue / 100);
            if (!is_null($maxDiscountValue) && $discount > $maxDiscountValue) {
                $discount = $maxDiscountValue;
            }
        } else {
            // fixed: không vượt tổng, tránh âm
            $discount = min($discountValue, $total);
        }

        return [
            'coupon'               => $coupon,
            'discount'             => round($discount),
            'total_after_discount' => max(0, $total - $discount),
        ];
    }

    /**
     * GỌI SAU KHI ĐƠN TẠO THÀNH CÔNG:
     * - Gắn used_at, order_id, discount_applied vào pivot user<->coupon
     * - Tăng usage_count
     */
    public static function markUsed(User $user, Coupon $coupon, ?Order $order = null, ?float $discountAmount = null): void
    {
        DB::transaction(function () use ($user, $coupon, $order, $discountAmount) {
            // Khoá row coupon để tránh race-condition
            $coupon = Coupon::whereKey($coupon->id)->lockForUpdate()->first();

            // Nếu user chưa claim -> attach snapshot trước
            if (!$user->coupons()->where('coupon_id', $coupon->id)->exists()) {
                $restriction = $coupon->restriction;

                $snapshot = [
                    'amount'             => 1,
                    'code'               => $coupon->code,
                    'title'              => $coupon->title,
                    'discount_type'      => $coupon->discount_type,
                    'discount_value'     => $coupon->discount_value,
                    'start_date'         => $coupon->start_date,
                    'end_date'           => $coupon->end_date,
                    'user_group'         => $coupon->user_group,
                    'usage_limit'        => $coupon->usage_limit,
                    'min_order_value'    => $restriction->min_order_value ?? 0,
                    'max_discount_value' => $restriction->max_discount_value ?? null,
                    'valid_products'     => $restriction->valid_products ?? [],
                    'valid_categories'   => $restriction->valid_categories ?? [],
                ];

                $user->coupons()->attach($coupon->id, $snapshot);
            }

            // Lấy pivot
            $pivot = $user->coupons()->where('coupon_id', $coupon->id)->first()->pivot;

            // Nếu đã used hoặc đã gắn order_id -> bỏ qua
            if ($pivot->used_at || $pivot->order_id) {
                return;
            }

            // Kiểm tra quota ngay trước khi set used
            if (!is_null($coupon->usage_limit) && $coupon->usage_count >= $coupon->usage_limit) {
                throw new \RuntimeException('Mã đã hết lượt sử dụng.');
            }

            // Đánh dấu đã dùng + gắn order + lưu số tiền giảm thực
            $user->coupons()->updateExistingPivot($coupon->id, [
                'used_at'          => now(),
                'order_id'         => $order?->id,
                'discount_applied' => $discountAmount ?? 0.0,
            ]);

            // Tăng lượt dùng toàn cục
            $coupon->increment('usage_count');
        });
    }

    /**
     * GỌI KHI: Đơn bị huỷ / thanh toán fail (nếu policy cho phép hoàn lượt).
     * - Clear used_at, order_id, discount_applied
     * - Giảm usage_count
     */
   public static function rollbackUsed(User $user, Coupon $coupon, ?Order $order = null): void
{
    DB::transaction(function () use ($user, $coupon, $order) {
        $pivot = $user->coupons()->where('coupon_id', $coupon->id)->first()?->pivot;

        // Nếu đã dùng rồi thì KHÔNG xoá used_at để đảm bảo 1 user chỉ dùng 1 lần
        if ($pivot && ($pivot->used_at || $pivot->order_id)) {
            // Chỉ clear order_id nếu muốn “rời” coupon khỏi đơn hủy
            $user->coupons()->updateExistingPivot($coupon->id, [
                'order_id'         => null,
                // Giữ nguyên used_at để khóa
                // discount_applied có thể giữ nguyên hoặc null tuỳ chính sách
            ]);

            //  Không giảm usage_count nếu muốn giữ số lượt toàn cục đã dùng
            // if ($coupon->usage_count > 0) {
            //     $coupon->decrement('usage_count');
            // }
        }
    });
}

}
