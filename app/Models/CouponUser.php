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
        'amount',
        'used_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
