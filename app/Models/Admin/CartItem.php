<?php

namespace App\Models\Admin;

use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'quantity'
    ];

    protected $with = [
        'product',
        'variant'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
