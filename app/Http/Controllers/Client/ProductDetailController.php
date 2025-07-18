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
    $product = Product::with('variants')->findOrFail($id);

    $category = $product->category;
    // Lấy tất cả giá trị thuộc tính theo dạng tách biệt màu - size
    // Lấy tất cả ID attribute_value của các biến thể
    $attributeValueIds = DB::table('attribute_value_product')
    ->where('product_id', $product->id)
    ->pluck('attribute_value_id');
//   $variantIds = $product->variants->pluck('id');

// $attributeValueIds = DB::table('attribute_value_product_variant')
//     ->whereIn('product_variant_id', $variantIds)
//     ->pluck('attribute_value_id');


    $colors = AttributeValue::whereIn('id', $attributeValueIds)
        ->where('attribute_id', 1)
        ->where('is_active', 1)
        ->get();
    
    $sizes = AttributeValue::whereIn('id', $attributeValueIds)
        ->where('attribute_id', 2)
        ->where('is_active', 1)
        ->get();

    $comments = $product->comments()->where('is_active', 1)->with('user')->latest()->get();
    // Lấy tất cả variant và chỉ lấy những gì cần thiết
    $variants = $product->variants->map(function ($variant) use ($product) {
        // Lấy ra tất cả ID các attribute_value liên quan đến biến thể của sản phẩm
        $color = $variant->attributeValues->firstWhere('attribute.slug', 'color');
        $size = $variant->attributeValues->firstWhere('attribute.slug', 'size');
        
        return [
            'id' => $variant->id,
            'color_id' => $color?->id, // $color ? $color->id : null
            'size_id' => $size?->id,
            'price' => $variant->price,
            'sale_price' => $variant->sale_price,
            'stock' => $variant->stock,
        ];
    });

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
    return view('client.productDetal.detal', compact('product','category' , 'comments', 'colors', 'sizes' , 'relatedProducts','reviews','variants'));

}
public function attributeValues()
{
    return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
}
}