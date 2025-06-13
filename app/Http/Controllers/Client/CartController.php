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
        $userId = Auth::id();
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        return view('client.carts.index', compact('cartItems'));
    }
    public function add(Request $request)
    {
        $userId = Auth::id();
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
    $userId = Auth::id();
    $productId = $request->input('product_id');
    $quantity = (int) $request->input('quantity');

    $item = CartItem::where('user_id', $userId)->where('product_id', $productId)->with('product')->first();

    if ($item) {
        $item->quantity = max(1, $quantity);
        $item->save();

        if ($request->ajax()) {
            $total = CartItem::where('user_id', $userId)->get()->sum(fn($i) => $i->product->price * $i->quantity);

            return response()->json([
                'success' => true,
                'item_total' => number_format($item->product->price * $item->quantity),
                'cart_total' => number_format($total),
                'quantity' => $item->quantity,
                'total_quantity' => CartItem::where('user_id', $userId)->sum('quantity')

            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật số lượng thành công!');
    }

    return response()->json(['success' => false], 404);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $item = CartItem::where('user_id', $userId)->where('id', $id)->first();
        if($item) {
            $item->delete();
        }
        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }
}
