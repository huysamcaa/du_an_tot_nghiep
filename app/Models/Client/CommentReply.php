<?php
namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CommentReply extends Model
{
    protected $fillable = ['comment_id', 'user_id', 'reply_user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}