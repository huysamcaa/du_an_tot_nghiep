<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shared\OrderItem;
use App\Models\Admin\Product;
use App\Models\Refund;
use App\Models\Admin\ProductVariant;

class RefundItem extends Model
{
    protected $fillable = [
        'refund_id',
        'product_id',
        'variant_id',
        'name',
        'name_variant',
        'quantity',
        'price',
        'price_variant',
        'quantity_variant'
    ];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    
}
