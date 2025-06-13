<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturersFactory> */

    use HasFactory;
    protected $fillable=[
        'name','slug','logo_path','website','description','is_active',
    ];
    // Scope lọc nhà sản xuất đang hiển thị
    public function scopeActive($q){
        return $q->where('is_active', true);
    }
}
