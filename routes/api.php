<?php
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Routing\Route;

// routes/api.php
Route::post('/products/{product}/variant', function (Request $request, Product $product) {
    $variant = $product->getVariantByAttributes($request->attributes);
    
    if (!$variant) {
        return response()->json(['error' => 'Variant not found'], 404);
    }
    
    return response()->json([
        'variant' => [
            'id' => $variant->id,
            'price' => $variant->final_price,
            'price_formatted' => number_format($variant->final_price) . 'đ',
            'thumbnail' => $variant->thumbnail ? asset('storage/'.$variant->thumbnail) : null
        ]
    ]);
});