<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shared\Order;
use Illuminate\Http\Request;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    // Danh sách đơn hàng COD
    public function index()
    {
        $orders = Order::whereIn('payment_id', [2, 3, 4])->orderByDesc('created_at')->paginate(100);
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

        // Kiểm tra nếu đơn hàng đã thanh toán online
        if (in_array($order->payment_id, [3, 4])) { // 3: MOMO, 4: VNPAY (hoặc mã id khác của thanh toán online)

            // Tạo trạng thái "Đã thanh toán" nếu chưa có
            $orderStatusPaid = OrderStatus::where('name', 'Đã thanh toán')->first();
            if ($orderStatusPaid) {
                // Cập nhật trạng thái "Đã thanh toán"
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => $orderStatusPaid->id,
                    'modified_by' => Auth::id(),
                    'is_current' => 1, // Đặt trạng thái này là trạng thái hiện tại
                ]);
            }

            // Chuyển trạng thái từ "Đã thanh toán" sang "Chờ xác nhận"
            $orderStatusWaiting = OrderStatus::where('name', 'Chờ xác nhận')->first();
            if ($orderStatusWaiting) {
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => $orderStatusWaiting->id,
                    'modified_by' => Auth::id(),
                    'is_current' => 1, // Đặt trạng thái này là trạng thái hiện tại
                ]);
            }
        } else {
            // Nếu không phải thanh toán online, vẫn cập nhật như trước
            $order->is_paid = true;
            $order->save();
        }

        return redirect()->back()->with('success', 'Đã xác nhận thanh toán hoặc chuyển trạng thái sang Chờ xác nhận');
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng');
    }
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $order = new Order();
        $connection = $order->getConnection();
        $connection->beginTransaction();

        try {
            $currentStatus = OrderOrderStatus::where('order_id', $orderId)
                ->where('is_current', 1)
                ->first();

            if ($currentStatus) {
                if ($currentStatus->order_status_id == 5 && $request->order_status_id != 7) {
                    $connection->rollBack();
                    return back()->with('error', 'Đơn hàng đã hoàn thành, chỉ được chuyển sang trạng thái Hoàn trả!');
                }

                if (in_array($currentStatus->order_status_id, [1, 2, 3, 4]) && $request->order_status_id == 7) {
                    $connection->rollBack();
                    return back()->with('error', 'Chỉ đơn hàng đã hoàn thành mới được hoàn trả!');
                }
            }

            if ($request->order_status_id == 2) {
            $order = Order::with('items.variant')->findOrFail($orderId);
            
            // Kiểm tra payment_id = 1 (COD)
            if ($order->payment_id == 1) {
                foreach ($order->items as $item) {
                    if (!$item->variant) {
                        $connection->rollBack();
                        return back()->with('error', 'Sản phẩm không tồn tại!');
                    }

                    if ($item->variant->stock < $item->quantity) {
                        $connection->rollBack();
                        return back()->with('error', 'Sản phẩm ' . ($item->variant->sku ?? '') . ' không đủ số lượng tồn kho!');
                    }

                    $item->variant->stock -= $item->quantity;
                    $item->variant->save();
                }
            }
        }
        if ($request->order_status_id == 6) {
            $order = Order::with('items.variant')->findOrFail($orderId);
            
            // Kiểm tra payment_id = 1 (COD)
            if ($order->payment_id == 3 || $order->payment_id == 4) {
                foreach ($order->items as $item) {
                    if (!$item->variant) {
                        $connection->rollBack();
                        return back()->with('error', 'Sản phẩm không tồn tại!');
                    }

                    if ($item->variant->stock < $item->quantity) {
                        $connection->rollBack();
                        return back()->with('error', 'Sản phẩm ' . ($item->variant->sku ?? '') . ' không đủ số lượng tồn kho!');
                    }

                    $item->variant->stock += $item->quantity;
                    $item->variant->save();
                }
            }
        }

            // if ($request->order_status_id == 6) {
            //     $order = Order::with('items.variant')->findOrFail($orderId);
            //     foreach ($order->items as $item) {
            //         if (!$item->variant) {
            //             $connection->rollBack();
            //             return back()->with('error', 'Sản phẩm không tồn tại!');
            //         }

            //         $item->variant->stock += $item->quantity;
            //         $item->variant->save();
            //     }
            // }

            OrderOrderStatus::where('order_id', $orderId)->update(['is_current' => 0]);

            OrderOrderStatus::create([
                'order_id' => $orderId,
                'order_status_id' => $request->order_status_id,
                'modified_by' => Auth::id(),
                'is_current' => 1,
            ]);

            if ($request->order_status_id == 6) {
                $this->handleCancelOrder($orderId);
            }

            $connection->commit();
            return back()->with('success', 'Cập nhật trạng thái thành công!');
        } catch (Exception $e) {
            $connection->rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

}
