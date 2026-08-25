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

    public function getReadingMinutesAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $words = str_word_count(strip_tags($this->content));

        return max(1, ceil($words / 200));
    }

    public function getImageSrcAttribute(): string
    {
        $raw = trim((string) ($this->image ?? ''));
        if ($raw !== '') {
            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                return $raw;
            }
            $clean = ltrim($raw, '/');
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
            $base = basename($clean);
            if (file_exists(public_path("images/curiosities/{$base}"))) {
                return asset("images/curiosities/{$base}");
            }
        }

        $slug = (string) ($this->slug ?? '');
        if ($slug !== '' && file_exists(public_path("images/curiosities/{$slug}.jpg"))) {
            return asset("images/curiosities/{$slug}.jpg");
        }

        if (file_exists(public_path('images/curiosities/default.jpg'))) {
            return asset('images/curiosities/default.jpg');
        }

        return asset('images/sample/djurdjevi.jpg');
    }
}