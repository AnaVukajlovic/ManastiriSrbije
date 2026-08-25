<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Monastery;
class Ktitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'born_year', 'died_year', 'bio', 'title', 'dynasty', 'is_saint', 'saint_name', 'feast_day', 'burial_place'
    ];

    protected $casts = [
        'born_year' => 'integer',
        'died_year' => 'integer',
        'is_saint' => 'boolean',
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
public function manastiri()
{
    // Ovo povezuje ktitore i manastire preko pivot tabele
    return $this->belongsToMany(Monastery::class, 'ktitor_manastir', 'ktitor_id', 'monastery_id');
}

public function getImageSrcAttribute(): string
{
    $slug = (string) ($this->slug ?? '');
    if ($slug !== '' && file_exists(public_path("images/ktitors/{$slug}.jpg"))) {
        return asset("images/ktitors/{$slug}.jpg");
    }

    $mainImg = $this->mainImage?->image_url ?? $this->mainImage?->path;
    if ($mainImg) {
        if (str_starts_with($mainImg, 'http://') || str_starts_with($mainImg, 'https://')) {
            return $mainImg;
        }
        $clean = ltrim($mainImg, '/');
        if (file_exists(public_path($clean))) {
            return asset($clean);
        }
    }

    if (file_exists(public_path('images/ktitors/stefan-nemanja.jpg'))) {
        return asset('images/ktitors/stefan-nemanja.jpg');
    }

    return asset('images/sample/studenica.jpg');
}
}