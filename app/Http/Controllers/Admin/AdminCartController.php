<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;

class AdminCartController extends Controller
{
    public function index()
    {
        // lấy danh sách user_id có giỏ hàng
        $userIds = CartItem::select('user_id')
            ->selectRaw('MAX(updated_at) as last_updated') // lấy lần cập nhật cuối cùng của giỏ cho mỗi user
            ->groupBy('user_id')
            ->orderByDesc('last_updated') // đưa user hoạt động gần nhất lên
            ->paginate('5');

        // lấy all cart ittem của những user này
        $cartItems = CartItem::with(['user', 'product', 'variant'])
            ->whereIn('user_id', $userIds->pluck('user_id'))
            ->get()
            ->groupBy('user_id');

        return view('admin.carts.index', [
            'cartItems' => $cartItems,
            'userIds' => $userIds // dùng để gọi links
        ]);
    }
    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $action = $request->input('action');

        if($action=='increase')
        {
            $item->quantity += 1;
        }elseif($action=='decrease'&&$item->quantity > 1)
        {
            $item->quantity -= 1;
        }
        $item->save();

        // tính tổng tiền
        $cartItems = CartItem::where('user_id', $item->user_id)->get();
        $total = $cartItems->sum(function($i){
            return ($i->variant->price ?? $i->product->price) * $i->quantity;
        });
        
        if($request->ajax()){
            return response()->json([
                'success' => true,
                'new_quantity' => $item->quantity,
                'item_total' => number_format(($item->variant->price ?? $item->product->price) * $item->quantity),
                'cart_total' => number_format($total),
                'user_id' => $item->user_id
            ]);
        }
    }
    public function destroy($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();
        return redirect()->back()->with('success', 'Sản phẩm đã được xoá khỏi giỏ hàng');
    }

}
