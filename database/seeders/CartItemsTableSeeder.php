<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\CartItem;

class CartItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i<10; $i++){
            CartItem::create([
                'user_id' => rand(1,10),
                'product_id' => rand(1,20),
                'product_variant_id' => null,
                'quantity' => rand(1,5),
            ]);
        }
    }
}
