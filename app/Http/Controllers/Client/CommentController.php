<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Comment;
use App\Models\Client\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string|min:3',
        ]);

        Comment::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id() ?? 1,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Bình luận đã được gửi']);
    }

    public function reply(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'content' => 'required|string|min:3',
        ]);

        CommentReply::create([
            'comment_id' => $request->comment_id,
            'user_id' => Auth::id() ?? 1,
            'reply_user_id' => $request->reply_user_id ?? null,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Đã trả lời bình luận']);
    }

    public function list(Request $request)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $request->product_id)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('partials.comment_list', compact('comments'))->render();
    }
}

