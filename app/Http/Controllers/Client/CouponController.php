<?php
namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CouponUser;


class CouponController extends Controller
{
       //  Tất cả mã giảm giá công khai
   public function index()
{
    $user = Auth::user(); // Lấy người dùng hiện tại

    $coupons = Coupon::with('restriction')
        ->where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        })
        ->where(function ($query) use ($user) {
            // Chỉ hiển thị mã không giới hạn nhóm hoặc đúng với nhóm người dùng
            $query->whereNull('user_group')
                  ->orWhere('user_group', $user->user_group ?? 'guest');
        })
        ->orderByDesc('created_at')
        ->get();

    return view('client.coupons.index', compact('coupons'));
}
    public function received()
{
    $user = Auth::user();
    $coupons = $user->coupons()->with('restriction')->get();
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

        return view('client.coupons.show', compact('coupon'));
    }

  public function claim($id, Request $request)
{
    $user = auth()->user();

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

    $restriction = $coupon->restriction;
    if ($restriction) {
        $validCategories = collect($restriction->valid_categories ?? [])->map(fn($id) => (int) $id);
        $validProducts = collect($restriction->valid_products ?? [])->map(fn($id) => (int) $id);

        $userProductIds = $user->orders()
            ->with('items')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('product_id')
            ->unique()
            ->map(fn($id) => (int) $id);

        $userCategoryIds = \App\Models\Admin\Product::whereIn('id', $userProductIds)
            ->pluck('category_id')
            ->unique()
            ->map(fn($id) => (int) $id);

        if ($validCategories->isNotEmpty() && $userCategoryIds->intersect($validCategories)->isEmpty()) {
            return redirect()->back()->with('warning', 'Mã này không áp dụng cho danh mục của bạn.');
        }

        if ($validProducts->isNotEmpty() && $userProductIds->intersect($validProducts)->isEmpty()) {
            return redirect()->back()->with('warning', 'Mã này không áp dụng cho sản phẩm của bạn.');
        }
    }

    $user->coupons()->attach($id, ['amount' => 1]);
    $coupon->increment('usage_count');

    return redirect()->back()->with('success', 'Bạn đã nhận mã thành công!');
}


}
