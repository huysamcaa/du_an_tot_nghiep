<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Lấy danh sách sản phẩm trong giỏ hàng cùng với thông tin sản phẩm và biến thể
        $cartItems = CartItem::with(['product', 'variant'])
                            ->where('user_id', $userId)
                            ->get();
        return view('client.carts.index', compact('cartItems'));
    }


    public function add(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity') ?: 1;
        $attributeValueIds = $request->input('attribute_values',[]); //lấy mảng attribute_values

        // Tìm biến thể phù hợp
        $variant = ProductVariant::where('product_id', $productId)
            ->with('attributeValues')
            ->get()
            ->first(function($variant) use ($attributeValueIds){
                $variantValues = $variant->attributeValues->pluck('id')->toArray();
                sort($variantValues);
                $selected = $attributeValueIds;
                sort($selected);
                return $variantValues == $selected;
            });

        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Biến thể sản phẩm không tồn tại.']);
        }

        // Kiểm tra số lượng hiện có trong giỏ
        $existingItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variant->id)
            ->first();

        $currentQuantityInCart = $existingItem ? $existingItem->quantity : 0;
        $totalAfterAdd = $currentQuantityInCart + $quantity;

        $availableStock = $variant->stock - $currentQuantityInCart;

        if ($quantity > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ còn ' . $availableStock . ' sản phẩm'
            ]);
        }

        // Thêm hoặc cập nhật
        $item = CartItem::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'product_variant_id' => $variant->id,
            ],
            [
                'quantity' => $totalAfterAdd
            ]
        );

        if ($request->ajax()) {
            $cartItems = CartItem::where('user_id', $userId)->with(['product','variant'])->get();
            $total = $cartItems->sum(function($item) {
                $variant = $item->variant;

                if ($variant) {
                    $price = $variant->is_sale ? $variant->sale_price : $variant->price;
                } else {
                    $price = $item->product->is_sale ? $item->product->sale_price : $item->product->price;
                }
                return $price * $item->quantity;
            });
            $totalProduct = $cartItems->sum('quantity');
            // render phần icon giỏ hàng
            $cartIcon = view('partials.cart_widget', compact('cartItems','total','totalProduct'))->render();

            return response()->json(['success' => true, 'totalProduct' => $totalProduct, 'cartWidget' => $cartIcon]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }


    public function update(Request $request)
    {
        $userId = Auth::id();
        $cartItemId = $request->input('cart_item_id');
        $action = $request->input('quantity'); // 'increase' hoặc 'decrease'

        // Tìm sản phẩm trong giỏ hàng kèm theo thông tin sản phẩm và biến thể
        $item = CartItem::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->find($cartItemId);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
        }

        if ($action === 'increase') {
            $stock = $item->variant->stock ?? 0;
            if($item->quantity < $stock){
                $item->quantity++;
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Trong kho còn ' . $stock . ' sản phẩm'
                ]);
            }
        } elseif ($action === 'decrease') {
            $item->quantity = max(1, $item->quantity - 1); // Giảm nhưng không thấp hơn 1
        }

        $item->save();

        // Tính lại giá của sản phẩm hiện tại
        $itemPrice = $item->variant->is_sale
            ? $item->variant->sale_price
            : $item->variant->price;

        return response()->json([
            'success' => true,
            'item_total' => number_format($itemPrice * $item->quantity),
            'new_quantity' => $item->quantity
        ]);
    }

    // Xoá sản phẩm khỏi giỏ hàng
    public function destroy($id)
    {
        $userId = Auth::id();

        // Xoá sản phẩm trong giỏ hàng nếu tồn tại
        CartItem::where('user_id', $userId)->where('id', $id)->delete();

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }

    // Kiểm tra biến thể có tồn tại không khi chọn thuộc tính
    public function checkVariant(Request $request)
    {
        // Lấy các thuộc tính đã chọn (color, size,...)
        $attributeValueIds = $request->input('attribute_values',[]);

        // Tìm biến thể có đầy đủ các thuộc tính
        $variant = ProductVariant::where('product_id', $request->input('product_id'))
            ->with('attributeValues')
            ->get()
            ->first(function($variant) use($attributeValueIds){
                $variantValues = $variant->attributeValues->pluck('id')->toArray();
                sort($variantValues);
                $selected = $attributeValueIds;
                sort($selected);
                return $variantValues == $selected;
            });

        if (!$variant) {
            return response()->json(['found' => false]);
        }

        $price = $variant->is_sale ? $variant->sale_price : $variant->price;

        return response()->json([
            'found' => true,
            'price' => number_format($price, 0, ',', '.') . 'đ',
            'image' => $variant->thumbnail
                ? asset('storage/' . $variant->thumbnail)
                : null
        ]);
    }
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm nào được chọn']);
        }

        CartItem::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

}
