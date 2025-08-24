<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'image','blog_category_id',];

    public function getThumbnailAttribute()
    {
        $content = $this->content;
        $doc = new \DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        $tags = $doc->getElementsByTagName('img');

        if ($tags->length > 0) {
            return $tags->item(0)->getAttribute('src');
        }

        return null;
    }
    public function getFirstImageFromContentAttribute()
{
    if (!$this->content) return null;

    preg_match('/<img[^>]+src="([^">]+)"/i', $this->content, $matches);

    return $matches[1] ?? null;
}

public function category()
{
    return $this->belongsTo(BlogCategory::class, 'blog_category_id');
}

}
