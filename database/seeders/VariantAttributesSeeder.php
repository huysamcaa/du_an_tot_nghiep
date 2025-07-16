<?php

namespace Database\Seeders;

use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use Illuminate\Database\Seeder;

class VariantAttributesSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'values' => ['Red', 'Blue', 'Green', 'Black', 'White'],
            ],
            [
                'name' => 'Size',
                'slug' => 'size',
                'values' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            ],
        ];

        foreach ($attributes as $attr) {
            $attribute = Attribute::create([
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'is_variant' => true,
                'is_active' => true,
            ]);

            foreach ($attr['values'] as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'is_active' => true,
                ]);
            }
        }
    }
}
