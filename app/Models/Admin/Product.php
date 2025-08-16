<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Client\Comment;
use App\Models\Admin\Review;
use App\Models\Brand;
use App\Models\Client\Wishlist;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'views',
        'short_description',
        'description',
        'thumbnail',
        'type',
        'is_active',
    ];

    protected $casts = [
        'views' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    // Quan hệ với danh mục (nhiều-nhiều)
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'product_id',
            'category_id'
        );
    }

    // Quan hệ với thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    // Quan hệ với các biến thể sản phẩm
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Quan hệ với các biến thể kèm thuộc tính
    public function variantsWithAttributes()
    {
        return $this->variants()
            ->with(['attributeValues.attribute'])
            ->get();
    }

    // Quan hệ với thư viện ảnh sản phẩm
    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }

    // Quan hệ với bình luận
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Quan hệ với đánh giá
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Quan hệ với order items
    public function orderItems()
    {
        return $this->hasMany(\App\Models\Shared\OrderItem::class, 'product_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    // Quan hệ với cart items
    public function cartItems()
    {
        return $this->hasMany(\App\Models\Admin\CartItem::class, 'product_id');
    }

    // Quan hệ với wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    // Helper: Lấy các thuộc tính dùng cho biến thể
    public function variantAttributes()
    {
        return Attribute::forVariants()
            ->active()
            ->with('values')
            ->get();
    }


    // Helper: Lấy các giá trị thuộc tính biến thể khả dụng
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

    // Helper: Tìm biến thể theo các thuộc tính
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
    // Thêm vào model Product
// Thêm vào model Product
public function getOrderStatusStats()
{
    return $this->orderItems()
        ->selectRaw('
            order_statuses.name as status_name,
            COUNT(DISTINCT order_items.order_id) as order_count,
            SUM(order_items.quantity) as total_quantity,
            SUM(order_items.price * order_items.quantity) as total_amount
        ')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('order_order_status', function($join) {
            $join->on('orders.id', '=', 'order_order_status.order_id')
                 ->where('order_order_status.is_current', true);
        })
        ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
        ->groupBy('order_statuses.name')
        ->get()
        ->mapWithKeys(function ($item) {
            return [
                $item->status_name => [
                    'order_count' => $item->order_count,
                    'total_quantity' => $item->total_quantity,
                    'total_amount' => $item->total_amount,
                    'status_label' => $this->getStatusLabel($item->status_name)
                ]
            ];
        });
}

protected function getStatusLabel($status)
{
    $labels = [
        '1' => 'Chờ xử lý',
        '2' => 'Đang xử lý',
        '3' => 'Đã giao hàng',
        '4' => 'Hoàn thành',
        '5' => 'Đã hủy',
        '6' => 'Trả hàng'
    ];

    return $labels[strtolower($status)] ?? $status;
}

}
