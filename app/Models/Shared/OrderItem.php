<?php
namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = [];
    public $timestamps = false;
}