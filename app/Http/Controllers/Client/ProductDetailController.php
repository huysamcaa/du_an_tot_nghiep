<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Comment;
use App\Models\Admin\CommentReply;
use App\Models\Admin\OrderItem;
use App\Models\Admin\AttributeValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends Controller
{
  public function show($id)
{
    $product = Product::findOrFail($id);
    $category = $product->category;
    // Lấy tất cả giá trị thuộc tính theo dạng tách biệt màu - size
    $colors = AttributeValue::where('attribute_id', 1)->where('is_active', 1)->get();
    $sizes = AttributeValue::where('attribute_id', 2)->where('is_active', 1)->get();
    $comments = $product->comments()->where('is_active', 1)->with('user','replies.user')->latest()->get();
    // Kiểm tra nếu người dùng đã mua sản phẩm này
    $userHasPurchased = false;
    if (Auth::check()) {
        $userHasPurchased = OrderItem::where('product_id', $id)
                                      ->whereHas('order', function ($query) {
                                          $query->where('user_id', Auth::id())
                                                ->whereHas('statuses', function($query) {
                                                        $query->where('id', 2);
                                                    });
                                      })
                                      ->exists();
    }
    return view('client.productDetal.detal', compact('product','category' , 'comments', 'colors', 'sizes', 'userHasPurchased'));
}
  public function addComment(Request $request, $id)
  {
    $userHasPurchased = OrderItem::where('product_id',$id)
                        ->whereHas('order', function($query) {
                            $query->where('user_id', Auth::id())
                                  ->whereHas('statuses', function($query) {
                                                        $query->where('id', 2);
                                                    });
                        })
                        ->exists();
    if(!$userHasPurchased) {
      return redirect()->back()->with('error', 'Bạn cần mua sản phẩm này để bình luận');
    }

    // thêm bl
    $comment = new Comment();
    $comment->product_id = $id;
    $comment->user_id = Auth::id();
    $comment->content = $request->input('comComment');
    $comment->is_active = 1;
    $comment->save();

    return redirect()->back()->with('success', 'Đã bình luận thành công');
  }
  public function addReply(Request $request, $id)
    {
      try {
        // Không kiểm tra $userHasPurchased cho phản hồi
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để trả lời bình luận');
        }
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'content' => 'required|string|max:1000',
        ]);
        // Lấy thông tin bình luận gốc để gán reply_user_id
        $comment = Comment::findOrFail($request->comment_id);
        $replyUserId = $comment->user_id; // ID của người sở hữu bình luận gốc

        // Thêm phản hồi
        $reply = new CommentReply();
        $reply->comment_id = $request->comment_id;
        $reply->user_id = Auth::id();
        $reply->reply_user_id = $replyUserId;
        $reply->content = $request->content;
        $reply->is_active = 1;
        $reply->save();

        return response()->json(['success' => true, 'message' => 'Đã trả lời bình luận thành công', 'data' => ['content' => $request->content, 'user_name' => Auth::user()->name]]);
    } catch (\Exception $e) {
            \Log::error('Error in addReply: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi server. Vui lòng thử lại.'], 500);
    }
    }
    public function updateCommentOrReply(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để sửa bình luận'], 401);
            }

            $request->validate([
                'comment_id' => 'required|integer',
                'content' => 'required|string|max:1000',
            ]);

            $commentId = $request->comment_id;
            $content = $request->content;

            // Kiểm tra xem comment_id thuộc Comment hay CommentReply
            $comment = Comment::find($commentId);
            $reply = CommentReply::find($commentId);

            if ($comment && Auth::id() === $comment->user_id) {
                // Cập nhật bình luận gốc
                $comment->content = $content;
                $comment->save();
                return response()->json(['success' => true, 'message' => 'Bình luận đã được cập nhật']);
            } elseif ($reply && Auth::id() === $reply->user_id) {
                // Cập nhật phản hồi
                $reply->content = $content;
                $reply->save();
                return response()->json(['success' => true, 'message' => 'Phản hồi đã được cập nhật']);
            } else {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền sửa nội dung này'], 403);
            }
        } catch (\Exception $e) {
            \Log::error('Error in updateComment: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi server. Vui lòng thử lại.'], 500);
        }
    }
}
