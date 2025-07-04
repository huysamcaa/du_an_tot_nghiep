<?php
namespace App\Models\Admin;
use App\Models\Shared\Order;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'product_id', 'order_id', 'user_id',
        'rating', 'review_text', 'reason', 'is_active',
        'created_at', 'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function multimedia()
    {
        return $this->hasMany(ReviewMultimedia::class);
    }
}
