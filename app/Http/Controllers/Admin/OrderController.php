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
 public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);

    $query = Order::with(['currentStatus.orderStatus', 'items.product', 'user'])
        ->whereIn('payment_id', [2, 3, 4]);

    // Filter by status
    if ($request->has('status') && $request->status != '') {
        $query->whereHas('currentStatus', function($q) use ($request) {
            $q->where('order_status_id', $request->status);
        });
    }

    // Filter by date range
    if ($request->has('from_date') && $request->from_date != '') {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->has('to_date') && $request->to_date != '') {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // Filter by customer (name or phone)
    if ($request->has('customer') && $request->customer != '') {
        $query->where(function($q) use ($request) {
            $q->where('fullname', 'LIKE', '%'.$request->customer.'%')
              ->orWhere('phone_number', 'LIKE', '%'.$request->customer.'%');
        });
    }

    // Filter by order code
    if ($request->has('order_code') && $request->order_code != '') {
        $query->where('code', 'LIKE', '%'.$request->order_code.'%');
    }

    // Filter by payment method
    if ($request->has('payment_method') && $request->payment_method != '') {
        $paymentMethods = [
            'COD' => 1,
            'transfer' => 2,
            'wallet' => 3,
            'credit_card' => 4
        ];
        if (isset($paymentMethods[$request->payment_method])) {
            $query->where('payment_id', $paymentMethods[$request->payment_method]);
        }
    }

    // Filter by amount range
    if ($request->has('min_amount') && $request->min_amount != '') {
        $query->where('total_amount', '>=', $request->min_amount);
    }
    if ($request->has('max_amount') && $request->max_amount != '') {
        $query->where('total_amount', '<=', $request->max_amount);
    }

    // Filter by city
    if ($request->has('city') && $request->city != '') {
        $query->where('address', 'LIKE', '%'.$request->city.'%');
    }

    // Filter by product
    if ($request->has('product') && $request->product != '') {
        $query->whereHas('items', function($q) use ($request) {
            $q->whereHas('product', function($q2) use ($request) {
                $q2->where('name', 'LIKE', '%'.$request->product.'%')
                   ->orWhere('code', 'LIKE', '%'.$request->product.'%');
            });
        });
    }

    $orders = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

    return view('admin.orders.index', compact('orders'));
}


    // Xem chi tiết đơn hàng
    public function show($id)
{
    $order = Order::with([
        'user',
        'items.product',
        'items.variant.attributeValues.attribute',
        'statuses.orderStatus', // Đổi tên mối quan hệ từ 'orderOrderStatuses' sang 'statuses' để code nhất quán
        'currentStatus.orderStatus'
    ])->findOrFail($id);
    
    // Nếu bạn không muốn đổi tên mối quan hệ trong model, bạn có thể truyền lại biến:
    $order->statuses = $order->orderOrderStatuses;

    $usedStatusIds = $order->statuses->pluck('order_status_id')->toArray();
    $statuses = OrderStatus::orderBy('id')->get();

    // Lấy trạng thái hiện tại
    $currentStatusId = $order->currentStatus?->order_status_id ?? 1;

    // Trạng thái tiếp theo
    $nextStatusId = $currentStatusId < 5 ? $currentStatusId + 1 : null;

    // Truyền biến 'statuses' đã được chuẩn bị vào view
    return view('admin.orders.show', compact('order', 'statuses', 'usedStatusIds', 'nextStatusId', 'currentStatusId'));
}
    // protected function handleCancelOrder($orderId)
    // {
    //     // Ví dụ logic đơn giản: hủy đơn hàng thì cập nhật cờ is_paid = false (nếu cần)
    //     $order = Order::find($orderId);
    //     if ($order) {
    //         $order->is_paid = false; // hoặc các hành động khác
    //         $order->save();
    //     }
    // }
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

 public function cancel(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);

    // Kiểm tra quyền sở hữu đơn hàng
    if ($order->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
    }

    // Kiểm tra trạng thái đơn hàng
    $currentStatus = $order->currentStatus->orderStatus->name ?? '';
    if ($currentStatus !== 'Chờ Xác Nhận') {
        return redirect()->back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
    }

    // Validate dữ liệu
    $request->validate([
        'cancel_reason' => 'required',
        'other_reason' => 'required_if:cancel_reason,other',
    ], [
        'cancel_reason.required' => 'Vui lòng chọn lý do hủy đơn hàng',
        'other_reason.required_if' => 'Vui lòng nhập lý do hủy đơn hàng',
    ]);

    // Xử lý lý do hủy
    $cancelReason = $request->input('cancel_reason');
    if ($cancelReason === 'other') {
        $cancelReason = $request->input('other_reason');
    }

    // Cập nhật trạng thái hiện tại thành không còn hiện hành
    OrderOrderStatus::where('order_id', $order->id)
        ->where('is_current', 1)
        ->update(['is_current' => 0]);

    // Tạo trạng thái hủy mới
    OrderOrderStatus::create([
        'order_id' => $order->id,
        'order_status_id' => 6, // ID trạng thái "Đã hủy"
        'modified_by' => Auth::id(),
        'cancel_reason' => $cancelReason,
        'customer_feedback' => $request->input('cancel_feedback'),
        'notes' => 'Đơn hàng đã bị hủy bởi khách hàng',
        'is_current' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Cập nhật trạng thái đơn hàng chính
    $order->update([
        'status' => 'cancelled',
        'is_refund_cancel' => 1,
        'updated_at' => now(),
    ]);

    return redirect()->route('client.orders.purchase.history')
        ->with('success', 'Đơn hàng #' . $order->code . ' đã được hủy thành công.');
}
    public function cancel2(Request $request, $orderId)
{
    try {
        // Validate dữ liệu
        $rules = [
            'cancel_reason' => 'required|string',
            'other_reason' => 'required_if:cancel_reason,other|string|nullable',
            'cancel_feedback' => 'nullable|string|max:500',
        ];

        // Lấy thông tin đơn hàng
        $order = Order::with('currentStatus.orderStatus')->findOrFail($orderId);

        // Thêm rules cho thông tin ngân hàng nếu đơn hàng đã thanh toán
        if ($order->is_paid) {
            $rules = array_merge($rules, [
                'bank_name' => 'required|string',
                'account_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:20',
                'phone_number' => 'required|string|max:15',
            ]);
        }

        $validated = $request->validate($rules);

        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders.purchase.history')
                ->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        // Kiểm tra trạng thái đơn hàng (ID 1 = Chờ Xác Nhận)
        if ($order->currentStatus->order_status_id !== 1) {
            return redirect()->route('client.orders.purchase.history')
                ->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        // Xử lý lý do hủy
        $cancelReason = $request->cancel_reason === 'other'
            ? $request->other_reason
            : $request->cancel_reason;

        // 1. Cập nhật trạng thái cũ
        OrderOrderStatus::where('order_id', $order->id)
            ->where('is_current', 1)
            ->update(['is_current' => 0]);

        // 2. Tạo trạng thái hủy mới (ID 6 = Đã Hủy)
        $newStatusData = [
            'order_id' => $order->id,
            'order_status_id' => 6,
            'modified_by' => Auth::id(),
            'cancel_reason' => $cancelReason,
            'customer_feedback' => $request->cancel_feedback,
            'notes' => $this->generateCancelNotes($cancelReason, $request->cancel_feedback),
            'is_current' => 1,
        ];

        // Thêm thông tin ngân hàng nếu đơn hàng đã thanh toán
        if ($order->is_paid) {
            $newStatusData = array_merge($newStatusData, [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'phone_number' => $request->phone_number,
            ]);
        }

        $newStatus = OrderOrderStatus::create($newStatusData);

        if (!$newStatus) {
            return redirect()->route('client.orders.purchase.history')
                ->with('error', 'Không thể tạo trạng thái mới');
        }

        // 3. Cập nhật đơn hàng

        $order->is_refund_cancel = 1; // 1 Nếu hủy hàng, 0 Nếu không hủy hàng


        if (!$order->save()) {
            return redirect()->route('client.orders.purchase.history')
                ->with('error', 'Không thể cập nhật đơn hàng');
        }


        if (!$order->save()) {
            return redirect()->route('client.orders.purchase.history')
                ->with('error', 'Không thể cập nhật đơn hàng');
        }

        // Gửi thông báo
        $bankInfo = $order->is_paid ? [
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'phone_number' => $request->phone_number,
        ] : null;

        $this->sendCancellationNotification($order, $cancelReason, $bankInfo);

        return redirect()->route('client.orders.purchase.history')
            ->with('success', 'Đơn hàng #' . $order->code . ' đã được hủy thành công.');

    } catch (\Exception $e) {
        return redirect()->route('client.orders.purchase.history')
            ->with('error', 'Có lỗi xảy ra khi hủy đơn hàng: '.$e->getMessage());
    }
}

private function generateCancelNotes($reason, $feedback)
{
    $notes = "Lý do hủy: $reason";
    if ($feedback) {
        $notes .= " | Góp ý: $feedback";
    }
    return $notes;
}

private function sendCancellationNotification($order, $reason, $bankInfo)
{
    // Gửi email/thông báo cho khách hàng và admin
    // Triển khai logic gửi thông báo tại đây
}
protected function handleCancelOrder($orderId)
    {
        // Ví dụ logic đơn giản: hủy đơn hàng thì cập nhật cờ is_paid = false (nếu cần)
        $order = Order::find($orderId);
        if ($order) {
            $order->is_paid = false; // hoặc các hành động khác
            $order->save();
        }
    }
    public function markAsReceived(Request $request, $orderId)
{
    // Tìm đơn hàng
    $order = Order::findOrFail($orderId);

    // Kiểm tra quyền sở hữu đơn hàng
    if ($order->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền xác nhận đơn hàng này.');
    }

    // Kiểm tra trạng thái đơn hàng (chỉ cho phép xác nhận khi ở trạng thái "Đã hoàn thành")
    $currentStatus = $order->currentStatus->orderStatus->name ?? '';
    if ($currentStatus !== 'Đang giao hàng') {
        return redirect()->back()->with('error', 'Chỉ có thể xác nhận đã nhận hàng khi đơn ở trạng thái "Đang giao hàng".');
    }

    // Cập nhật trạng thái hiện tại thành không còn là current
    OrderOrderStatus::where('order_id', $order->id)
        ->where('is_current', 1)
        ->update(['is_current' => 0]);

    // Thêm trạng thái mới với order_status_id = 11 (Đã nhận hàng)
    OrderOrderStatus::create([
        'order_id' => $order->id,
        'order_status_id' => 5, // Trạng thái "Đã nhận hàng"
        'modified_by' => Auth::id(), // Người dùng hiện tại
        'notes' => $request->input('notes', 'Khách hàng xác nhận đã nhận hàng'),
        'is_current' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    if (!$order->is_paid) {
        $order->update([
            'is_paid' => true,
            
        ]);
    }

    return redirect()->back()->with('success', 'Đã xác nhận nhận hàng thành công.');
}
public function showCancelForm($orderId)
{
    $order = Order::findOrFail($orderId);

    // Kiểm tra quyền sở hữu đơn hàng
    if ($order->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
    }

    // Kiểm tra trạng thái đơn hàng
    $currentStatus = $order->currentStatus->orderStatus->name ?? '';
    if ($currentStatus !== 'Chờ Xác Nhận') {
        return redirect()->back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
    }

    return view('client.orders.cancel-form', compact('order'));
}
public function showCancelForm2($orderId)
{
    $order = Order::findOrFail($orderId);

    // Kiểm tra quyền sở hữu đơn hàng
    if ($order->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
    }

    // Kiểm tra trạng thái đơn hàng
    $currentStatus = $order->currentStatus->orderStatus->name ?? '';
    if ($currentStatus !== 'Chờ Xác Nhận') {
        return redirect()->back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
    }

    return view('client.orders.cancel-online', compact('order'));
}
public function showConfirmRefund(Order $order)
    {




        // Load các quan hệ cần thiết với select tối ưu
        $order->load([
            'user:id,name',
            'currentStatus:order_id,cancel_reason,bank_name,account_name,account_number,phone_number'
        ]);

        return view('admin.orders.confirm-refund', compact('order'));
    }
public function confirmRefund(Request $request, $orderId)
{
    try {

        // 1. Kiểm tra đơn hàng tồn tại
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');

        }

        // 2. Kiểm tra nếu đã hoàn tiền trước đó
        if ($order->check_refund_cancel == 1) {
            return redirect()->back()->with('error', 'Đơn hàng đã được hoàn tiền trước đó');
        }

        // 3. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'evidence_images' => 'required|array|min:1',
            'evidence_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 4. Xử lý upload ảnh
        $uploadedImages = [];
        foreach ($request->file('evidence_images') as $image) {
            $path = $image->store('refund_evidences', 'public');
            $uploadedImages[] = $path;
        }

        // 5. CHỈ CẬP NHẬT 2 TRƯỜNG THEO YÊU CẦU
        $order->img_send_refund_money = json_encode($uploadedImages);
        $order->check_refund_cancel = 1;
        $order->save();

        // 6. Trả về kết quả
        return redirect()
            ->back()
            ->with('success', 'Đã xác nhận hoàn tiền thành công')
            ->with('images', $uploadedImages);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()
            ->back()
            ->withErrors($e->errors())
            ->withInput();

    } catch (\Exception $e) {
        // Ghi log lỗi

        return redirect()
            ->back()
            ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }
}

private function processEvidenceImages($images)
{
    $evidence = [];
    foreach ($images as $image) {
        $path = $image->store('public/refund_evidence');
        $evidence[] = [
            'path' => str_replace('public/', '', $path),
            'uploaded_at' => now()->toDateTimeString(),
            'uploaded_by' => Auth::id(),
        ];
    }
    return $evidence;
}

private function storeEvidenceImages($images)
{
    $paths = [];
    foreach ($images as $image) {
        $path = $image->store('public/refund_evidence');
        $paths[] = str_replace('public/', '', $path);
    }
    return json_encode($paths);
}
// OrderController.php

public function listCancelledOrders(Request $request)
{
    try {
        $orders = Order::whereHas('currentStatus', function($query) {
                $query->where('order_status_id', 6); // ID 6 = Đã hủy
            })
            ->with(['user', 'currentStatus']) // Chỉ load user và currentStatus
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.cancelled', compact('orders'));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Lỗi khi tải danh sách đơn hủy: '.$e->getMessage());
    }
}

}
