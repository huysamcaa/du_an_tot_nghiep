<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Lấy danh sách sản phẩm trong giỏ hàng cùng với thông tin sản phẩm và biến thể
        $cartItems = CartItem::with(['product', 'variant.attributeValues.attribute'])
                            ->where('user_id', $userId)
                            ->get();
        return view('client.carts.index', compact('cartItems'));
    }
    public function add(Request $request)
    {
        // Nếu chưa đăng nhập, trả về JSON báo lỗi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'unauthenticated' => true,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.'
            ], 401);
        }

        $userId = Auth::id();
        $productId = $request->input('product_id');

        // Lấy các giá trị thuộc tính như màu và size
        $attributeValueIds = array_filter([$request->input('color'), $request->input('size')]);

        // Nếu không truyền số lượng thì mặc định là 1
        $quantity = (int) $request->input('quantity') ?: 1;

        // Tìm biến thể phù hợp với sản phẩm và các thuộc tính đã chọn
        $variant = ProductVariant::where('product_id', $productId)
            ->whereHas('attributeValues', fn($q) =>
                $q->whereIn('attribute_value_id', $attributeValueIds),
                '=', count($attributeValueIds)
            )
            ->withCount('attributeValues')
            ->having('attribute_values_count', '=', count($attributeValueIds))
            ->first();

        // Nếu không tìm thấy biến thể thì trả về lỗi
        if (!$variant) {
            return response()->json(['success' => false]);
        }

        // Tạo mới hoặc cập nhật số lượng nếu đã có biến thể này trong giỏ hàng
        $item = CartItem::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'product_variant_id' => $variant->id,
            ],
            [
                'quantity' => DB::raw("quantity + $quantity") // Tăng số lượng
            ]
        );

        // Nếu là AJAX request thì trả về tổng số lượng sản phẩm trong giỏ hàng
        if ($request->ajax()) {
            $totalProduct = CartItem::where('user_id', $userId)->sum('quantity');
            return response()->json(['success' => true, 'totalProduct' => $totalProduct]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $cartItemId = $request->input('cart_item_id');
        $action = $request->input('quantity'); // 'increase' hoặc 'decrease'

        // Tìm sản phẩm trong giỏ hàng kèm theo thông tin sản phẩm
        $item = CartItem::with('product')
            ->where('user_id', $userId)
            ->find($cartItemId);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
        }

        if ($action === 'increase') {
            $item->quantity++;
        } elseif ($action === 'decrease' && $item->quantity > 1) {
            $item->quantity--;
        }

        $item->save();

        // Tính lại tổng tiền giỏ hàng
        $cartTotal = CartItem::with('product')
            ->where('user_id', $userId)
            ->get()
            ->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);

        // Tính lại giá của sản phẩm hiện tại
        $itemPrice = $item->product->sale_price ?? $item->product->price;

        return response()->json([
            'success' => true,
            'item_total' => number_format($itemPrice * $item->quantity), // Tổng giá của sản phẩm đó
            'cart_total' => number_format($cartTotal), // Tổng giá toàn bộ giỏ hàng
            'new_quantity' => $item->quantity, // Số lượng mới
            'grand_total' => number_format($cartTotal + 30000) // Tổng tiền sau phí ship 30.000đ
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
        $attributeValueIds = array_filter([$request->input('color'), $request->input('size')]);

        // Tìm biến thể có đầy đủ các thuộc tính
        $variant = ProductVariant::where('product_id', $request->input('product_id'))
            ->whereHas('attributeValues', fn($q) =>
                $q->whereIn('attribute_value_id', $attributeValueIds),
                '=', count($attributeValueIds)
            )
            ->withCount('attributeValues')
            ->having('attribute_values_count', '=', count($attributeValueIds))
            ->first();

        // Trả về true nếu tìm thấy, false nếu không
        return response()->json(['found' => (bool) $variant]);
    }
}
