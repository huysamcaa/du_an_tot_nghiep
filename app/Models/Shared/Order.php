<?php

namespace App\Models\Shared;

use App\Models\Admin\ProductVariant;
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
        'payment_info'
    ];

    protected $table = 'orders';
    protected $guarded = [];
    public $timestamps = true;




    use SoftDeletes;



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






    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
