<?php

namespace App\Models;

use App\Models\Admin\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class OrderOrderStatus extends Model
{
    protected $table = 'order_order_status';

    protected $fillable = [
        'order_id',
        'order_status_id',
        'modified_by',
        'notes',
        'employee_evidence',
        'customer_confirmation',
        'is_current',
        'created_at',
        'updated_at',
    ];

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}