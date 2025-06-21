<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id ?? 2; // hoặc giả định 2 cho test
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('client.carts.checkout', compact('cartItems', 'total'));
    }
}
