<?php
namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CouponUser;


class CouponController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $coupons = $user->coupons()->with('restriction')->get();

        return view('client.coupons.index', compact('coupons'));
    }

    public function active()
    {
        $user = Auth::user();

        // Lấy các sản phẩm & danh mục mà user đã mua (nếu có hệ thống đơn hàng)
        $userProductIds = collect();  // Bạn có thể lấy thực tế từ đơn hàng
        $userCategoryIds = collect();

        // Lấy các mã hợp lệ
        $coupons = Coupon::with('restriction')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($query) use ($user) {
                $query->whereNull('user_group')->orWhere('user_group', $user->group ?? 'guest');
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->get()
            ->filter(function ($coupon) use ($userProductIds, $userCategoryIds) {
                $restriction = $coupon->restriction;
                if (!$restriction) return true;

                $validCategories = json_decode($restriction->valid_categories ?? '[]', true);
                $validProducts = json_decode($restriction->valid_products ?? '[]', true);

                if (!empty($validCategories) && !$userCategoryIds->intersect($validCategories)->count()) {
                    return false;
                }

                if (!empty($validProducts) && !$userProductIds->intersect($validProducts)->count()) {
                    return false;
                }

                return true;
            });

        return view('client.coupons.active', compact('coupons'));
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
                $query->whereNull('user_group')->orWhere('user_group', $user->group ?? 'guest');
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
                return $query->whereNull('user_group')->orWhere('user_group', $user->group ?? 'guest');
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->first();

        if (!$coupon) {
            return redirect()->back()->with('warning', 'Mã không hợp lệ hoặc đã hết lượt.');
        }

        // Kiểm tra đã nhận chưa
        if ($user->coupons()->where('coupon_id', $id)->exists()) {
            return redirect()->back()->with('warning', 'Bạn đã nhận mã này.');
        }

        // Kiểm tra ràng buộc
        $restriction = $coupon->restriction;
        if ($restriction) {
            $validCategories = json_decode($restriction->valid_categories ?? '[]', true);
            $validProducts = json_decode($restriction->valid_products ?? '[]', true);

            $userCategoryIds = collect(); //lấy danh mục từ lịch sử mua
            $userProductIds = collect(); // lấy sản phẩm từ lịch sử mua

            if (!empty($validCategories) && !$userCategoryIds->intersect($validCategories)->count()) {
                return redirect()->back()->with('warning', 'Mã này không áp dụng cho danh mục của bạn.');
            }

            if (!empty($validProducts) && !$userProductIds->intersect($validProducts)->count()) {
                return redirect()->back()->with('warning', 'Mã này không áp dụng cho sản phẩm của bạn.');
            }
        }

        // Ghi nhận vào bảng coupon_user
        $user->coupons()->attach($id, ['amount' => 1]);

        // Tăng usage_count
        $coupon->increment('usage_count');

        return redirect()->back()->with('success', 'Bạn đã nhận mã thành công!');
    }
}
