<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Notifications\RefundStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu hoàn tiền
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $dateRange = $request->input('date_range');

        $query = Refund::with(['user', 'order']);

        // Tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', fn($order) => $order->where('code', 'LIKE', "%{$search}%"))
                    ->orWhereHas('user', fn($user) => $user->where('name', 'LIKE', "%{$search}%"))
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Lọc trạng thái
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Lọc thời gian và sắp xếp
        switch ($dateRange) {
            case 'last_month':
                $query->where('created_at', '>=', now()->subMonth())->orderByDesc('created_at');
                break;
            case '7_days_ago':
                $query->where('created_at', '>=', now()->subDays(7))->orderByDesc('created_at');
                break;
            case 'newest_first':
                $query->orderByDesc('created_at');
                break;
            case 'oldest_first':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $refunds = $query->paginate($perPage)->withQueryString();

        $statuses = [
            'all' => 'Tất cả trạng thái',
            'pending' => 'Chờ xử lý',
            'receiving' => 'Đang tiếp nhận',
            'completed' => 'Hoàn thành',
            'rejected' => 'Đã từ chối',
            'failed' => 'Thất bại',
            'cancel' => 'Đã hủy',
        ];

        return view('admin.refunds.index', compact('refunds', 'statuses'));
    }

    /**
     * Hiển thị chi tiết yêu cầu hoàn tiền
     */
    public function show(Refund $refund)
    {
        $refund->load(['user', 'items.orderItem.product', 'order']);

        $statusColors = [
            'pending' => 'secondary',
            'receiving' => 'info',
            'completed' => 'success',
            'rejected' => 'danger',
            'failed' => 'warning',
            'cancel' => 'dark',
        ];
        $bankStatusColors = [
            'unverified' => 'secondary',
            'verified' => 'info',
            'sent' => 'success',
        ];
        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'receiving' => 'Đang tiếp nhận',
            'completed' => 'Hoàn thành',
            'rejected' => 'Đã từ chối',
            'failed' => 'Thất bại',
            'cancel' => 'Đã hủy',
        ];
        $bankLabels = [
            'unverified' => 'Chưa xác minh',
            'verified' => 'Đã xác minh',
            'sent' => 'Đã gửi',
        ];

        return view('admin.refunds.show', compact('refund', 'statusColors', 'bankStatusColors', 'statusLabels', 'bankLabels'));
    }

    /**
     * Cập nhật trạng thái và thông tin hoàn tiền
     */
    public function update(Request $request, Refund $refund)
{
    // Xác thực dữ liệu cơ bản
    $rules = [
        'status' => 'required|in:pending,receiving,completed,rejected,failed,cancel',
        'bank_account_status' => $request->status === 'completed'
            ? 'nullable|in:unverified,sent,verified'
            : 'required|in:unverified,sent,verified',
        'admin_reason' => 'nullable|string|max:1000',
        'fail_reason' => 'nullable|string|max:1000',
        'img_fail_or_completed' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:10240',
    ];

    // Điều kiện riêng cho rejected và failed
    if ($request->status === 'rejected') {
        $rules['admin_reason'] = 'required|string|max:1000';
    }
    if ($request->status === 'failed') {
        $rules['fail_reason'] = 'required|string|max:1000';
        $rules['img_fail_or_completed'] = 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:10240';
    }

    $validated = $request->validate($rules);

    // Kiểm tra thay đổi trước khi cập nhật
    $isUnchanged =
        ($request->status === $refund->status) &&
        (
            $request->status === 'completed'
                ? true // completed tự gán bank_account_status, không xét
                : $request->bank_account_status === $refund->bank_account_status
        ) &&
        ($request->admin_reason === $refund->admin_reason) &&
        ($request->fail_reason === $refund->fail_reason) &&
        !$request->hasFile('img_fail_or_completed');

    if ($isUnchanged) {
        return back()->withErrors(['error' => 'Vui lòng thay đổi ít nhất một thông tin trước khi gửi.']);
    }

    DB::beginTransaction();
    try {
        // Cập nhật trạng thái và bank_account_status
        $refund->status = strtolower($validated['status']);
        $refund->bank_account_status = $refund->status === 'completed'
            ? 'sent'
            : $validated['bank_account_status'];

        // Cập nhật reason theo trạng thái
        $refund->admin_reason = $refund->status === 'rejected' ? $validated['admin_reason'] : null;
        $refund->fail_reason = $refund->status === 'failed' ? $validated['fail_reason'] : null;

        // Cập nhật is_send_money nếu có file ảnh/video
        $refund->is_send_money = $request->hasFile('img_fail_or_completed') ? 1 : 0;

        // Xử lý file ảnh/video
        if ($request->hasFile('img_fail_or_completed')) {
            if ($refund->img_fail_or_completed && Storage::disk('public')->exists($refund->img_fail_or_completed)) {
                Storage::disk('public')->delete($refund->img_fail_or_completed);
            }
            $refund->img_fail_or_completed = $request->file('img_fail_or_completed')->store('refund_proofs', 'public');
        } elseif (!in_array($refund->status, ['completed', 'failed'])) {
            if ($refund->img_fail_or_completed && Storage::disk('public')->exists($refund->img_fail_or_completed)) {
                Storage::disk('public')->delete($refund->img_fail_or_completed);
            }
            $refund->img_fail_or_completed = null;
        }

        $refund->save();

        // Cập nhật đơn hàng nếu hoàn thành
        $order = $refund->order;
        if ($order && $refund->status === 'completed' && $refund->is_send_money) {
            $order->is_refund_cancel = true;
            $order->is_refund = true;
            $order->img_send_refund_money = $refund->img_fail_or_completed;
            $order->save();

            $refundedStatusId = 7;

            DB::table('order_order_status')
                ->where('order_id', $order->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);

            DB::table('order_order_status')->insert([
                'order_id' => $order->id,
                'order_status_id' => $refundedStatusId,
                'modified_by' => auth()->user()->id,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Gửi thông báo
        $refund->user->notify(new RefundStatusChanged($refund));

        DB::commit();
        return back()->with('success', 'Cập nhật hoàn tiền thành công.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
    }
}

}
