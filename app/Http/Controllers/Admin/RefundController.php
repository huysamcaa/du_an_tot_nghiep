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
        // Lấy các tham số từ request
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Refund::with(['user', 'items', 'order']);

        // Áp dụng bộ lọc tìm kiếm nếu có
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Tìm kiếm theo mã đơn hàng
                $q->whereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery->where('code', 'LIKE', "%{$search}%");
                })
                // Tìm kiếm theo tên khách hàng
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%");
                })
                // Tìm kiếm theo ID của yêu cầu hoàn tiền
                ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        $refunds = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        return view('admin.refunds.index', compact('refunds'));
    }

    /**
     * Hiển thị chi tiết yêu cầu hoàn tiền
     */
    public function show(Refund $refund)
    {
        $refund->load(['user', 'items.orderItem.product', 'order']);

        // Các màu trạng thái
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

        // Nhãn hiển thị
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

        // Khởi tạo các quy tắc xác thực cơ bản
        $rules = [
            'status'                => 'required|in:pending,receiving,completed,rejected,failed,cancel',
            'bank_account_status'   => 'required|in:unverified,sent,verified',
            'is_send_money'         => 'nullable|boolean',
            'admin_reason'          => 'nullable|string|max:1000',
            'fail_reason'           => 'nullable|string|max:1000',
            'img_fail_or_completed' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:10240', // Cho phép cả ảnh và video, 10MB
        ];

        // Ghi lại trạng thái cũ để kiểm tra thay đổi
        $oldStatus = $refund->status;
        $oldBankStatus = $refund->bank_account_status;

        // Thêm các quy tắc xác thực có điều kiện dựa trên trạng thái mới
        // Logic PHP:
        // 1. Khi status là 'rejected' thì 'admin_reason' là bắt buộc
        if ($request->input('status') === 'rejected') {
            $rules['admin_reason'] = 'required|string|max:1000';
        }

        // 2. Khi status là 'failed' thì 'fail_reason' và 'img_fail_or_completed' là bắt buộc
        if ($request->input('status') === 'failed') {
            $rules['fail_reason'] = 'required|string|max:1000';
            $rules['img_fail_or_completed'] = 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:10240';
        }

        // 3. Khi 'is_send_money' được tích thì 'img_fail_or_completed' là bắt buộc và status phải là 'completed'
        if ($request->boolean('is_send_money')) {
            $rules['img_fail_or_completed'] = 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:10240';
            $rules['status'] = 'required|in:completed'; // Bắt buộc status phải là completed
            $rules['bank_account_status'] = 'required|in:sent'; // Bắt buộc bank status phải là sent
        }


        $validatedData = $request->validate($rules);

        // Kiểm tra nếu không có thay đổi nào được thực hiện
        $isUnchanged =
            ($request->input('status') === $oldStatus) &&
            ($request->input('bank_account_status') === $oldBankStatus) &&
            ($request->input('admin_reason') === $refund->admin_reason) &&
            ($request->input('fail_reason') === $refund->fail_reason) &&
            !$request->hasFile('img_fail_or_completed') &&
            ($request->boolean('is_send_money') == $refund->is_send_money);

        if ($isUnchanged) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng thay đổi ít nhất một thông tin trước khi gửi.']);
        }

        DB::beginTransaction();
        try {
            // Cập nhật các trường
            $refund->status = strtolower($validatedData['status']);
            $refund->bank_account_status = $validatedData['bank_account_status'];

            // Xử lý các trường `reason` dựa trên trạng thái mới
            // Chỉ lưu `admin_reason` khi status là 'rejected'
            if ($refund->status === 'rejected') {
                $refund->admin_reason = $validatedData['admin_reason'];
            } else {
                $refund->admin_reason = null; // Xóa lý do nếu trạng thái thay đổi
            }

            // Chỉ lưu `fail_reason` khi status là 'failed'
            if ($refund->status === 'failed') {
                $refund->fail_reason = $validatedData['fail_reason'];
            } else {
                $refund->fail_reason = null; // Xóa lý do nếu trạng thái thay đổi
            }

            // Cập nhật trường `is_send_money`
            $refund->is_send_money = $request->boolean('is_send_money');

            // Xử lý file ảnh/video
            if ($request->hasFile('img_fail_or_completed')) {
                // Xóa file cũ nếu có
                if ($refund->img_fail_or_completed && Storage::disk('public')->exists($refund->img_fail_or_completed)) {
                    Storage::disk('public')->delete($refund->img_fail_or_completed);
                }
                $path = $request->file('img_fail_or_completed')->store('refund_proofs', 'public');
                $refund->img_fail_or_completed = $path;
            } elseif ($refund->status !== 'completed' && $refund->status !== 'failed') {
                 // Nếu trạng thái không yêu cầu file, xóa file nếu tồn tại
                 if ($refund->img_fail_or_completed && Storage::disk('public')->exists($refund->img_fail_or_completed)) {
                    Storage::disk('public')->delete($refund->img_fail_or_completed);
                }
                $refund->img_fail_or_completed = null;
            }

            $refund->save();

            // Gửi thông báo cho người dùng
            $refund->user->notify(new RefundStatusChanged($refund));

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật hoàn tiền thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
        }
    }
}
