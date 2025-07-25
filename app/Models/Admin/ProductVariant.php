<?php
// app/Models/ProductVariant.php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
     protected $fillable = [
        'stock',
        'price',
        'thumbnail',
    ];


    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant');
    }

    public function getColorAttribute()
    {
        return $this->attributeValues->first(function ($value) {
            return $value->attribute->slug === 'color';
        });
    }

    public function getSizeAttribute()
    {
        return $this->attributeValues->first(function ($value) {
            return $value->attribute->slug === 'size';
        });
    }

    public function getVariantNameAttribute()
    {
        $color = $this->color;
        $size = $this->size;
        
        $parts = [];
        if ($color) $parts[] = $color->value;
        if ($size) $parts[] = $size->value;
        
        return implode(' / ', $parts);
    }
}