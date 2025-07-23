<?php
namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    protected $fillable = ['product_id', 'user_id', 'content', 'is_active'];


    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}