<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(['Nike','Adidas','Puma'] as $name){
            Manufacturer::create([
                'name'=>$name,
                'slug'=>Str::slug($name),
                'website'=>  'https://'.Str::slug($name).'.com',
                'description'=> fake()->sentence(),
            ]);
        }
    }
}
