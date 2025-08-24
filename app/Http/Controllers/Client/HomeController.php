<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Blog;
use App\Models\Admin\Review;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BlogCategory;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['variants.attributeValues.attribute'])
            ->where('is_active', 1)
            ->latest()
            ->paginate(8);

       $categories = Category::where('is_active', 1)
            ->whereNull('parent_id') // Lọc chỉ các danh mục cha
            ->whereHas('children') // Đảm bảo danh mục cha này có ít nhất một danh mục con
            ->with(['children' => function ($query) {
                // Tải các danh mục con và đếm số sản phẩm trực tiếp đang hoạt động của chúng
                $query->withCount(['directProducts' => function ($query) {
                    $query->where('is_active', 1);
                }]);
            }])
            ->orderBy('ordinal')
            ->get();

        $blogs = Blog::latest()->take(3)->get();
        $blogCategories = BlogCategory::where('is_active', 1)->get();

        // Sản phẩm mới nhất (có phân trang hoặc limit tùy bạn)
    $newProducts = Product::with(['variants.attributeValues.attribute'])
        ->latest()
        ->where('is_active', 1)
        ->paginate(8);
        

    // Sản phẩm bán chạy
    $orderCompletedStatusId = DB::table('order_statuses')
        ->where('name', 'Đã hoàn thành')
        ->value('id');

    $bestSellingProductIds = DB::table('orders')
        ->join('order_order_status', function ($join) use ($orderCompletedStatusId) {
            $join->on('orders.id', '=', 'order_order_status.order_id')
                ->where('order_order_status.order_status_id', $orderCompletedStatusId)
                ->where('order_order_status.is_current', 1);
        })
        ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total'))
        ->groupBy('order_items.product_id')
        ->orderByDesc('total')
        ->limit(8)
        ->pluck('product_id');

    $bestSellingProducts = Product::with(['variants.attributeValues.attribute'])
        ->whereIn('id', $bestSellingProductIds)
        ->where('is_active', 1)
        ->paginate(8);

        $reviews = Review::with('user', 'product')
            ->where('is_active', 1)
            ->where('rating', 5)
            ->whereHas('product')
            ->whereHas('user')
            ->latest()
            ->take(6)
            ->get();

        $user = Auth::user();

        // Nếu đã đăng nhập → lấy ra danh sách coupon mà user đã nhận
        $claimedIds = $user
            ? \DB::table('coupon_user')->where('user_id', $user->id)->pluck('coupon_id')
            : collect([]);

        // Chỉ lấy các coupon chưa nhận, còn hiệu lực
        $coupons = Coupon::with('restriction')
            ->where('is_active', 1)
            ->whereNotIn('id', $claimedIds) // loại bỏ coupon đã claim
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->latest()
            ->take(6)
            ->get();

        if ($request->ajax()) {
            return view('client.components.products-list', compact('products'))->render();
        }



        return view('client.home', compact('products', 'categories', 'blogs','reviews', 'bestSellingProducts','blogCategories','coupons'));


    }

     public function showCategoriesInMegaMenu()
    {
        // Lấy tất cả danh mục cha và danh mục con của chúng
        $parentCategories = Category::whereNull('parent_id')
            ->with('children')
            ->where('is_active', 1)
            ->orderBy('ordinal')
            ->get();

        // Chia danh sách các danh mục cha thành 2 hoặc 3 nhóm
        // để phân bổ vào các cột.
        $chunks = $parentCategories->chunk(ceil($parentCategories->count() / 2));


    return view('client.partials.mega_menu', compact('chunks'));
}

}
