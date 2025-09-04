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
    /** ====== CORE HELPERS ================================================= */

    /** User đã dùng mã này chưa? (ít nhất 1 dòng pivot có used_at hoặc order_id) */
    public static function userHasUsedCoupon(User $user, int $couponId): bool
    {
        return DB::table('coupon_user')
            ->where('user_id', $user->id)
            ->where('coupon_id', $couponId)
            ->where(function ($w) {
                $w->whereNotNull('used_at')
                    ->orWhereNotNull('order_id');
            })
            ->exists();
    }

    /** Chuẩn hoá JSON/array sang array */
    private static function normalizeArray($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
        }
        if (is_array($value)) return $value;
        return [];
    }

    /** ====== VALIDATE + APPLY ============================================ */

    /**
     * Validate mã + tính giảm theo snapshot (ưu tiên pivot).
     * Trả về:
     * [
     *   'coupon'               => Coupon,
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

        // 1) Ưu tiên mã đã claim (kèm snapshot)
        if ($user) {
            $coupon = $user->coupons()
                ->withTrashed()
                ->where('coupons.code', $code)
                ->withPivot([
                    'code',
                    'title',
                    'amount',
                    'used_at',
                    'discount_type',
                    'discount_value',
                    'min_order_value',
                    'max_discount_value',
                    'valid_categories',
                    'valid_products',
                    'start_date',
                    'end_date',
                    'user_group',
                    'usage_limit',
                    'order_id',
                    'discount_applied',
                    'created_at',
                    'updated_at',
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

        // 3) Chặn ngay nếu user đã từng dùng mã này (tuyệt đối 1 lần/người)
        if ($user && self::userHasUsedCoupon($user, $coupon->id)) {
            throw ValidationException::withMessages(['coupon' => 'Bạn đã sử dụng mã này rồi.']);
        }

        // 4) Nếu có pivot CHƯA dùng, dùng làm snapshot; nếu không, thử lấy pivot dòng chưa dùng
        if ($user && !$pivot) {
            $pivotRow = DB::table('coupon_user')
                ->where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->whereNull('used_at')
                ->whereNull('order_id')
                ->first();

            if ($pivotRow) {
                $pivot = (object) [
                    'discount_type'      => $pivotRow->discount_type,
                    'discount_value'     => $pivotRow->discount_value,
                    'min_order_value'    => $pivotRow->min_order_value,
                    'max_discount_value' => $pivotRow->max_discount_value,
                    'valid_products'     => self::normalizeArray($pivotRow->valid_products),
                    'valid_categories'   => self::normalizeArray($pivotRow->valid_categories),
                    'start_date'         => $pivotRow->start_date,
                    'end_date'           => $pivotRow->end_date,
                    'user_group'         => $pivotRow->user_group,
                ];
            }
        }

        // 5) Đọc snapshot (ưu tiên pivot)
        $discountType     = $pivot->discount_type      ?? $coupon->discount_type;
        $discountValue    = $pivot->discount_value     ?? $coupon->discount_value;
        $minOrderValue    = $pivot->min_order_value    ?? $coupon->restriction?->min_order_value;
        $maxDiscountValue = $pivot->max_discount_value ?? $coupon->restriction?->max_discount_value;
        $validProductIds  = collect($pivot->valid_products   ?? $coupon->restriction?->valid_products   ?? []);
        $validCategoryIds = collect($pivot->valid_categories ?? $coupon->restriction?->valid_categories ?? []);
        $startDate        = $pivot->start_date ?? $coupon->start_date;
        $endDate          = $pivot->end_date   ?? $coupon->end_date;

        $hasSnapshot = !is_null($pivot);
        //  Luôn chặn theo trạng thái thật của coupon (kể cả snapshot)
        if (!$coupon->is_active) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã bị vô hiệu hoá.']);
        }
        if (!self::hasQuota($coupon)) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }
        // 6) Validate thời gian (luôn theo snapshot nếu có)
        if ($startDate && $now->lt($startDate)) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá chưa được bắt đầu.']);
        }
        if ($endDate && $now->gt($endDate)) {
            throw ValidationException::withMessages(['coupon' => 'Mã giảm giá đã hết hạn.']);
        }

        // 6b) Điều kiện toàn cục khi dùng bản public (chưa claim)
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
            $requiredGroup = $pivot->user_group ?? null;
            if ($user && $requiredGroup && $requiredGroup !== ($user->user_group ?? 'guest')) {
                throw ValidationException::withMessages(['coupon' => 'Mã không áp dụng cho nhóm người dùng của bạn.']);
            }
        }

        // 7) Điều kiện giỏ hàng
        $total = $cartItems->sum(function ($item) {
            $price    = (float) data_get($item, 'price');
            $quantity = (int)   data_get($item, 'quantity', 1);
            return $price * $quantity;
        });

        if ($minOrderValue > 0 && $total < $minOrderValue) {
            $minFmt   = number_format($minOrderValue, 0, ',', '.');
            $totalFmt = number_format($total, 0, ',', '.');

            throw ValidationException::withMessages([
                'coupon' => "🛒 Đơn hàng hiện tại ({$totalFmt} VNĐ) chưa đạt giá trị tối thiểu {$minFmt} VNĐ để sử dụng mã."
            ]);
        }


        $cartProductIds  = $cartItems->pluck('product_id')->map(fn($id) => (int) $id);
        $cartCategoryIds = $cartItems->pluck('category_id')->map(fn($id) => (int) $id);

        // Phạm vi OR: chỉ cần khớp sản phẩm HOẶC danh mục
        $passesProduct  = $validProductIds->isEmpty()  || $cartProductIds->intersect($validProductIds)->isNotEmpty();
        $passesCategory = $validCategoryIds->isEmpty() || $cartCategoryIds->intersect($validCategoryIds)->isNotEmpty();

        if (!($passesProduct || $passesCategory)) {
            throw ValidationException::withMessages(['coupon' => 'Mã không áp dụng cho sản phẩm/danh mục trong giỏ hàng.']);
        }

        // 8) Tính giảm
        if ($discountType === 'percent') {
            $discount = $total * ((float) $discountValue / 100);
            if (!is_null($maxDiscountValue) && $discount > (float) $maxDiscountValue) {
                $discount = (float) $maxDiscountValue;
            }
        } else {
            $discount = min((float) $discountValue, $total);
        }

        $discount = round($discount);

        return [
            'coupon'               => $coupon,
            'discount'             => $discount,
            'total_after_discount' => max(0, $total - $discount),
        ];
    }

    /** ====== THEO LUỒNG CHECKOUT: TRẢ VỀ DANH SÁCH CHỌN MÃ =============== */

    /**
     * Lấy danh sách mã “có thể chọn” cho checkout.
     * - Loại hẳn các mã user đã dùng.
     * - Chạy validate trên từng mã còn lại để biết usable/disabled & lý do.
     *
     * return [
     *   'usable'   => [ ['code'=>..., 'title'=>..., 'discount'=>..., 'preview'=>...], ... ],
     *   'disabled' => [ ['code'=>..., 'title'=>..., 'reason'=>'Đã sử dụng'|'Hết hạn'|...], ... ],
     * ]
     */
    public static function getCheckoutOptions(Collection $cartItems, User $user): array
    {
        // 1) Public ứng viên: đúng group + còn hạn theo NGÀY + is_active
        $public = Coupon::query()
            ->where(function ($q) use ($user) {
                $q->whereNull('user_group')
                    ->orWhere('user_group', $user->user_group ?? 'guest');
            })
            ->where('is_active', 1) // 🔥 thêm
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->get(['id', 'code', 'title', 'usage_limit', 'usage_count']); // 🔥 lấy thêm quota

        // 2) Claimed: còn hạn theo snapshot NGÀY
        $claimed = DB::table('coupon_user as cu')
            ->join('coupons as c', 'c.id', '=', 'cu.coupon_id')
            ->where('cu.user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('cu.end_date')
                    ->orWhere('cu.end_date', '>=', now());
            })
            ->get(['c.id', 'c.code', 'c.title', 'c.usage_limit', 'c.usage_count']); // 🔥 quota

        // 3) Gộp + bỏ những mã đã dùng
        $candidates = collect($public)->concat($claimed)
            ->unique('id')
            ->reject(fn($c) => self::userHasUsedCoupon($user, $c->id))
            // 🔥 Ẩn luôn mã hết lượt
            ->reject(function ($c) {
                // chuyển sang model để dùng helper (hoặc check trực tiếp usage_count/limit)
                $coupon = Coupon::find($c->id);
                return !$coupon || !self::hasQuota($coupon);
            })
            ->values();

        $usable = [];
        $disabled = [];

        foreach ($candidates as $c) {
            try {
                $res = self::validateAndApply($c->code, $cartItems, $user);
                $usable[] = [
                    'id'       => $c->id,
                    'code'     => $c->code,
                    'title'    => $c->title,
                    'discount' => $res['discount'],
                ];
            } catch (\Illuminate\Validation\ValidationException $e) {
                $msg = collect($e->errors())->flatten()->first() ?? 'Không thể áp dụng mã này.';
                $disabled[] = [
                    'id'     => $c->id,
                    'code'   => $c->code,
                    'title'  => $c->title,
                    'reason' => $msg,
                ];
            }
        }

        usort($usable, fn($a, $b) => $b['discount'] <=> $a['discount']);
        return compact('usable', 'disabled');
    }



    /** ====== ĐÁNH DẤU ĐÃ DÙNG / ROLLBACK ================================= */

    /**
     * GỌI SAU KHI ĐƠN TẠO THÀNH CÔNG:
     * - Gắn used_at, order_id, discount_applied (atomic, có lock)
     * - Tăng usage_count (có lock)
     * - Idempotent
     */
    public static function markUsed(User $user, Coupon $coupon, ?Order $order = null, ?float $discountAmount = null): void
    {
        DB::transaction(function () use ($user, $coupon, $order, $discountAmount) {
            // 1) Khoá coupon
            $coupon = Coupon::query()
                ->whereKey($coupon->id)
                ->lockForUpdate()
                ->first();

            // 2) Bảo đảm có snapshot ở pivot
            if (!$user->coupons()->where('coupon_id', $coupon->id)->exists()) {
                $r = $coupon->restriction; // có thể null
                $user->coupons()->attach($coupon->id, [
                    'amount'             => 1,
                    'code'               => $coupon->code,
                    'title'              => $coupon->title,
                    'discount_type'      => $coupon->discount_type,
                    'discount_value'     => $coupon->discount_value,
                    'start_date'         => $coupon->start_date,
                    'end_date'           => $coupon->end_date,
                    'user_group'         => $coupon->user_group,
                    'usage_limit'        => $coupon->usage_limit,
                    'min_order_value'    => $r->min_order_value ?? 0,
                    'max_discount_value' => $r->max_discount_value ?? null,
                    'valid_products'     => $r->valid_products ?? [],
                    'valid_categories'   => $r->valid_categories ?? [],
                ]);
            }

            // 3) Khoá pivot
            $pivotRow = DB::table('coupon_user')
                ->where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->lockForUpdate()
                ->first();

            // 4) Idempotent
            if ($pivotRow?->used_at || $pivotRow?->order_id) {
                return;
            }

            // 5) Kiểm tra quota toàn cục
            if (!is_null($coupon->usage_limit) && $coupon->usage_count >= $coupon->usage_limit) {
                throw new \RuntimeException('Mã đã hết lượt sử dụng.');
            }

            // 6) Đánh dấu used
            $user->coupons()->updateExistingPivot($coupon->id, [
                'used_at'          => now(),
                'order_id'         => $order?->id,
                'discount_applied' => max(0, (float) ($discountAmount ?? 0.0)),
            ]);

            // 7) Tăng usage_count
            $coupon->increment('usage_count');
        });
    }

    /**
     * GỌI KHI huỷ đơn / thanh toán fail (nếu policy cho phép hoàn lượt).
     */
    public static function rollbackUsed(User $user, Coupon $coupon, ?Order $order = null): void
    {
        DB::transaction(function () use ($user, $coupon, $order) {
            $pivot = $user->coupons()->where('coupon_id', $coupon->id)->first()?->pivot;

            if ($pivot && ($pivot->used_at || $pivot->order_id)) {
                if ($order && $pivot->order_id && $pivot->order_id !== $order->id) {
                    return;
                }

                $user->coupons()->updateExistingPivot($coupon->id, [
                    'used_at'          => null,
                    'order_id'         => null,
                    'discount_applied' => null,
                ]);

                if ($coupon->usage_count > 0) {
                    $coupon->decrement('usage_count');
                }
            }
        });
    }
    // CouponService
    private static function hasQuota(Coupon $coupon): bool
    {
        return is_null($coupon->usage_limit) || $coupon->usage_count < $coupon->usage_limit;
    }
}
