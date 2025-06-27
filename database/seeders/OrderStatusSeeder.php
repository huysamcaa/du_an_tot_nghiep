<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Đang xử lý'],         
            ['name' => 'Đã hoàn thành'],       
            ['name' => 'Đã hủy'],       
            ['name' => 'Đã hoàn tiền'],         
            ['name' => 'Đang giao hàng'],      
            ['name' => 'Đang chờ'],          
        ];
        DB::table('order_statuses')->insert($statuses);
    }
}
