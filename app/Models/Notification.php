<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'message',
        'type',
        'read',
    ];

    // Mối quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng Coupon
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
