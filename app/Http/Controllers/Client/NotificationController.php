<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
   public function index()
{
    $notifications = DB::table('notifications as n')
        ->leftJoin('coupons as c', 'c.id', '=', 'n.coupon_id')
        ->where('n.user_id', auth()->id())
        ->where(function ($q) {
            $q->whereNull('n.coupon_id')      // thông báo không gắn coupon
              ->orWhereNull('c.deleted_at');  // hoặc coupon còn hoạt động (chưa soft delete)
        })
        ->orderByDesc('n.created_at')
        ->select('n.*')
        ->get();

    return view('client.notifications.index', compact('notifications'));
}


public function show($id)
{
    $notification = DB::table('notifications')
        ->where('id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$notification) {
        return redirect()->back()->with('warning', 'Thông báo không tồn tại.');
    }

    // Nếu là thông báo gắn coupon, chỉ cho xem khi coupon chưa bị xóa mềm
    if (!empty($notification->coupon_id)) {
        $couponAlive = DB::table('coupons')
            ->where('id', $notification->coupon_id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$couponAlive) {
            return redirect()->back()->with('warning', 'Mã giảm giá không tồn tại hoặc đã bị xóa.');
        }
    }

    return view('client.notifications.show', compact('notification'));
}


    public function markAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['read' => 1]);

        return redirect()->back()->with('success', 'Thông báo đã được đánh dấu là đã đọc.');
    }

    public function destroy($id)
    {
        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()->back()->with(
            $deleted ? 'success' : 'warning',
            $deleted ? 'Thông báo đã được xóa thành công.' : 'Không tìm thấy thông báo.'
        );
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected', []);

        if (!empty($ids)) {
            DB::table('notifications')
                ->whereIn('id', $ids)
                ->where('user_id', auth()->id())
                ->delete();

            return redirect()->back()->with('success', 'Đã xóa các thông báo được chọn.');
        }

        return redirect()->back()->with('warning', 'Bạn chưa chọn thông báo nào để xóa.');
    }

    public function bulkMarkAsRead(Request $request)
    {
        $ids = $request->input('selected', []);

        if (!empty($ids)) {
            DB::table('notifications')
                ->whereIn('id', $ids)
                ->where('user_id', auth()->id())
                ->update(['read' => 1]);

            return redirect()->back()->with('success', 'Đã đánh dấu là đã đọc các thông báo được chọn.');
        }

        return redirect()->back()->with('warning', 'Bạn chưa chọn thông báo nào để đánh dấu đã đọc.');
    }
}
