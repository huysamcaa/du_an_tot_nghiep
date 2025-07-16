<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'is_featured',
        'is_trending',
        'is_active',
    ];

    protected $casts = [
        'views' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_price_start_at' => 'datetime',
        'sale_price_end_at' => 'datetime',
        'is_sale' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Quan hệ với danh mục
     */
//    public function categories()
// {
//     return $this->belongsToMany(Category::class, 'category_product');
// }

    /**
     * Quan hệ với các biến thể sản phẩm
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Quan hệ với các biến thể kèm thuộc tính
     */
    public function variantsWithAttributes()
    {
        return $this->variants()
            ->with(['attributeValues.attribute'])
            ->get();
    }

    /**
     * Quan hệ với thư viện ảnh sản phẩm
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }

    /**
     * Quan hệ với bình luận
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Quan hệ với đánh giá
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }


    /**
     * Lấy các thuộc tính dùng cho biến thể
     */
    public function variantAttributes()
    {
        return Attribute::forVariants()
            ->active()
            ->with('values')
            ->get();
    }

    /**
     * Lấy các giá trị thuộc tính biến thể khả dụng
     */
    public function availableVariantValues(string $attributeSlug)
    {
        return $this->attributeValues()
            ->whereHas('attribute', function($query) use ($attributeSlug) {
                $query->where('slug', $attributeSlug)
                    ->where('is_variant', true);
            })
            ->active()
            ->get();
    }

    /**
     * Tìm biến thể theo các thuộc tính
     */
    public function getVariantByAttributes(array $attributes)
    {
        $query = $this->variants();
        
        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }

    /**
     * Scope sản phẩm đang sale
     */
    public function scopeOnSale($query)
    {
        return $query->where('is_sale', true)
            ->where('sale_price_start_at', '<=', now())
            ->where('sale_price_end_at', '>=', now());
    }

    /**
     * Scope sản phẩm nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope sản phẩm đang hot
     */
    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    /**
     * Scope sản phẩm đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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

}
