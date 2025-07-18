<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponRestriction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'coupon_id',
        'min_order_value',
        'max_discount_value',
        'valid_categories',
        'valid_products'
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    protected $casts = [
    'valid_products' => 'array',
    'valid_categories' => 'array',
];

}
