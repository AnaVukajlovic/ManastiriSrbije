<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curiosity extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'reading_minutes',
        'image',
        'excerpt',
        'content',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'reading_minutes' => 'integer',
    ];
}