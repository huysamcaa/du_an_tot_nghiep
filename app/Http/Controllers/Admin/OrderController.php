<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shared\Order;
use Illuminate\Http\Request;

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
}