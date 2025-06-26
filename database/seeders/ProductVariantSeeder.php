<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả các sản phẩm
        $products = Product::all();

        // Lấy tất cả giá trị thuộc tính "Color" (Màu sắc)
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];

        // Lấy tất cả giá trị thuộc tính "Size" (Kích thước)
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        // Duyệt qua tất cả các sản phẩm và tạo các biến thể cho mỗi kết hợp màu sắc và kích thước
        foreach ($products as $product) {
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    // Tạo biến thể cho mỗi kết hợp màu sắc và kích thước
                    $sku = $product->sku . '-' . $color . '-' . $size;

                    DB::table('product_variants')->insert([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'price' => $product->price,
                        'sale_price' => $product->price - rand(5000, 20000),  // Giảm giá ngẫu nhiên từ 5k đến 20k
                        'thumbnail' => 'default.jpg',
                        'is_active' => 1,
                    ]);
                }
            }
        }
    }
}

