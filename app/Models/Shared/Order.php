<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];
    public $timestamps = true;

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
}
