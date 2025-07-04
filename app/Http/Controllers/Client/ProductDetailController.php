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
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Review;
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
     $reviews = $product->reviews()
        ->with(['user', 'multimedia'])
        ->where('is_active', 1)
        ->latest()
        ->get();
    $relatedProducts = Product::with('variants')

   ->withCount('comments')   // đếm comments thay vì reviews
        ->where('category_id', $product->category_id)
        ->where('id', '<>', $product->id)
        ->take(8)
        ->get();
    return view('client.productDetal.detal', compact('product','category' , 'comments', 'colors', 'sizes' , 'relatedProducts','reviews'));

}
}
