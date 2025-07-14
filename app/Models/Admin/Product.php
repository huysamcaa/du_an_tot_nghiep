<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Admin\Attribute;
use App\Models\Admin\Category;
use App\Models\Client\Comment;
use App\Models\Admin\Review;
use App\Models\Brand;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'views',
        'short_description',
        'description',
        'stock',
        'thumbnail',
        'type',
        'sku',
        'price',
        'sale_price',
        'sale_price_start_at',
        'sale_price_end_at',
        'is_sale',
        'is_active',
    ];

    protected $casts = [
        'views' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_price_start_at' => 'datetime',
        'sale_price_end_at' => 'datetime',
        'is_sale' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    // === Relationships ===

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'product_id',
            'category_id'
        );
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variantsWithAttributes()
    {
        return $this->variants()
            ->with(['attributeValues.attribute'])
            ->get();
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\Shared\OrderItem::class, 'product_id', 'id');
    }

    // === Helper Methods ===

    public function variantAttributes()
    {
        return Attribute::forVariants()
            ->active()
            ->with('values')
            ->get();
    }

    public function availableVariantValues(string $attributeSlug)
    {
        return $this->attributeValues()
            ->whereHas('attribute', function ($query) use ($attributeSlug) {
                $query->where('slug', $attributeSlug)
                    ->where('is_variant', true);
            })
            ->active()
            ->get();
    }

    public function getVariantByAttributes(array $attributes)
    {
        $query = $this->variants();
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        return $query->first();
    }

    // === Scopes ===

    public function scopeOnSale($query)
    {
        return $query->where('is_sale', true)
            ->where('sale_price_start_at', '<=', now())
            ->where('sale_price_end_at', '>=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
