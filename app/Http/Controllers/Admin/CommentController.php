<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Comment;
use App\Models\Admin\CommentReply;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return view('admin.comment.index',compact('comments'));
    }
public function toggleComment($id)
{
    $comment = Comment::findOrFail($id);
    $comment->is_active = !$comment->is_active;
    $comment->save();
    return redirect()->route('admin.comments.index')->with('success', 'Trạng thái bình luận đã thay đổi');
}

public function toggleReply($id)
{
    $reply = CommentReply::findOrFail($id);
    $reply->is_active = !$reply->is_active;
    $reply->save();
    return redirect()->route('admin.replies.index')->with('success', 'Trạng thái phản hồi đã thay đổi');
}

    public function indexReplies()
    {
        $replies = CommentReply::with('comment.product', 'user', 'replyUser')->get();
        return view('admin.comment.reply', compact('replies'));
    }
}
