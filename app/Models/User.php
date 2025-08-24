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
use App\Models\CouponUser;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            ->withTrashed()
            ->withPivot(array_merge(['id'], CouponUser::SNAPSHOT_COLUMNS))
            ->withTimestamps();
    }

    public function notifications()
    {

        // Mối quan hệ giữa User và Notification (1 User có nhiều thông báo)
        return $this->hasMany(Notification::class, 'user_id');
    }
     public function refunds(): HasMany
    {
        return $this->hasMany(\App\Models\Refund::class, 'user_id');
    }
 public function grossSpent(): int|float
    {
        // Nếu bạn có cột tổng tiền trên orders (vd: total_price), ưu tiên dùng sum('total_price')
        // Ở đây minh hoạ tính theo items để bạn điều chỉnh cho khớp schema của bạn.
        return $this->orders()
            ->whereHas('currentStatus.orderStatus', fn($q) => $q->where('name', 'đã hoàn thành'))
            ->with(['items' => function ($q) {
                $q->select('order_id', 'price', 'quantity');
            }])
            ->get()
            ->sum(function ($order) {
                return $order->items->sum(fn($i) => $i->price * $i->quantity);
            });
    }
       public function refundedSpent(): int|float
    {
        return $this->refunds()
            ->where('status', 'completed')
            ->sum('total_amount');
    }
    /**
     * Tổng chi tiêu gốc (chỉ các đơn đã HOÀN THÀNH)
     * -> bạn có thể thay bằng cột total_paid/total_price nếu đã có.
     */
    public const TIER_MEMBER = 3_000_000;
    public const TIER_VIP    = 4_000_000;
    public function totalSpent(): int|float
    {
        $gross = $this->grossSpent();
        $refunded = $this->refundedSpent();
        $net = max(0, $gross - $refunded); // không âm
        return $net;
    }

    /**
     * Cập nhật hạng thành viên dựa trên totalSpent() hiện tại.
     * Ví dụ ngưỡng của bạn: <3tr = Khách; 3–<4tr = Thành viên; ≥4tr = VIP (tự chỉnh)
     */

     public function refreshGroup(): void
    {
        $spent = $this->totalSpent();

        // Ví dụ mapping — chỉnh theo logic/hằng số bạn đang dùng
        if ($spent >= 4_000_000) {
            $this->user_group = 'vip';
        } elseif ($spent >= 3_000_000) {
            $this->user_group = 'member';
        } else {
            $this->user_group = 'guest';
        }

        $this->save();
    }

    public function isVip(): bool
    {
        return $this->user_group === 'vip';
    }

    public function groupLabel(): string
    {
        return match ($this->user_group) {
            'vip' => 'VIP',
            'member' => 'Thành viên',
            default => 'Khách',
        };
    }

     public function groupBadgeClass(): string
    {
        return match ($this->user_group) {
            'vip' => 'bg-warning text-dark',
            'member' => 'bg-primary',
            default => 'bg-secondary',
        };
    }
    // Tính bậc kế tiếp & số tiền còn thiếu
    public function nextTierLabel(): ?string
    {
        return match (true) {
            $this->user_group === 'guest'  => 'Thành viên',
            $this->user_group === 'member' => 'VIP',
            default => null,
        };
    }

    public function remainingToNextTier(): int
    {
        $t = $this->totalSpent();
        return match (true) {
            $t < self::TIER_MEMBER => max(0, self::TIER_MEMBER - $t),
            $t < self::TIER_VIP    => max(0, self::TIER_VIP - $t),
            default                => 0,
        };
    }
}
