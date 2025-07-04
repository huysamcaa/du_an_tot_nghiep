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
}