<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategory extends Model
{
    protected $fillable = ['module','title','slug','description','sort_order'];

    public function items()
    {
        return $this->hasMany(ContentItem::class, 'category_id')
            ->orderBy('sort_order')
            ->orderBy('title');
    }
}
