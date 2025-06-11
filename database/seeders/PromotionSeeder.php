<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'title' => 'Giảm giá Mùa Hè 2025',
            'description' => 'Ưu đãi đặc biệt chào mùa hèhè 2025!',
            'discount_percent' => 20,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ]);
    }
}

