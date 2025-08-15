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
        $perPage = $request->get('per_page', 10);

        $query = Comment::query();

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('content', 'like', '%'.$request->keyword.'%')
                ->orWhereHas('product', function($p) use ($request) {
                    $p->where('name', 'like', '%'.$request->keyword.'%');
                })
                ->orWhereHas('user', function($u) use ($request) {
                    $u->where('name', 'like', '%'.$request->keyword.'%');
                })
                ->orderBy('created_at', 'desc');
            });
        }
        $query->orderBy('created_at', 'desc');
        $comments = $query->paginate($perPage);
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
        $perPage = $request->get('per_page', 10);

        $query = CommentReply::query();

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('content', 'like', '%'.$request->keyword.'%')
                ->orWhereHas('product', function($p) use ($request) {
                    $p->where('name', 'like', '%'.$request->keyword.'%');
                })
                ->orWhereHas('user', function($u) use ($request) {
                    $u->where('name', 'like', '%'.$request->keyword.'%');
                })
                ->orderBy('created_at', 'desc');
            });
        }
        $query->orderBy('created_at', 'desc');
        $replies = $query->paginate($perPage);
        return view('admin.comment.reply', compact('replies'));
    }
}
