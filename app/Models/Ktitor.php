<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ktitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'born_year', 'died_year', 'bio',
    ];

    protected $casts = [
        'born_year' => 'integer',
        'died_year' => 'integer',
    ];

    protected static function booted()
    {
        static::saving(function ($k) {
            if (!$k->slug || trim((string)$k->slug) === '') {
                $k->slug = Str::of((string)$k->name)->slug('-')->toString();
            }
        });
    }

    public function images()
    {
        return $this->hasMany(\App\Models\KtitorImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(\App\Models\KtitorImage::class)->orderBy('sort');
    }

    // opcionalno (nekad korisno)
    public function latestImage()
    {
        return $this->hasOne(\App\Models\KtitorImage::class)->latest('sort');
    }
}