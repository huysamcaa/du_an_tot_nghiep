<?php
namespace App\Http\Controllers\Client;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    // Hiển thị tất cả thông báo của người dùng
public function index()
{
    // Lấy tất cả thông báo của người dùng
    $notifications = auth()->user()->notifications;

    // Đếm số lượng thông báo chưa đọc
    $unreadNotificationsCount = $notifications->where('read', 0)->count();

    // Truyền vào view
    return view('client.notifications.index', compact('notifications', 'unreadNotificationsCount'));
}


    // Đánh dấu thông báo là đã đọc
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = 1; // Đánh dấu là đã đọc
        $notification->save();

        return redirect()->route('client.notifications.index')->with('success', 'Thông báo đã được đánh dấu là đã đọc');
    }
     public function show($id)
    {
        $notification = Notification::findOrFail($id);

        // Trả về view chi tiết thông báo
        return view('client.notifications.show', compact('notification'));
    }
}
