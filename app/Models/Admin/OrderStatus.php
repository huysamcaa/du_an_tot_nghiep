<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';
    protected $fillable = ['name'];

    // app/Models/Admin/OrderStatus.php

    public function orderOrderStatuses()
    {
        return $this->hasMany(\App\Models\Admin\OrderOrderStatus::class, 'order_status_id');
    }
    public $timestamps = false;
}

