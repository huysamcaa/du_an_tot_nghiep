<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Client\UserAddress;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Coupon;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'gender',
        'birthday',
        'loyalty_points',
        'role', // Thêm 'role' vào fillable
        'status',
        'google_id',
        'facebook_id',
        'code_verified_email',
        'bank_name',
        'user_bank_name',
        'bank_account',
        'reason_lock',
        'is_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'code_verified_email',
        'bank_account',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'code_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 9+ tự động hash mật khẩu
        'birthday' => 'date',
        'is_change_password' => 'boolean',
    ];

    /**
     * Kiểm tra xem người dùng có phải là admin không.
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    /**
     * Kiểm tra xem tài khoản người dùng có hoạt động không.
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
 public function coupons()
{
    return $this->belongsToMany(Coupon::class, 'coupon_user')
                ->using(CouponUser::class)
                ->withPivot(['id', 'amount', 'created_at', 'updated_at'])
                ->withTimestamps();
}
}
