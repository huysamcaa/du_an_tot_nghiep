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
class ProductDetailController extends Controller
{
public function show($id)
{
    $product = Product::findOrFail($id);
    $category = $product->category;

    // Lấy các attribute_value cho sản phẩm này thông qua bảng trung gian
    $attributeValueIds = DB::table('attribute_value_product')
        ->where('product_id', $product->id)
        ->pluck('attribute_value_id')
        ->toArray();

    // Lấy các màu sắc (attribute_id = 1)
    $colors = AttributeValue::whereIn('id', $attributeValueIds)
                ->where('attribute_id', 1)
                ->where('is_active', 1)
                ->get(['id', 'value', 'hex']);

    // Lấy các kích thước (attribute_id = 2)
    $sizes = AttributeValue::whereIn('id', $attributeValueIds)
                ->where('attribute_id', 2)
                ->where('is_active', 1)
                ->get();

    $comments = $product->comments()
                ->where('is_active', 1)
                ->with('user')
                ->latest()
                ->get();

    return view('client.productDetal.detal', compact('product', 'category', 'comments', 'colors', 'sizes'));
}
}
