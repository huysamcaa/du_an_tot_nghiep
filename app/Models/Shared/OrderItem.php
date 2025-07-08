<?php
namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shared\Order;
use App\Models\Admin\Product;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = [];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
