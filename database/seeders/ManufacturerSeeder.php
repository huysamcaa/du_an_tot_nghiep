<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('manufacturers')->insert([
            [
                'name' => 'Công ty 1',
                'address' => '789 Lê Lợi, Đà Nẵng',
                'phone' => '0912345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công ty 2',
                'address' => '101 Trần Phú, Nha Trang',
                'phone' => '0908765432',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công ty 3',
                'address' => 'Hà Đông, Hà Nội',
                'phone' => '0900456347',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
