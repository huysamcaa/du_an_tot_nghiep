<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CommentReply extends Model
{
    protected $fillable = ['comment_id', 'user_id', 'reply_user_id', 'content', 'is_active'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replyUser()
    {
        return $this->belongsTo(User::class, 'reply_user_id');
    }
}
