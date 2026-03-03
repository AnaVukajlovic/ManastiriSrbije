<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    protected $fillable = ['category_id','title','slug','excerpt','body','source_url','sort_order'];

    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }
}
