<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shared\Order;
use Illuminate\Http\Request;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng COD
    public function index()
    {
        $orders = Order::whereIn('payment_id', [2, 3, 4])->orderByDesc('created_at')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['items', 'currentStatus.orderStatus', 'orderOrderStatuses'])->findOrFail($id);

        $usedStatusIds = $order->orderOrderStatuses->pluck('order_status_id')->toArray();
        $statuses = OrderStatus::orderBy('id')->get();

        // Lấy trạng thái hiện tại (lớn nhất trong lịch sử)
        $currentStatusId = $order->currentStatus?->order_status_id ?? 1;
        // Trạng thái tiếp theo
        $nextStatusId = $currentStatusId < 5 ? $currentStatusId + 1 : null; // 5 là trạng thái cuối cùng hợp lệ

        return view('admin.orders.show', compact('order', 'statuses', 'usedStatusIds', 'nextStatusId', 'currentStatusId'));
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
            'order_status_id' => 'nullable|exists:order_statuses,id',
        ]);

        // Lấy trạng thái hiện tại
        $currentStatus = OrderOrderStatus::where('order_id', $orderId)
            ->where('is_current', 1)
            ->first();

        // Nếu trạng thái hiện tại là "Đã hoàn thành"
        if ($currentStatus && $currentStatus->order_status_id == 5) {
            // Chỉ cho phép chuyển sang "Hoàn trả" (id = 7)
            if ($request->order_status_id != 7) {
                return back()->with('error', 'Đơn hàng đã hoàn thành, chỉ được chuyển sang trạng thái Hoàn trả!');
            }
        }
        if ($currentStatus && in_array($currentStatus->order_status_id, [1, 2, 3, 4]) && $request->order_status_id == 7) {
            return back()->with('error', 'Chỉ đơn hàng đã hoàn thành mới được hoàn trả!');
        }
        // Đặt tất cả trạng thái cũ về 0
        OrderOrderStatus::where('order_id', $orderId)->update(['is_current' => 0]);
        OrderOrderStatus::create([
            'order_id' => $orderId,
            'order_status_id' => $request->order_status_id,
            'modified_by' => Auth::id(),
            'is_current' => 1,
            // created_at sẽ tự động nếu có timestamps
        ]);
        if ($request->order_status_id == 6) {
            if (!$currentStatus || $currentStatus->order_status_id != 1) {
                return back()->with('error', 'Chỉ đơn hàng đang chờ xác nhận mới được phép hủy!');
            }

            // Đếm số đơn bị hủy trong ngày của user
            $order = Order::find($orderId);
            if ($order) {
                $userId = $order->user_id;
                $today = now()->toDateString();

                $cancelCount = Order::where('user_id', $userId)
                    ->whereHas('orderOrderStatuses', function ($q) use ($today) {
                        $q->where('order_status_id', 6)
                            ->whereDate('created_at', $today)
                            ->where('is_current', 1);
                    })
                    ->count();

                if ($cancelCount >= 5) {
                    $user = \App\Models\User::find($userId);
                    if ($user && $user->status !== 'locked') {
                        $user->status = 'locked';
                        $user->reason_lock = 'Hủy 5 đơn trong 1 ngày';
                        $user->save();
                    }
                }
            }
        }
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
