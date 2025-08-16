<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
protected $table = 'contacts';

protected $primaryKey = 'id';

public $timestamps = true;

protected $fillable = ['name', 'email', 'phone','message','created_at','updated_at','is_contacted' ];

}
