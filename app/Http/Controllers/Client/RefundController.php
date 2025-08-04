<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Shared\Order;
use App\Models\Refund;
use App\Models\RefundItem;
use App\Events\RefundCreated;
use App\Events\RefundCancelled;
use App\Notifications\RefundStatusChanged;
use App\Models\Shared\OrderItem;

class RefundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 1. Hiển thị danh sách yêu cầu hoàn tiền của người dùng
    public function index()
    {
        $refunds = Refund::with('order', 'items')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.refunds.index', compact('refunds'));
    }

    // 2. Hiển thị form tạo yêu cầu hoàn tiền
    public function selectItems($orderId)
    {
        $order = Order::with([
            'items.product',
            'items.variant.attributeValues.attribute', // thêm dòng này
        ])
            ->where('user_id', auth()->id())
            ->whereHas('currentStatus.orderStatus', fn($q) => $q->where('name', 'đã hoàn thành'))
            ->findOrFail($orderId);

        return view('client.refunds.select_items', compact('order'));
    }

    // Bước 1 POST: xác nhận items, redirect sang create
    public function confirmItems(Request $request, $orderId)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'regex:/^\d+_\d+$/',
        ]);

        $rawItems = $request->input('items');

        // Lấy danh sách ID sản phẩm thật sự
        $itemIds = array_map(function ($val) {
            return explode('_', $val)[0];
        }, $rawItems);

        $itemsParam = implode('|', $itemIds);

        return redirect()->route('refunds.create', [
            'order_id' => $orderId,
            'items'    => $itemsParam,
        ]);
    }

    // Bước 2 (create): nhận order_id và items list
    public function create($orderId, $items)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $itemIds = explode('|', $items); // ['5', '5', '8']

        // Đếm số lần mỗi item_id xuất hiện
        $itemCounts = array_count_values($itemIds); // ['5' => 2, '8' => 1]

        $selectedItems = collect();

        foreach ($itemCounts as $itemId => $count) {
            $item = OrderItem::with(['variant'])->find($itemId);
            if ($item) {
                for ($i = 0; $i < $count; $i++) {
                    $selectedItems->push($item); // thêm bản sao của item
                }
            }
        }

        return view('client.refunds.create', [
            'order'         => $order,
            'selectedItems' => $selectedItems,
        ]);
    }


    // 3. Lưu yêu cầu hoàn tiền mới
    public function store(Request $request)
    {

        $validated = $request->validate([
            'order_id'         => 'required|exists:orders,id',
            'reason'           => 'required|string|max:500',
            'bank_account'     => 'required|string|max:255',
            'user_bank_name'   => 'required|string|max:255',
            'phone_number'     => 'required|string|max:20',
            'bank_name'        => 'required|string|max:100',
            'reason_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'item_ids'         => 'required|array|min:1',
            'item_ids.*'       => 'exists:order_items,id',
        ]);

        $existing = Refund::where('order_id', $validated['order_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors(['general' => 'Bạn đã gửi yêu cầu hoàn cho đơn hàng này rồi.']);
        }
        $selectedItemIds = $validated['item_ids'];

        $selectedItems = collect();

        foreach ($selectedItemIds as $itemId) {
            $item = OrderItem::with(['product', 'variant'])->find($itemId);
            if ($item) {
                $selectedItems->push($item);
            }
        }

        $totalAmount = $selectedItems->sum(fn($item) => $item->price);
        DB::beginTransaction();
        try {
            $refundData = [
                'user_id'             => auth()->id(),
                'order_id'            => $validated['order_id'],
                'reason'              => $validated['reason'],
                'bank_account'        => $validated['bank_account'],
                'user_bank_name'      => $validated['user_bank_name'],
                'phone_number'        => $validated['phone_number'],
                'bank_name'           => $validated['bank_name'],
                'total_amount'        => $totalAmount,
                'status'              => 'pending',
                'bank_account_status' => 'unverified',
                'is_send_money'       => 0,
            ];

            if ($request->hasFile('reason_image')) {
                $refundData['reason_image'] = $request->file('reason_image')->store('refunds', 'public');
            }

            $refund = Refund::create($refundData);
            $order = Order::find($validated['order_id']);
            if (!$order) {
                Log::error("Không tìm thấy order với ID: " . $validated['order_id']);
            }
            // Nếu người dùng từng gửi hoàn nhưng đã tự hủy, cho phép gửi lại bằng cách reset is_refund_cancel
            if ($order->is_refund == 1 && $order->is_refund_cancel == 1) {
                $order->update([
                    'is_refund_cancel' => 0,
                ]);
            }
            $order->update([
                'is_refund' => 1,
            ]);
            foreach ($selectedItems as $item) {
                RefundItem::create([
                    'refund_id'        => $refund->id,
                    'product_id'       => $item->product_id,
                    'variant_id'       => $item->product_variant_id,
                    'name'             => $item->product->name,
                    'name_variant'     => optional($item->variant)->name,
                    'quantity'         => 1,
                    'price'            => $item->price,
                    'price_variant'    => optional($item->variant)->sale_price ?? 0,
                    'quantity_variant' => 1,
                ]);
            }

            DB::commit();

            event(new RefundCreated($refund));
            auth()->user()->notify(new RefundStatusChanged($refund));

            return redirect()->route('refunds.index')->with('success', 'Gửi yêu cầu hoàn tiền thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Refund store error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.']);
        }
    }

    public function show($id)
    {
        $refund = Refund::with([
            'order',
            'items.product',     // cần để lấy thumbnail, name
            'items.variant'      // cần để lấy tên phân loại
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('client.refunds.show', compact('refund'));
    }

    // 4. Hủy yêu cầu hoàn hàng
    public function cancel($id)
    {
        $refund = Refund::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $refund->update([
                'status'       => 'cancel',
                'admin_reason' => 'Khách tự hủy',
            ]);
            $refund->load('order');
            $refund->order->update([
                'is_refund_cancel' => 1,
            ]);
            DB::commit();

            // event(new RefundCancelled($refund));
            auth()->user()->notify(new RefundStatusChanged($refund));

            return redirect()->back()->with('success', 'Yêu cầu hoàn hàng đã được hủy.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Refund cancel error', ['error' => $e->getMessage()]);
            dd($e);
            return back()->withErrors(['general' => 'Hủy yêu cầu thất bại. Vui lòng thử lại.']);
        }
    }
}
