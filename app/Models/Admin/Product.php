<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'views',
        'short_description',
        'description',
        'thumbnail',
        'type',
        'sku',
        'price',
        'sale_price',
        'sale_price_start_at',
        'sale_price_end_at',
        'is_sale',
        'is_featured',
        'is_trending',
        'is_active',
    ];

    protected $casts = [
        'views' => 'integer',
        'price' => 'float',
        'sale_price' => 'float',
        'sale_price_start_at' => 'datetime',
        'sale_price_end_at' => 'datetime',
        'is_sale' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function variantAttributes()
{
    return Attribute::forVariants()->active()->with('values')->get();
}

public function availableVariantValues($attributeSlug)
{
    return $this->attributeValues()
        ->whereHas('attribute', function($query) use ($attributeSlug) {
            $query->where('slug', $attributeSlug)
                  ->where('is_variant', true);
        })
        ->active()
        ->get();
}

public function getVariantByAttributes($attributes)
{
    // Ví dụ: tìm biến thể theo color_id và size_id
    return $this->variants()
        ->where('color_id', $attributes['color_id'] ?? null)
        ->where('size_id', $attributes['size_id'] ?? null)
        ->first();
}
 public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
public function variantsWithAttributes()
{
    return $this->variants()
        ->with(['attributeValues.attribute'])
        ->get();
}
public function galleries()
{
    return $this->hasMany(ProductGallery::class);
}


}
