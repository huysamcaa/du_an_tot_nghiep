<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shared\Order;
use Illuminate\Http\Request;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\OrderStatus;

class OrderController extends Controller
{
    // Danh sách đơn hàng COD
    public function index()
    {
        
        $orders = Order::where('payment_id', 2)->orderByDesc('created_at')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $usedStatusIds = $order->orderOrderStatuses->pluck('order_status_id')->toArray();
        $statuses = OrderStatus::orderBy('id')->get();
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Xác nhận đã thanh toán COD
    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->is_paid = true;
        $order->save();
        return redirect()->back()->with('success', 'Đã xác nhận thanh toán COD');
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.destroy')->with('success', 'Đã xóa đơn hàng');
    }
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        OrderOrderStatus::create([
            'order_id' => $orderId,
            'order_status_id' => $request->order_status_id,
            'modified_by' => auth()->id(),
            // created_at sẽ tự động nếu có timestamps
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
