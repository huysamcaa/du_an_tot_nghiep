<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Comment;
use App\Models\Admin\AttributeValue;
use App\Http\Controllers\Controller;

// Controller để xử lý chi tiết sản phẩm
class ProductDetailController extends Controller
{
  public function show($id)
{
    $product = Product::findOrFail($id);
    $category = $product->category;
    // Lấy tất cả giá trị thuộc tính theo dạng tách biệt màu - size
    $colors = AttributeValue::where('attribute_id', 1)->where('is_active', 1)->get();
    $sizes = AttributeValue::where('attribute_id', 2)->where('is_active', 1)->get();
    $comments = $product->comments()->with('user')->latest()->get();
    return view('client.productDetal.detal', compact('product','category' , 'comments', 'colors', 'sizes'));
}

}
