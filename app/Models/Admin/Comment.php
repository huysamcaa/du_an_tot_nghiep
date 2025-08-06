<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'user_id', 'content', 'is_active'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
