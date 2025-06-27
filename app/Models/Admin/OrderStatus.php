<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = ['name'];

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_order_status');
    }
    public function currentOrders()
    {
        return $this->belongsToMany(Order::class, 'order_order_status')
                    ->where('order_order_status.is_current', 1);
    }

}
