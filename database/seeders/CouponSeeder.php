<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\CouponRestriction;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'title' => 'Giảm 10% cho khách hàng mới',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'is_active' => true,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(30),
                'restriction' => [
                    'min_order_value' => 100000,
                    'max_discount_value' => 50000,
                    'valid_categories' => json_encode([1, 2]),
                    'valid_products' => json_encode([]),
                ]
            ],
            [
                'code' => 'FIXED50K',
                'title' => 'Giảm 50.000đ cho đơn từ 300k',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'restriction' => [
                    'min_order_value' => 300000,
                    'max_discount_value' => null,
                    'valid_categories' => json_encode([]),
                    'valid_products' => json_encode([5, 6, 7]),
                ]
            ],
        ];

        foreach ($coupons as $data) {
            $coupon = Coupon::create([
                'code' => $data['code'],
                'title' => $data['title'],
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'is_active' => $data['is_active'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            if (!empty($data['restriction'])) {
                $coupon->restriction()->create($data['restriction']);
            }
        }
    }
}
