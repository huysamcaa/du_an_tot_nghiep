<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'address', 'phone_number', 'fullname', 'id_default'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
