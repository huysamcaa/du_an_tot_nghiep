<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Admin\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::create([
            'product_id' => 30,
            'user_id' => 3,
            'order_id' => 17,
            'rating' => 5,
            'review_text' => 'Sản phẩm rất tốt, giao nhanh, sẽ mua lại!',
            'is_active' => 1,
        ]);

        Review::create([
            'product_id' => 30,
            'user_id' => 5,
            'order_id' => 23,
            'rating' => 4,
            'review_text' => 'Hàng đẹp, giống mô tả nhưng giao hơi chậm.',
            'is_active' => 1,
        ]);

        Review::create([
            'product_id' => 30,
            'user_id' => 3,
            'order_id' => 28,
            'rating' => 3,
            'review_text' => 'Chất lượng tạm ổn, đóng gói chưa kỹ.',
            'is_active' => 1,
        ]);
    }
}
