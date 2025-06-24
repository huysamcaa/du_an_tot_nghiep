<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
                    ->withPivot('amount') // lấy thêm cột amount
                    ->withTimestamps();   // lấy thời gian tạo/sửa
    }
}
