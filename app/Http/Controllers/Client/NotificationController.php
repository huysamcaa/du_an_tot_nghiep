<?php
namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('client.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['read' => 1]);

        return redirect()->back()->with('success', 'Thông báo đã được đánh dấu là đã đọc.');
    }

    public function show($id)
    {
        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        abort_if(!$notification, 404);

        return view('client.notifications.show', compact('notification'));
    }
}
