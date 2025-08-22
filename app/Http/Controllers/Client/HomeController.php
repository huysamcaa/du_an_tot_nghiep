<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Blog;
use App\Models\Admin\Review;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('is_active', 1)
            ->latest()
            ->paginate(8);

        $categories = Category::where('is_active', 1)
            ->withCount('products')
            ->orderBy('ordinal')
            ->get();

        $blogs = Blog::latest()->take(3)->get();

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

        if ($request->ajax()) {
            return view('client.components.products-list', compact('products'))->render();
        }

        return view('client.home', compact('products', 'categories', 'blogs','reviews', 'bestSellingProducts'));
    }
}
