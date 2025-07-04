<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike', 'logo' => 'nike.png'],
            ['name' => 'Adidas', 'logo' => 'adidas.png'],
            ['name' => 'Puma', 'logo' => 'puma.png'],
            ['name' => 'Reebok', 'logo' => 'reebok.png'],
            ['name' => 'Under Armour', 'logo' => 'under-armour.png'],
            ['name' => 'New Balance', 'logo' => 'new-balance.png'],
            ['name' => 'Asics', 'logo' => 'asics.png'],
            ['name' => 'Converse', 'logo' => 'converse.png'],
            ['name' => 'Vans', 'logo' => 'vans.png'],
            ['name' => 'Fila', 'logo' => 'fila.png'],
        ];

        foreach ($brands as $brand) {
            Brand::withTrashed()->updateOrCreate(
                ['name' => $brand['name']],
                [
                    'slug' => Str::slug($brand['name']),
                    'logo' => 'brands/' . $brand['logo'],
                    'is_active' => rand(0, 1),
                    'deleted_at' => null, // khôi phục nếu bị xóa mềm
                ]
            );
        }
    }
}
