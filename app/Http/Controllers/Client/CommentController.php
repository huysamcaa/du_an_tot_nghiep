<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Comment;
use App\Models\Client\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string|min:10|max:300',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để bình luận'], 401);
        }

        $userId = Auth::id();
        $productId = $request->product_id;
        $cacheKey = "comment_flood_{$userId}_{$productId}";

        if (Cache::has($cacheKey)) {
            return response()->json(['message' => 'Bạn đang gửi bình luận quá nhanh, vui lòng đợi 30 giây'], 429);
        }

        $lastComment = Comment::where('user_id', $userId)
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->first();

        if ($lastComment && $lastComment->content === $request->content) {
            return response()->json(['message' => 'Nội dung bình luận bị trùng'], 422);
        }

        Comment::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        Cache::put($cacheKey, true, 30);

        return response()->json(['message' => 'Bình luận đã được gửi']);
    }

     public function reply(Request $request)
{
    $request->validate([
        'comment_id' => 'required|exists:comments,id',
        'content' => 'required|string|min:10|max:300',
    ]);

    if (!Auth::check()) {
        return response()->json(['message' => 'Bạn cần đăng nhập để trả lời'], 401);
    }

    $user = Auth::user();
    $comment = Comment::findOrFail($request->comment_id);

    // Chỉ cho phép admin hoặc chính chủ comment được trả lời
    if ($user->role !== 'admin' && $comment->user_id !== $user->id) {
        return response()->json(['message' => 'Bạn không có quyền trả lời bình luận này'], 403);
    }

    CommentReply::create([
        'comment_id' => $request->comment_id,
        'user_id' => $user->id,
        'reply_user_id' => $request->reply_user_id ?? null,
        'content' => $request->content,
    ]);

    return response()->json(['message' => 'Đã trả lời bình luận']);
}

    public function list(Request $request)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $request->product_id)
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('partials.comment_list', compact('comments'))->render();
    }
}
