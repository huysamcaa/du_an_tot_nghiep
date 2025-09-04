<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\CartItem;
use App\Models\Admin\Category;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View Composer 1: Cho header (Giỏ hàng)
        View::composer('client.layouts.header', function ($view) {
    $userId = Auth::id() ?? 2;
    $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $userId)->get();

    $total = $cartItems->sum(function ($item) {
        $variant = $item->variant;
        return ($variant && $variant->sale_price > 0 && $variant->sale_price < $variant->price)
            ? $variant->sale_price * $item->quantity
            : ($variant->price ?? 0) * $item->quantity;
    });

    $totalProduct = $cartItems->count();

    $view->with([
        'cartItems' => $cartItems,
        'total' => $total,
        'totalProduct' => $totalProduct
    ]);
});


        // View Composer 2: Cho cart_widget
        View::composer('partials.cart_widget', function ($view) {
    $userId = Auth::id();
    $cartItems = CartItem::with('variant')->where('user_id', $userId)->get();

    $total = $cartItems->sum(function ($item) {
        $variant = $item->variant;
        return ($variant && $variant->sale_price > 0 && $variant->sale_price < $variant->price)
            ? $variant->sale_price * $item->quantity
            : ($variant->price ?? 0) * $item->quantity;
    });

    $totalProduct = $cartItems->sum('quantity');

    $view->with(compact('cartItems', 'total', 'totalProduct'));
});


       View::composer('client.layouts.header', function ($view) {
            $categories = \App\Models\Admin\Category::query()
                ->withCount('children') // Đếm số lượng danh mục con
                ->where('is_active', 1) // Chỉ lấy các danh mục đang hoạt động
                ->having('children_count', 0) // Chỉ lấy các danh mục không có con
                ->orderBy('ordinal', 'desc') // Sắp xếp theo ordinal giảm dần
                ->take(10) // Giới hạn 10 danh mục
                ->get();

            // Chia 10 danh mục thành 2 cột
            $chunks = $categories->chunk(ceil($categories->count() / 2));

            $view->with('chunks', $chunks);
        });
    }
}
