<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        DB::table('product_variants')->truncate();  // Xóa tất cả bản ghi trong bảng
        $products = Product::all();

        $colors = AttributeValue::whereHas('attribute', fn($q) => $q->where('slug', 'color'))->get();
        $sizes = AttributeValue::whereHas('attribute', fn($q) => $q->where('slug', 'size'))->get();

        foreach ($products as $product) {
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    $sku = $product->sku . '-' . $color->value . '-' . $size->value;

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price ?? ($product->price - rand(5000, 20000)),
                        'thumbnail' => 'default.jpg',
                        'is_active' => 1
                    ]);

                    $variant->attributeValues()->sync([$color->id, $size->id]);
                }
            }
        }
    }
}

