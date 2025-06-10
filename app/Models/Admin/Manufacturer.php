<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'is_active'];
}
