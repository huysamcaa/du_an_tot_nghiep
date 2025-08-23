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
            ->with(['variants' => fn($q) => $q->whereNull('deleted_at')
                                                ->with('attributeValues.attribute')])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_active', 1);
            }], 'rating')
            ->withCount(['reviews as reviews_count' => function ($q) {
                $q->where('is_active', 1);
            }])
            ->whereNull('deleted_at')
            ->findOrFail($id);

        $category = $product->category;

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
            $isSaleActive = $variant->is_sale == 1 &&
                            $variant->sale_price &&
                            $variant->sale_price_start_at &&
                            $variant->sale_price_end_at &&
                            now()->between($variant->sale_price_start_at, $variant->sale_price_end_at);

            $currentPrice = $isSaleActive ? $variant->sale_price : $variant->price;

            $color = $variant->attributeValues->firstWhere('attribute.slug', 'color');
            $size = $variant->attributeValues->firstWhere('attribute.slug', 'size');

            return [
                'id' => $variant->id,
                'color_id' => $color?->id,
                'size_id' => $size?->id,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'stock' => $variant->stock,
                'thumbnail' => $variant->thumbnail ? asset('storage/' . $variant->thumbnail) : asset('storage/' . $product->thumbnail),
                'current_price' => $currentPrice,
                'is_sale_active' => $isSaleActive,
            ];
        });

        // Tính toán giá min/max dựa trên giá hiện tại
        $minPrice = $variants->min('current_price');
        $maxPrice = $variants->max('current_price');

        // Tính toán giá gốc min/max
        $minOriginalPrice = $variants->min('price');
        $maxOriginalPrice = $variants->max('price');

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
            ->paginate(5)
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
            ->orderByDesc('avg_rating')
            ->orderByDesc('reviews_count')
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

        return view('client.productDetal.detal', compact(
            'product',
            'category',
            'comments',
            'colors',
            'sizes',
            'relatedProducts',
            'reviews',
            'variants',
            'allReviews',
            'hasReviewed',
            'myReview',
            'isFavorite',
            'minPrice',
            'maxPrice',
            'minOriginalPrice',
            'maxOriginalPrice'
        ));
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }
}
