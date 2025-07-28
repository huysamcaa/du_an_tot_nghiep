<?php

namespace App\Models\Client;

use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = ['user_id','product_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
