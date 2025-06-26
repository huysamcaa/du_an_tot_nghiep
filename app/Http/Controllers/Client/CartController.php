<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $userId = Auth::id();
        $userId = Auth::id();

        // Eager load thông tin sản phẩm, biến thể và thuộc tính của biến thể
        $cartItems = CartItem::with(['product', 'variant.attributeValues.attribute'])
                            ->where('user_id', $userId)
                            ->get();
        return view('client.carts.index', compact('cartItems'));
    }
    public function add(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');
        $colorId = $request->input('color'); // Lấy màu sắc từ form
        $sizeId = $request->input('size');  // Lấy kích thước từ form
        $quantity = (int)$request->input('quantity') ?: 1; // Số lượng mặc định là 1 nếu không có giá trị

        // Tìm biến thể sản phẩm theo màu sắc và kích thước
        $variant = ProductVariant::where('product_id', $productId)
            ->whereHas('attributeValues', function($q) use ($colorId) {
                $q->where('attribute_value_id', $colorId);  // Kiểm tra màu sắc
            })
            ->whereHas('attributeValues', function($q) use ($sizeId) {
                $q->where('attribute_value_id', $sizeId);  // Kiểm tra kích thước
            })
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Biến thể không tồn tại');
        }

        // Kiểm tra giỏ hàng đã có sản phẩm với biến thể này chưa
        $item = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($item) {
            // Nếu đã có, cộng thêm số lượng vào sản phẩm hiện tại trong giỏ hàng
            $item->quantity += $quantity;
            $item->save();
        } else {
            // Nếu chưa có, tạo mới một mục trong giỏ hàng
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'product_variant_id' => $variant->id, // Lưu product_variant_id để phân biệt các biến thể
                'quantity' => $quantity
            ]);
        }

        // Trả lại tổng số sản phẩm trong giỏ nếu là yêu cầu AJAX
        if ($request->ajax()) {
            $totalProduct = CartItem::where('user_id', $userId)->sum('quantity');
            return response()->json([
                'success' => true,
                'totalProduct' => $totalProduct
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $userId = Auth::id(); // fallback ID nếu chưa login

        $cartItemId = $request->input('cart_item_id');
        $action = $request->input('quantity'); // có thể là 'increase' hoặc 'decrease'

        $item = CartItem::with('product')
            ->where('user_id', $userId)
            ->where('id', $cartItemId)
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
        $userId = Auth::id();
        $item = CartItem::where('user_id', $userId)->where('id', $id)->first();
        if($item) {
            $item->delete();
        }
        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }
}