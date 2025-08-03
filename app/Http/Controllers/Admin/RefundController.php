<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Notifications\RefundStatusChanged;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu hoàn tiền
     */
    public function index()
    {
        $refunds = Refund::with(['user', 'items', 'order'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.refunds.index', compact('refunds'));
    }

    /**
     * Hiển thị chi tiết yêu cầu hoàn tiền
     */
    public function show(Refund $refund)
    {
        $refund->load(['user', 'items.orderItem.product', 'order']);
        return view('admin.refunds.show', compact('refund'));
    }

    /**
     * Cập nhật trạng thái và thông tin hoàn tiền
     */
    public function update(Request $request, Refund $refund)
    {
        $request->validate([
            'status'               => 'nullable|in:pending,receiving,completed,rejected,failed,cancel',
            'bank_account_status'  => 'nullable|in:unverified,sent,verified',
            'admin_reason'         => 'nullable|string|max:1000',
            'is_send_money'        => 'nullable|boolean'
        ]);

        $isUnchanged =
            ($request->input('status', $refund->status) === $refund->status) &&
            ($request->input('bank_account_status', $refund->bank_account_status) === $refund->bank_account_status) &&
            (trim($request->input('admin_reason', $refund->admin_reason)) === trim($refund->admin_reason)) &&
            ($request->boolean('is_send_money') === $refund->is_send_money);

        if ($isUnchanged) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng thay đổi ít nhất một thông tin trước khi gửi.']);
        }

        DB::beginTransaction();
        try {
            // Nếu có thay đổi trạng thái hoàn đơn
            if ($request->filled('status')) {
                $refund->status = $request->status;
            }

            // Nếu có trạng thái ngân hàng
            if ($request->filled('bank_account_status')) {
                $refund->bank_account_status = $request->bank_account_status;
            }

            // Nếu có lý do từ admin
            $refund->admin_reason = $request->admin_reason;

            // Nếu admin đánh dấu đã chuyển khoản hoàn tiền
            if ($request->boolean('is_send_money')) {
                $refund->is_send_money = true;
                $refund->status = 'completed';
                $refund->updated_at;
            }

            $refund->save();

            // Gửi thông báo cho khách hàng
            $refund->user->notify(new RefundStatusChanged($refund));

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật hoàn tiền thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
