<?php

namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    // Tất cả mã giảm giá công khai (chỉ còn hiệu lực)
    public function index()
    {
        $user = Auth::user();

        $claimedIds = CouponUser::where('user_id', $user?->id)->pluck('coupon_id');

        $coupons = Coupon::query()
            ->with('restriction')
            ->valid() // chỉ lấy coupon còn hiệu lực (is_active, quota, is_expired/end_date)
            ->whereNotIn('id', $claimedIds)
            ->where(function ($q) { // chưa tới ngày bắt đầu thì bỏ
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) use ($user) { // đúng group
                $q->whereNull('user_group')
                  ->orWhere('user_group', $user?->user_group ?? 'guest');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('client.coupons.index', compact('coupons'));
    }

    // Mã đã nhận (chỉ còn hiệu lực, chưa dùng)
    public function received(Request $request)
    {
        $user = auth()->user();

        $query = $user->coupons()
            ->withPivot([
                'id','code','title','discount_type','discount_value',
                'min_order_value','max_discount_value',
                'valid_categories','valid_products',
                'start_date','end_date','user_group',
                'usage_limit','amount',
                'used_at','order_id','discount_applied',
                'created_at','updated_at',
            ])
            ->where(function ($q) { // còn hiệu lực thời gian
                $q->whereNull('coupon_user.start_date')
                  ->orWhere('coupon_user.start_date', '<=', now());
            })
            ->where(function ($q) { // còn hạn
                $q->whereNull('coupon_user.end_date')
                  ->orWhere('coupon_user.end_date', '>=', now());
            })
            ->whereNull('coupon_user.used_at')   // chưa dùng
            ->whereNull('coupon_user.order_id'); // chưa gắn đơn hàng

        $coupons = $query->get();

        return view('client.coupons.received', compact('coupons'));
    }

    // Xem chi tiết coupon
    public function show($id)
    {
        $user = Auth::user();

        $coupon = $user?->coupons()->withTrashed()->where('coupons.id', $id)->first();
        $isClaimed = true;

        if (!$coupon) {
            // fallback: chỉ tìm trong coupon còn hiệu lực
            $coupon = Coupon::with('restriction')
                ->valid()
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) use ($user) {
                    $q->whereNull('user_group')
                      ->orWhere('user_group', $user?->user_group ?? 'guest');
                })
                ->whereKey($id)
                ->firstOrFail();

            $isClaimed = false;
        }

        $categories = Category::whereIn(
            'id',
            $isClaimed
                ? ($coupon->pivot->valid_categories ?? [])
                : ($coupon->restriction?->valid_categories ?? [])
        )->get();

        $products = Product::whereIn(
            'id',
            $isClaimed
                ? ($coupon->pivot->valid_products ?? [])
                : ($coupon->restriction?->valid_products ?? [])
        )->get();

        return view('client.coupons.show', compact('coupon', 'categories', 'products', 'isClaimed'));
    }

    // Nhận coupon (chỉ còn hiệu lực)
    public function claim($id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để nhận mã.');
        }

        $user = auth()->user();

        $coupon = Coupon::with('restriction')
            ->valid()
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('user_group')
                  ->orWhere('user_group', $user?->user_group ?? 'guest');
            })
            ->whereKey($id)
            ->first();

        if (!$coupon) {
            return redirect()->back()->with('warning', 'Mã không hợp lệ hoặc đã hết hạn.');
        }

        if ($user->coupons()->where('coupon_id', $id)->exists()) {
            return redirect()->back()->with('warning', 'Bạn đã nhận mã này.');
        }

        // Snapshot
        $user->coupons()->attach($id, [
            'amount'             => 1,
            'code'               => $coupon->code,
            'title'              => $coupon->title,
            'discount_type'      => $coupon->discount_type,
            'discount_value'     => $coupon->discount_value,
            'start_date'         => $coupon->start_date,
            'end_date'           => $coupon->end_date,
            'min_order_value'    => $coupon->restriction->min_order_value ?? 0,
            'max_discount_value' => $coupon->restriction->max_discount_value ?? null,
            'valid_products'     => $coupon->restriction->valid_products ?? [],
            'valid_categories'   => $coupon->restriction->valid_categories ?? [],
            'user_group'         => $coupon->user_group,
            'usage_limit'        => $coupon->usage_limit,
        ]);

        return redirect()->back()->with('success', 'Bạn đã nhận mã thành công!');
    }
}
