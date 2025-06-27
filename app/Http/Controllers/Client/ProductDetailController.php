<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Comment;
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
    $comments = $product->comments()->where('is_active', 1)->with('user')->latest()->get();
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
    $comment->content = $request->comComment;
    $comment->is_active = 1;
    $comment->save();

    return redirect()->back()->with('success', 'Đã bình luận thành công');
  }
}
