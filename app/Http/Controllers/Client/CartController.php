<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $userId = Auth::id();
        $userId = Auth::id() ?? 2;
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        return view('client.carts.index', compact('cartItems'));
    }
    public function add(Request $request)
    {
        // $userId = Auth::id();
        $userId = Auth::id() ?? 2;
        $productId = $request->input('product_id');

        $item = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();
        if($item) {
            $item->quantity += 1;
            $item->save();
        }else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
        if($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $userId = Auth::id() ?? 2; // fallback ID nếu chưa login

        $productId = $request->input('product_id');
        $action = $request->input('quantity'); // có thể là 'increase' hoặc 'decrease'

        $item = CartItem::with('product')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        if ($action === 'increase') {
            $item->quantity += 1;
        } elseif ($action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();

        $cartTotal = CartItem::where('user_id', $userId)
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

        return response()->json([
            'success' => true,
            'item_total' => number_format($item->product->price * $item->quantity),
            'cart_total' => number_format($cartTotal),
            'new_quantity' => $item->quantity,
            'grand_total' => number_format($cartTotal + 30000)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $userId = Auth::id();
        $userId = Auth::id() ?? 2;
        $item = CartItem::where('user_id', $userId)->where('id', $id)->first();
        if($item) {
            $item->delete();
        }
        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }
}
