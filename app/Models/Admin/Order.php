<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code', 
        'user_id', 
        'phone_number',
        'email',
        'fullname',
        'address',
        'note',
        'total_amount'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }
        // Mối quan hệ với bảng OrderStatus qua bảng trung gian order_order_status
    public function statuses()
    {
        return $this->belongsToMany(OrderStatus::class, 'order_order_status');
    }

    // Kiểm tra trạng thái hiện tại của đơn hàng (lấy trạng thái cuối cùng)

    public function currentStatus()
{
    return $this->statuses()->orderBy('order_order_status.created_at', 'desc')->first(); // Lấy trạng thái cuối cùng
}

}
