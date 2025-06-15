<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;

    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id',
        'image',
    ];

    // Mối quan hệ ngược với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public $timestamps = false;

}
