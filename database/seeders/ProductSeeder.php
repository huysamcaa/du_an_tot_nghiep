<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'brand_id' => 1, // phải có brand trước
                'name' => "Sản phẩm $i",
                'slug' => Str::slug("Sản phẩm $i"),
                'sku' => "SP$i",
                'price' => rand(1000000, 5000000),
                'is_active' => true,
                'views' => rand(0, 1000),
                'thumbnail' => 'default.jpg',
            ]);
        }
    }
}
