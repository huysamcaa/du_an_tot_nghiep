<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Comment;
use App\Models\Admin\CommentReply;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $comments = Comment::with('user', 'product')
                ->when($keyword, function($query, $keyword){
                    $query->where(function($q) use ($keyword){
                        $q->whereHas('product', function($q2) use ($keyword){
                            $q2->where('name', 'like' ,"%$keyword%");
                        })
                        ->orWhereHas('user', function($q3) use ($keyword){
                            $q3->where('name', 'like',"%$keyword%");
                        })
                        ->orWhere('content','like',"%$keyword%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

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

    public function indexReplies(Request $request)
    {
        $keyword = $request->input('keyword');
        $replies = CommentReply::with('comment.product', 'user', 'replyUser')
                ->when($keyword, function($query, $keyword){
                    $query->where(function($q) use ($keyword){
                        $q->whereHas('comment.product', function($q2) use ($keyword){
                            $q2->where('name', 'like' ,"%$keyword%");
                        })
                        ->orWhereHas('user', function($q3) use ($keyword){
                            $q3->where('name', 'like',"%$keyword%");
                        })
                        ->orWhere('content','like',"%$keyword%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);;
        return view('admin.comment.reply', compact('replies'));
    }
}
