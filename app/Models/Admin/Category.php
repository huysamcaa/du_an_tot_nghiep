<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function directProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_id',
            'product_id'
        );
    }




    public function getAllProductsAttribute()
    {
        $direct = $this->directProducts()->get();
        $related = $this->relatedProducts()->get();
        return $direct->merge($related)->unique('id');
    }

    public function hasProducts(): bool
    {
        return $this->directProducts()->exists() ||
            DB::table('category_product')
            ->where('category_id', $this->id)
            ->exists();
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function canBeDeleted(): bool
    {
        return !$this->hasProducts() && !$this->hasChildren();
    }

    public function updateStatus(bool $status): bool
    {
        $this->update(['is_active' => $status]);

        if (!$status && $this->hasChildren()) {
            $this->children()->update(['is_active' => $status]);
        }

        return true;
    }

    public function transferProductsTo(int $newCategoryId): void
    {
        DB::transaction(function () use ($newCategoryId) {
            // Chuyển sản phẩm trực tiếp
            $this->directProducts()->update(['category_id' => $newCategoryId]);

            // Chuyển quan hệ many-to-many
            DB::table('category_product')
                ->where('category_id', $this->id)
                ->update(['category_id' => $newCategoryId]);
        });
    }

    public function transferChildrenTo(?int $newParentId = null): void
    {
        $this->children()->update(['parent_id' => $newParentId]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = \Illuminate\Support\Str::slug($model->name);
            }
        });

        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                DB::table('category_product')->where('category_id', $model->id)->delete();
            } else if (!$model->canBeDeleted()) {
                throw new \Exception('Không thể xóa danh mục khi còn sản phẩm hoặc danh mục con');
            }
        });
    }
}
