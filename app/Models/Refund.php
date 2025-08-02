<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shared\Order;
use App\Models\RefundItem;


class Refund extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'total_amount',
        'bank_account', 'user_bank_name', 'phone_number',
        'bank_name', 'reason', 'fail_reason',
        'img_fail_or_completed', 'reason_image',
        'admin_reason', 'is_send_money',
        'status', 'bank_account_status'
    ];

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
