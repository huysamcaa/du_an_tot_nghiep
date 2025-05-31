<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Danh mục gốc 1',
            'slug' => 'danh-muc-goc-1',
            'ordinal' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Danh mục gốc 2',
            'slug' => 'danh-muc-goc-2',
            'ordinal' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => 1,
            'name' => 'Danh mục con 1',
            'slug' => 'danh-muc-con-1',
            'ordinal' => 1,
            'is_active' => true,
        ]);
    }
}