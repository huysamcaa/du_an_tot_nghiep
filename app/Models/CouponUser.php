<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Coupon;
use App\Models\User;

class CouponUser extends Pivot
{
    protected $table = 'coupon_user';

    protected $fillable = [
        'coupon_id',
        'user_id',
        'code',
        'title',
        'amount',
        'used_at',
        'discount_type',
        'discount_value',
        'min_order_value',
        'max_discount_value',
        'valid_categories',
        'valid_products',
        'start_date',
        'end_date',
        'user_group',
        'usage_limit',
         'order_id',
         'discount_applied',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'valid_categories' => 'array',
        'valid_products' => 'array',
        'user_group' => 'string',
        'usage_limit' => 'integer',
        'order_id' => 'integer',
        'discount_applied' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // App\Models\CouponUser.php
        public const SNAPSHOT_COLUMNS = [
            'code',
            'title',
            'discount_type',
            'discount_value',
            'min_order_value',
            'max_discount_value',
            'valid_categories',
            'valid_products',
            'start_date',
            'end_date',
            'user_group',
            'usage_limit',
            'amount',
            'used_at',
            'order_id',
            'discount_applied',
            'created_at',
            'updated_at',
        ];


    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
