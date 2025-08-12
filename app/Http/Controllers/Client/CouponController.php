<?php

namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CouponClaimedNotification;

class CouponController extends Controller
{

    // Tất cả mã giảm giá công khai
public function index()
{
    $user = Auth::user();

    // Lấy danh sách ID mã đã nhận
    $claimedIds = CouponUser::where('user_id', $user?->id)->pluck('coupon_id');

    // Lấy các mã CHƯA nhận, còn hiệu lực, chưa bị xóa
    $coupons = Coupon::query()
        ->with('restriction')
        ->whereNotIn('id', $claimedIds) // ❗ Chỉ mã chưa nhận
        ->where('is_active', true)
        ->whereNull('deleted_at')       // ❗ Bỏ mã đã bị soft delete
        ->where(function ($query) {
            $query->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        })
        ->where(function ($query) use ($user) {
            $query->whereNull('user_group')
                ->orWhere('user_group', $user?->user_group ?? 'guest');
        })
        ->orderByDesc('created_at')
        ->get();

    return view('client.coupons.index', compact('coupons'));
}


    public function received(Request $request)
{
    if (!Auth::check()) {
        return redirect()
            ->route('login')
            ->with('warning', 'Vui lòng đăng nhập để xem mã đã nhận.');
    }

    $user = Auth::user();
    $status = $request->query('status');

    $couponQuery = $user->coupons()
        ->where(function ($q) {
            $q->whereNull('coupon_user.start_date')
              ->orWhere('coupon_user.start_date', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('coupon_user.end_date')
              ->orWhere('coupon_user.end_date', '>=', now());
        });

    // Sửa logic used/unused: xét cả used_at và order_id
    if ($status === 'used') {
        $couponQuery->where(function($q){
            $q->whereNotNull('coupon_user.used_at')
              ->orWhereNotNull('coupon_user.order_id');
        });
    } elseif ($status === 'unused') {
        $couponQuery->whereNull('coupon_user.used_at')
                    ->whereNull('coupon_user.order_id');
    }

    $coupons = $couponQuery
        ->orderByDesc('coupon_user.created_at')
        ->get();

    // Lấy tên danh mục & sản phẩm từ ID lưu trong pivot (đã cast array)
    foreach ($coupons as $coupon) {
        $pivot = $coupon->pivot;

        $categoryIds = $pivot->valid_categories ?? [];
        $productIds  = $pivot->valid_products ?? [];

        $pivot->category_names = is_array($categoryIds)
            ? \App\Models\Admin\Category::whereIn('id', $categoryIds)->pluck('name')->toArray()
            : [];

        $pivot->product_names = is_array($productIds)
            ? \App\Models\Admin\Product::whereIn('id', $productIds)->pluck('name')->toArray()
            : [];
    }

    return view('client.coupons.received', compact('coupons'));
}
    public function show($id)
    {
        $user = Auth::user();

        // Ưu tiên bản đã nhận (đã snapshot)
        $coupon = $user?->coupons()->withTrashed()->where('coupons.id', $id)->first();


        $isClaimed = true;

        if (!$coupon) {
            // Fallback: bản public
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
                    $query->whereNull('user_group')->orWhere('user_group', $user?->user_group ?? 'guest');
                })
                ->where(function ($query) {
                    $query->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
                })
                ->firstOrFail();

            $isClaimed = false;
        }

        // Dùng danh sách từ pivot nếu đã nhận, ngược lại lấy từ coupon.restriction
        $categories = Category::whereIn(
            'id',
            $isClaimed
                ? $coupon->pivot->valid_categories ?? []
                : $coupon->restriction?->valid_categories ?? []
        )->get();

        $products = Product::whereIn(
            'id',
            $isClaimed
                ? $coupon->pivot->valid_products ?? []
                : $coupon->restriction?->valid_products ?? []
        )->get();

        return view('client.coupons.show', compact('coupon', 'categories', 'products', 'isClaimed'));
    }



    public function claim($id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để nhận mã.');
        }

        $user = auth()->user();

        $coupon = Coupon::with('restriction')->where('id', $id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($query) use ($user) {
                return $query->whereNull('user_group')->orWhere('user_group', $user?->user_group ?? 'guest');
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

        // Lưu snapshot tại thời điểm nhận
        $user->coupons()->attach($id, [
            'amount' => 1,
            'code' => $coupon->code,
            'title' => $coupon->title,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
            'min_order_value' => $coupon->restriction->min_order_value ?? 0,
            'max_discount_value' => $coupon->restriction->max_discount_value ?? null,
            'valid_products' => $coupon->restriction->valid_products ?? [],
            'valid_categories' => $coupon->restriction->valid_categories ?? [],
            'user_group' => $coupon->user_group,
            'usage_limit' => $coupon->usage_limit,
        ]);

        return redirect()->back()->with('success', 'Bạn đã nhận mã thành công!');
    }
    
}
