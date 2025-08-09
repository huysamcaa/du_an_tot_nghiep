<?php

namespace App\Models;

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\CouponRestriction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

}

