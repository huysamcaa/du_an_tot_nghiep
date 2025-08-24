<?php 
// app/Models/AttributeValue.php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attribute_id',
        'value',
        'hex',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_value_product');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'attribute_value_product_variant');
    }
    
    
    public function productVariants()
{
    return $this->belongsToMany(
        ProductVariant::class,
        'attribute_value_product_variant', // Tên bảng pivot
        'attribute_value_id',              // Khóa ngoại của model hiện tại trong bảng pivot
        'product_variant_id'               // Khóa ngoại của model liên quan trong bảng pivot
    );
}
}