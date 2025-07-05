<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OrderOrderStatus extends Model
{
    protected $table = 'order_order_status';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(\App\Models\Shared\Order::class, 'order_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
