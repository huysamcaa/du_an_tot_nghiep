<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ReviewMultimedia extends Model
{
    protected $table = 'review_multimedia';

    protected $fillable = [
        'review_id', 'file', 'file_type', 'mime_type'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
