<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productVariant()
{
    return $this->belongsTo(ProductVariant::class);
}
}
