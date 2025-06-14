<?php 
// app/Models/Attribute.php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'slug',
        'is_variant',
        'is_active'
    ];

    protected $casts = [
        'is_variant' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
    public function attributeValues()
{
    return $this->hasMany(\App\Models\Admin\AttributeValue::class);
}

    public function scopeForVariants($query)
    {
        return $query->where('is_variant', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}