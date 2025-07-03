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

    public function histories()
    {
        return $this->hasMany(OrderOrderStatus::class, 'order_status_id');
    }

}

