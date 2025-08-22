<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'icon',
        'name',
        'slug',
        'ordinal',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordinal' => 'integer'
    ];

    /**
     * Một danh mục thuộc về một danh mục cha.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Một danh mục có thể có nhiều danh mục con.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Mối quan hệ HasMany cho sản phẩm được gán trực tiếp (theo category_id).
     */
    public function directProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Mối quan hệ BelongsToMany cho sản phẩm liên quan (qua bảng trung gian).
     */
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    /**
     * Kiểm tra xem danh mục có bất kỳ sản phẩm nào được gán trực tiếp hay không.
     * Sử dụng cho logic trong Controller để xác định danh mục cha hợp lệ.
     */
    public function hasDirectProducts(): bool
    {
        return $this->directProducts()->exists();
    }

    /**
     * Kiểm tra xem danh mục có bất kỳ danh mục con nào không.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Kiểm tra xem danh mục có thể được xóa mềm không.
     */
    public function canBeSoftDeleted(): bool
    {
        return !$this->hasDirectProducts() && !$this->hasChildren();
    }

    /**
     * Lấy tất cả các sản phẩm, bao gồm cả sản phẩm trực tiếp và sản phẩm liên quan.
     * Đây là phương thức bạn nên sử dụng để hiển thị tất cả sản phẩm của danh mục.
     */
    public function getAllProductsAttribute()
    {
        // Sử dụng eager loading để tránh N+1 query
        $direct = $this->directProducts;
        $related = $this->relatedProducts;

        // Kết hợp và loại bỏ trùng lặp
        return $direct->merge($related)->unique('id');
    }

    // Giữ nguyên các phương thức còn lại nếu bạn đang sử dụng chúng
    // ... updateStatus, transferProductsTo, transferChildrenTo ...

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });

        // Event này sẽ được kích hoạt khi gọi $category->delete()
        static::deleting(function ($model) {
            if (!$model->isForceDeleting() && !$model->canBeSoftDeleted()) {
                // Ném ngoại lệ để ngăn xóa mềm nếu có sản phẩm hoặc danh mục con
                throw new \Exception('Không thể xóa mềm danh mục khi còn sản phẩm trực tiếp hoặc danh mục con.');
            }
        });
    }
}
