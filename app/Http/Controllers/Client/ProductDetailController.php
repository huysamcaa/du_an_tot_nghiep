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
use App\Models\Client\Wishlist;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Product::query()
            ->with(['variants.attributeValues.attribute'])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_active', 1);
            }], 'rating')
            ->withCount(['reviews as reviews_count' => function ($q) {
                $q->where('is_active', 1);
            }])
            ->findOrFail($id);


        $category = $product->category;
        // Lấy tất cả giá trị thuộc tính theo dạng tách biệt màu - size
        // Lấy tất cả ID attribute_value của các biến thể
        // $attributeValueIds = DB::table('attribute_value_product')
        // ->where('product_id', $product->id)
        // ->pluck('attribute_value_id');
        $variantIds = $product->variants->pluck('id');

        $attributeValueIds = DB::table('attribute_value_product_variant')
            ->whereIn('product_variant_id', $variantIds)
            ->pluck('attribute_value_id');

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

        $ratingFilter = request()->input('rating');
        $sortOption = request()->input('sort');
        $allReviews = $product->reviews()
            ->where('is_active', 1)
            ->get();

        $reviews = $product->reviews()
            ->with(['user', 'multimedia'])
            ->where('is_active', 1)
            ->when(in_array($ratingFilter, [1, 2, 3, 4, 5]), fn($q) => $q->where('rating', $ratingFilter))
            ->when($sortOption === 'latest', fn($q) => $q->orderByDesc('created_at'))
            ->when($sortOption === 'highest', fn($q) => $q->orderByDesc('rating'))
            ->when($sortOption === 'lowest', fn($q) => $q->orderBy('rating'))
            ->paginate(5) // Có thể đổi số 5 tuỳ ý
            ->withQueryString();
        $relatedProducts = Product::query()
    ->where('category_id', $product->category_id)
    ->where('id', '<>', $product->id)
    ->with(['variants.attributeValues.attribute'])
    ->withAvg(['reviews as avg_rating' => function ($q) {
        $q->where('is_active', 1);
    }], 'rating')
    ->withCount(['reviews as reviews_count' => function ($q) {
        $q->where('is_active', 1);
    }])
    ->orderByDesc('avg_rating')   // 👈 ưu tiên sp được đánh giá cao
    ->orderByDesc('reviews_count')// 👈 rồi tới số lượng đánh giá
    ->take(8)
    ->get();


        $isFavorite = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();
        $hasReviewed = false;
        $myReview = null;

        if (Auth::check()) {
            $myReview = Review::with('multimedia')
                ->where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            $hasReviewed = $myReview !== null;
        }

        return view('client.productDetal.detal', compact('product', 'category', 'comments', 'colors', 'sizes', 'relatedProducts', 'reviews', 'variants', 'allReviews', 'hasReviewed', 'myReview', 'isFavorite'));
    }
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }
}
