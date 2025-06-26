<?php

namespace Database\Seeders;

use App\Models\Admin\ProductVariant;
use App\Models\Admin\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValueProductVariantSeeder extends Seeder
{
    public function run()
    {
        // Xóa tất cả các bản ghi cũ trong bảng `attribute_value_product_variant` để không bị trùng lặp
        DB::table('attribute_value_product_variant')->truncate();  // Xóa tất cả bản ghi trong bảng
        
        // Lấy tất cả các biến thể sản phẩm
        $productVariants = ProductVariant::all();

        // Lấy tất cả giá trị thuộc tính "Color" (màu sắc) và "Size" (kích thước)
        $colorValues = AttributeValue::whereHas('attribute', function($query) {
            $query->where('slug', 'color');
        })->get();

        $sizeValues = AttributeValue::whereHas('attribute', function($query) {
            $query->where('slug', 'size');
        })->get();

        // Duyệt qua các biến thể sản phẩm theo mẫu SKU (Do-XS, Do-S, Do-M, ...)
        foreach ($productVariants as $variant) {
            // Phân tích SKU để lấy màu sắc và kích thước
            $skuParts = explode('-', $variant->sku);
            $colorName = $skuParts[1]; // Lấy màu sắc (Do, Blue, Green, ...)
            $sizeName = $skuParts[2];  // Lấy kích thước (XS, S, M, ...)

            // Tìm attribute_value_id cho màu sắc
            $color = AttributeValue::whereHas('attribute', function($query) {
                $query->where('slug', 'color');
            })->where('value', $colorName)->first();

            // Tìm attribute_value_id cho kích thước
            $size = AttributeValue::whereHas('attribute', function($query) {
                $query->where('slug', 'size');
            })->where('value', $sizeName)->first();

            // Chèn vào bảng `attribute_value_product_variant`
            if ($color && $size) {
                DB::table('attribute_value_product_variant')->insert([
                    'product_variant_id' => $variant->id,
                    'attribute_value_id' => $color->id,  // Màu sắc
                ]);

                DB::table('attribute_value_product_variant')->insert([
                    'product_variant_id' => $variant->id,
                    'attribute_value_id' => $size->id,  // Kích thước
                ]);
            }
        }
    }
}



