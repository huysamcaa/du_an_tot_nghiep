<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Shared\Order;
use App\Models\Client\UserAddress;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'role',
        'status',
        'user_group',
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
    public function orders()
{
    return $this->hasMany(Order::class, 'user_id');
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
