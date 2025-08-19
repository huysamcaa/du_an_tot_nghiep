<?php
namespace App\Models\Shared;

use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\ProductVariant;
use App\Models\Client\UserAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'user_id',
        'payment_id',
        'total_amount',
        'phone_number',
        'email',
        'fullname',
        'address',
        'note',
        'is_paid',
        'is_refund',
        'is_refund_cancel',
        'coupon_id',
        'coupon_code',
        'coupon_discount_type',
        'coupon_discount_value',
        'max_discount_value',
        'payment_info',
    ];

    protected $table   = 'orders';
    protected $guarded = [];
    public $timestamps = true;
    protected $appends = ['status_text', 'status_class'];
    use SoftDeletes;

    public function getStatusTextAttribute()
{
    if (!$this->currentStatus || !$this->currentStatus->orderStatus) {
        return 'Không xác định';
    }

    return $this->currentStatus->orderStatus->name;
}

public function getStatusClassAttribute()
{
    $statusId = $this->currentStatus->order_status_id ?? 0;

    $classMap = [
        1 => 'badge-secondary',  // Chờ Xác Nhận
        2 => 'badge-primary',    // Đã Xác Nhận
        3 => 'badge-info',       // Đang xử lý
        4 => 'badge-info',       // Đang giao hàng
        5 => 'badge-success',    // Đã hoàn thành
        6 => 'badge-warning',    // Trả hàng/Hoàn tiền
        7 => 'badge-danger',     // Hủy Đơn
        8 => 'badge-dark',       // Thất bại
        9 => 'badge-success',    // Đã Thanh Toán
        10 => 'badge-secondary'  // Chờ Thanh Toán
    ];

    return $classMap[$statusId] ?? 'badge-dark';
}
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function orderOrderStatuses()
    {
        return $this->hasMany(\App\Models\Admin\OrderOrderStatus::class, 'order_id');
    }

    // Lấy trạng thái hiện tại
    public function currentStatus()
    {
        return $this->hasOne(\App\Models\Admin\OrderOrderStatus::class, 'order_id')->orderByDesc('created_at');
    }

    public function refunds()
    {
        return $this->hasMany(\App\Models\Refund::class);
    }
    public function canBeCancelled()
    {
        // Chỉ cho phép hủy khi đơn hàng ở trạng thái chờ xác nhận (status_id = 1)
        return $this->current_status_id == 1;
    }

    public function getCurrentStatusIdAttribute()
    {
        return $this->statuses()->wherePivot('is_current', 1)->first()->id;
    }
    public function statuses()
    {
        return $this->hasMany(OrderOrderStatus::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

}
