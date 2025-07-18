<?php
namespace App\Models;

use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'is_active',
    ];
       public function products()
    {
        return $this->hasMany(Product::class);
    }
}
