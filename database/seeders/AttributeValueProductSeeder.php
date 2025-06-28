<?php

namespace Database\Seeders;

use App\Models\Admin\AttributeValue;
use App\Models\Admin\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValueProductSeeder extends Seeder
{
public function run()
    {
        // Lấy tất cả các sản phẩm
        $products = Product::all();

        // Lấy tất cả giá trị thuộc tính "Color" (Màu sắc)
        $colorValues = AttributeValue::whereHas('attribute', function($query) {
            $query->where('slug', 'color');
        })->get();

        // Lấy tất cả giá trị thuộc tính "Size" (Kích thước)
        $sizeValues = AttributeValue::whereHas('attribute', function($query) {
            $query->where('slug', 'size');
        })->get();

        foreach ($products as $product) {
            // Liên kết sản phẩm với tất cả các màu sắc và kích thước
            foreach ($colorValues as $color) {
                foreach ($sizeValues as $size) {
                    // Kiểm tra nếu sự kết hợp đã tồn tại
                    $existsColor = DB::table('attribute_value_product')
                        ->where('product_id', $product->id)
                        ->where('attribute_value_id', $color->id)
                        ->exists();

                    $existsSize = DB::table('attribute_value_product')
                        ->where('product_id', $product->id)
                        ->where('attribute_value_id', $size->id)
                        ->exists();

                    // Nếu không tồn tại thì mới chèn
                    if (!$existsColor) {
                        DB::table('attribute_value_product')->insert([
                            'product_id' => $product->id,
                            'attribute_value_id' => $color->id,  // Màu sắc
                        ]);
                    }

                    if (!$existsSize) {
                        DB::table('attribute_value_product')->insert([
                            'product_id' => $product->id,
                            'attribute_value_id' => $size->id,  // Kích thước
                        ]);
                    }
                }
            }
        }
    }
}

