<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

    // 2. Hiển thị form chọn sản phẩm hoàn tiền
    public function selectItems($orderId)
    {
        // Validation đơn giản cho tham số trên URL
        if (!is_numeric($orderId) || $orderId <= 0) {
            abort(404);
        }

        $order = Order::with([
            'items.product',
            'items.variant.attributeValues.attribute',
        ])
            ->where('user_id', auth()->id())
            ->whereHas('currentStatus.orderStatus', fn($q) => $q->where('name', 'đã hoàn thành'))
            ->findOrFail($orderId);

        return view('client.refunds.select_items', compact('order'));
    }

    // Bước 1 POST: xác nhận items, redirect sang create
    public function confirmItems(Request $request, $orderId)
    {
        // Sử dụng Validator::make()
        $validator = Validator::make($request->all(), [
            'items'   => 'required|array|min:1',
            'items.*' => 'regex:/^\d+_\d+$/',
        ], [
            'items.required'  => 'Vui lòng chọn ít nhất một sản phẩm để hoàn tiền.',
            'items.min'       => 'Vui lòng chọn ít nhất một sản phẩm để hoàn tiền.',
            'items.*.regex'   => 'Định dạng sản phẩm không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $rawItems = $validated['items'];

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
        // Validation đơn giản cho tham số trên URL
        if (!is_numeric($orderId) || $orderId <= 0 || !preg_match('/^(\d+\|?)+$/', $items)) {
            abort(404);
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $itemIds = explode('|', $items);
        $itemCounts = array_count_values($itemIds);

        // Lấy tất cả OrderItem cùng lúc để tối ưu
        $selectedItems = OrderItem::whereIn('id', array_keys($itemCounts))
            ->with(['product', 'variant.attributeValues.attribute'])
            ->get();

        // Tạo một collection mới với số lượng item đã chọn
        $finalSelectedItems = collect();
        foreach ($selectedItems as $item) {
            $count = $itemCounts[$item->id] ?? 0;
            for ($i = 0; $i < $count; $i++) {
                $finalSelectedItems->push($item);
            }
        }

        if ($finalSelectedItems->isEmpty() || $finalSelectedItems->count() != count($itemIds)) {
            return redirect()->route('orders.show', $orderId)->withErrors(['general' => 'Một hoặc nhiều sản phẩm đã chọn không hợp lệ hoặc không tồn tại.']);
        }

        return view('client.refunds.create', [
            'order'        => $order,
            'selectedItems' => $finalSelectedItems,
        ]);
    }

    // 3. Lưu yêu cầu hoàn tiền mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'       => 'required|exists:orders,id',
            'reason'         => 'required|string|max:500',
            'bank_account'   => 'required|string|min:8|max:20',
            'user_bank_name' => 'required|string|max:255',
            'phone_number'   => ['required', 'regex:/^0\d{9}$/'],
            'bank_name'      => 'required|string|max:100',
            'reason_image'   => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:10240', // 10MB
            'item_ids'       => 'required|array|min:1',
            'item_ids.*'     => 'exists:order_items,id',
        ], [
            'order_id.required'        => 'Đơn hàng không được để trống.',
            'order_id.exists'          => 'Đơn hàng không tồn tại.',
            'reason.required'          => 'Lý do hoàn tiền không được để trống.',
            'reason.max'               => 'Lý do hoàn tiền không được vượt quá 500 ký tự.',
            'bank_account.required'    => 'Số tài khoản không được để trống.',
            'bank_account.min'         => 'Số tài khoản phải có ít nhất 8 ký tự.',
            'bank_account.max'         => 'Số tài khoản không được vượt quá 20 ký tự.',
            'user_bank_name.required'  => 'Tên chủ tài khoản không được để trống.',
            'phone_number.required'    => 'Số điện thoại không được để trống.',
            'phone_number.regex'       => 'Số điện thoại không hợp lệ. Vui lòng nhập 10 chữ số, bắt đầu bằng 0.',
            'bank_name.required'       => 'Tên ngân hàng không được để trống.',
            'reason_image.max'         => 'Kích thước tệp không được vượt quá 10MB.',
            'reason_image.mimes'       => 'Định dạng tệp không hợp lệ. Vui lòng chọn ảnh (jpeg, png, jpg) hoặc video (mp4, mov, avi).',
            'item_ids.required'        => 'Vui lòng chọn ít nhất một sản phẩm để hoàn tiền.',
            'item_ids.min'             => 'Vui lòng chọn ít nhất một sản phẩm để hoàn tiền.',
            'item_ids.*.exists'        => 'Một hoặc nhiều sản phẩm không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $existing = Refund::where('order_id', $validated['order_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors(['general' => 'Bạn đã gửi yêu cầu hoàn cho đơn hàng này rồi.']);
        }

        $selectedItemIds = $validated['item_ids'];
        $itemCounts = array_count_values($selectedItemIds);
        $selectedItems = OrderItem::whereIn('id', array_keys($itemCounts))
            ->with(['product', 'variant'])
            ->get();

        $totalAmount = $selectedItems->sum(function($item) use ($itemCounts) {
            return $item->price * ($itemCounts[$item->id] ?? 0);
        });

        DB::beginTransaction();
        try {
            $refundData = [
                'user_id'           => auth()->id(),
                'order_id'          => $validated['order_id'],
                'reason'            => $validated['reason'],
                'bank_account'      => $validated['bank_account'],
                'user_bank_name'    => $validated['user_bank_name'],
                'phone_number'      => $validated['phone_number'],
                'bank_name'         => $validated['bank_name'],
                'total_amount'      => $totalAmount,
                'status'            => 'pending',
                'bank_account_status' => 'unverified',
                'is_send_money'     => 0,
            ];

            $refund = Refund::create($refundData);

            if ($request->hasFile('reason_image')) {
                $file = $request->file('reason_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('refunds', $filename, 'public');

                $refund->update([
                    'reason_image' => $path,
                ]);
            }

            $order = Order::find($validated['order_id']);
            if ($order) {
                $order->update(['is_refund' => 1]);
            } else {
                Log::error("Không tìm thấy order với ID: " . $validated['order_id']);
            }

            foreach ($selectedItems as $item) {
                $quantity = $itemCounts[$item->id] ?? 0;
                if ($quantity > 0) {
                    RefundItem::create([
                        'refund_id'          => $refund->id,
                        'product_id'         => $item->product_id,
                        'variant_id'         => $item->product_variant_id,
                        'name'               => $item->name, // Sử dụng tên sản phẩm từ OrderItem
                        'name_variant'       => optional($item->variant)->name ?? 'Không có phân loại',
                        'thumbnail'          => optional($item->variant)->thumbnail ?? 'path/to/default/image.jpg',
                        'quantity'           => $quantity,
                        'price'              => $item->price,
                        'price_variant'      => optional($item->variant)->sale_price ?? 0,
                        'quantity_variant'   => $quantity,
                    ]);
                }
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
        // Validation đơn giản cho tham số trên URL
        if (!is_numeric($id) || $id <= 0) {
            abort(404);
        }

        // Tải các mối quan hệ cần thiết. Tên sản phẩm, biến thể và thumbnail
        // đã được lưu trực tiếp vào bảng refund_items trong phương thức store(),
        // nên không cần phải eager load các model product và variant để lấy thông tin này.
        // Điều này cũng giải quyết lỗi "withTrashed() doesn't exist" nếu các model đó không
        // sử dụng trait SoftDeletes.
        $refund = Refund::with(['order', 'items'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('client.refunds.show', compact('refund'));
    }

    // 4. Hủy yêu cầu hoàn hàng
    public function cancel($id)
    {
        // Validation đơn giản cho tham số trên URL
        if (!is_numeric($id) || $id <= 0) {
            abort(404);
        }

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
            if ($refund->order) {
                $refund->order->update(['is_refund' => 0]);
            }

            DB::commit();

            event(new RefundCancelled($refund));
            auth()->user()->notify(new RefundStatusChanged($refund));

            return redirect()->back()->with('success', 'Yêu cầu hoàn hàng đã được hủy.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Refund cancel error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Hủy yêu cầu thất bại. Vui lòng thử lại.']);
        }
    }
}
