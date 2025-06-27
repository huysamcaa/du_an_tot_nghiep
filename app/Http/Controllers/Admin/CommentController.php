<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Comment;
use App\Models\Admin\CommentReply;
use App\Models\Admin\Product;
use App\Models\User;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return view('admin.comment.index',compact('comments'));
    }
    public function toggleVisibility($id)
    {
        // Kiểm tra xem ID thuộc Comment hay CommentReply
        $comment = Comment::find($id);
        $reply = CommentReply::find($id);

        // Thay đổi giá trị của is_active
        if($comment){
            $comment->is_active = !$comment->is_active; // Chuyển từ 1 thành 0 hoặc ngược lại
            $comment->save();
        }elseif ($reply) {
            $reply->is_active = !$reply->is_active;
            $reply->save();
            $message = 'Trạng thái phản hồi đã thay đổi';
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy nội dung để thay đổi trạng thái');
        }

        return redirect()->route('admin.comments.index')->with('success', 'Trạng thái bình luận đã thay đổi');
    }
    public function indexReplies()
    {
        $replies = CommentReply::with('comment.product', 'user', 'replyUser')->get();
        return view('admin.comment.reply', compact('replies'));
    }
}
