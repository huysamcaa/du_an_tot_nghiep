<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CouponService
{
    public static function validateAndApply(string $code, Collection $cartItems, User $user = null)
    {
        $coupon = Coupon::with('restriction')->where('code', $code)->first();

        if (!$coupon || !$coupon->is_active) {
            return back()->withErrors(['coupon' => 'Mã giảm giá không hợp lệ hoặc đã bị vô hiệu hoá.']);
        }

        $now = Carbon::now();

        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return back()->withErrors(['coupon' => 'Mã giảm giá chưa được kích hoạt.']);
        }

        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return back()->withErrors(['coupon' => 'Mã giảm giá đã hết hạn.']);
        }

        if ($coupon->is_expired) {
            return back()->withErrors(['coupon' => 'Mã giảm giá đã bị đánh dấu hết hạn.']);
        }

        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return back()->withErrors(['coupon' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        if ($user && $coupon->user_group && $coupon->user_group !== ($user->user_group ?? 'guest')) {
            return back()->withErrors(['coupon' => 'Mã không áp dụng cho nhóm người dùng của bạn.']);
        }

        if ($user && $coupon->users()->where('user_id', $user->id)->wherePivotNotNull('used_at')->exists()) {
            return back()->withErrors(['coupon' => 'Bạn đã sử dụng mã này rồi.']);
        }




        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        $restriction = $coupon->restriction;

        // Điều kiện về giá trị đơn hàng
        if ($restriction && $restriction->min_order_value && $total < $restriction->min_order_value) {
            return back()->withErrors(['coupon' => '🛒 Đơn hàng chưa đủ giá trị tối thiểu để dùng mã.']);
        }

        // Điều kiện về danh mục và sản phẩm
        $validCategoryIds = collect($restriction->valid_categories ?? []);
        $validProductIds = collect($restriction->valid_products ?? []);
        $cartProductIds = $cartItems->pluck('product_id')->map(fn($id) => (int) $id);
        $cartCategoryIds = $cartItems->pluck('category_id')->map(fn($id) => (int) $id);

        if ($validProductIds->isNotEmpty() && $cartProductIds->intersect($validProductIds)->isEmpty()) {
            return back()->withErrors(['coupon' => 'Mã không áp dụng cho các sản phẩm trong giỏ hàng.']);
        }

        if ($validCategoryIds->isNotEmpty() && $cartCategoryIds->intersect($validCategoryIds)->isEmpty()) {
            return back()->withErrors(['coupon' => 'Mã không áp dụng cho danh mục trong giỏ hàng.']);
        }

        // Tính toán giảm giá
        $discount = $coupon->discount_type === 'percent'
            ? $total * $coupon->discount_value / 100
            : $coupon->discount_value;

        // Giới hạn mức giảm tối đa
        if ($restriction && $restriction->max_discount_value && $discount > $restriction->max_discount_value) {
            $discount = $restriction->max_discount_value;
        }

        return [
            'coupon' => $coupon,
            'discount' => round($discount),
            'total_after_discount' => max(0, $total - $discount),
        ];
    }
}
