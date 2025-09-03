<?php

namespace App\Models;

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\CouponRestriction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\CouponUserGroupScope;
class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'usage_count',
        'user_group',
        'is_expired',
        'is_active',
        'is_notified',
        'start_date',
        'end_date'
    ];
  protected static function booted(): void
    {
        static::addGlobalScope(new CouponUserGroupScope);
    }
    // Quan hệ 1-1: Mỗi mã có thể có 1 bộ ràng buộc
    public function restriction()
    {

        return $this->hasOne(CouponRestriction::class);
    }

    // Quan hệ nhiều-nhiều với người dùng
  public function users()
{
    return $this->belongsToMany(User::class, 'coupon_user')
                ->using(CouponUser::class)
               ->withPivot(\App\Models\CouponUser::SNAPSHOT_COLUMNS)
                ->withTimestamps();
}

    protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'is_active' => 'boolean',
    'is_expired' => 'boolean',
    'is_notified' => 'boolean',
];
public function products()
{
    return $this->belongsToMany(Product::class, 'coupon_product');
}

public function categories()
{
    return $this->belongsToMany(Category::class, 'coupon_category');
}
// HẾT HẠN thật (không lưu DB): do hết ngày (nếu có thời hạn) hoặc hết lượt
public function getExpiredAttribute(): bool
{
    $overByTime = $this->is_expired
        && $this->end_date
        && now()->greaterThan($this->end_date);

    $overByUsage = !is_null($this->usage_limit)
        && (int)$this->usage_count >= (int)$this->usage_limit;

    return $overByTime || $overByUsage;
}

// Lọc các coupon còn dùng được (tiện cho truy vấn)
public function scopeValid($q)
{
    return $q->where('is_active', true)
             ->where(function ($qq) {
                 $qq->whereNull('usage_limit')
                    ->orWhereColumn('usage_count', '<', 'usage_limit');
             })
             ->where(function ($qq) {
                 // không có thời hạn -> pass | có thời hạn -> end_date chưa qua
                 $qq->where('is_expired', false)
                    ->orWhereNull('end_date')
                    ->orWhere('end_date', '>=', now());
             });
}

}

