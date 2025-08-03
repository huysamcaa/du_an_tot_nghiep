<?php
namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shared\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'attributes_variant' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
