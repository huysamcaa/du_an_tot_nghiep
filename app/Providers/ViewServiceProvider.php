<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\CartItem;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('client.layouts.header', function ($view) {
        $userId = Auth::id() ?? 2; // fallback nếu chưa login
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $totalProduct = $cartItems->count();
        $view->with([
            'cartItems' => $cartItems,
            'total' => $total,
            'totalProduct' => $totalProduct
        ]);
        View::composer('partials.cart_widget', function ($view) {
            $userId = Auth::id();
            $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $userId)->get();

            $total = $cartItems->sum(function ($item) {
                $variant = $item->variant;
                return ($variant && $variant->sale_price > 0 && $variant->sale_price < $variant->price)
                    ? $variant->sale_price * $item->quantity
                    : ($variant->price ?? $item->product->price) * $item->quantity;
            });

            $totalProduct = $cartItems->sum('quantity');

            $view->with(compact('cartItems', 'total', 'totalProduct'));
        });
    });
    }
}
