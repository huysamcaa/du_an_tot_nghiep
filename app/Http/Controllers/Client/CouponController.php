<?php

namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CouponUser;
use App\Notifications\CouponClaimedNotification;

class CouponController extends Controller
{

    // Tất cả mã giảm giá công khai
    public function index()
    {
        $user = Auth::user();

        $coupons = Coupon::with('restriction')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($query) use ($user) {
                $query->whereNull('user_group')
                      ->orWhere('user_group', $user->user_group ?? 'guest');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('client.coupons.index', compact('coupons'));
    }

public function received(Request $request)
{
    $user = Auth::user();
    $status = $request->query('status'); // ?status=...

    $couponsQuery = $user->coupons()
        ->with('restriction')
        ->where(function ($q) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
        });

    // Lọc theo trạng thái
    if ($status === 'used') {
        $couponsQuery->wherePivotNotNull('used_at');
    } elseif ($status === 'unused') {
        $couponsQuery->wherePivotNull('used_at');
    }

    // ✅ Sắp xếp theo lúc người dùng NHẬN mã (pivot.created_at)
    $coupons = $couponsQuery
        ->orderByDesc('coupon_user.created_at')
        ->get();

    return view('client.coupons.received', compact('coupons'));
}




  public function show($id)
{
    $user = Auth::user();

    $coupon = Coupon::with('restriction')
        ->where('id', $id)
        ->where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        })
        ->where(function ($query) use ($user) {
            $query->whereNull('user_group')->orWhere('user_group', $user->user_group ?? 'guest');
        })
        ->where(function ($query) {
            $query->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
        })
        ->firstOrFail();

    // ✅ Truy vấn thêm danh mục & sản phẩm từ restriction
    $categories = \App\Models\Admin\Category::whereIn('id', $coupon->restriction->valid_categories ?? [])->get();
    $products = \App\Models\Admin\Product::whereIn('id', $coupon->restriction->valid_products ?? [])->get();

    return view('client.coupons.show', compact('coupon', 'categories', 'products'));
}


   public function claim($id, Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để nhận mã.');
    }

    $user = auth()->user();
    $coupon = Coupon::where('id', $id)
        ->where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        })
        ->where(function ($query) use ($user) {
            return $query->whereNull('user_group')->orWhere('user_group', $user->user_group ?? 'guest');
        })
        ->where(function ($query) {
            $query->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
        })
        ->first();

    if (!$coupon) {
        return redirect()->back()->with('warning', 'Mã không hợp lệ hoặc đã hết lượt.');
    }

    if ($user->coupons()->where('coupon_id', $id)->exists()) {
        return redirect()->back()->with('warning', 'Bạn đã nhận mã này.');
    }

    // Không kiểm tra sản phẩm / danh mục ở đây nữa

    $user->coupons()->attach($id, ['amount' => 1]);
    $coupon->increment('usage_count');

    return redirect()->back()->with('success', 'Bạn đã nhận mã thành công!');
}

}
