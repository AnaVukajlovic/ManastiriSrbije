<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonasteryImage extends Model
{
    protected $fillable = ['monastery_id','url','caption','sort_order'];

    public function monastery()
    {
        return $this->belongsTo(Monastery::class);
    }

    public function getImageSrcAttribute(): string
    {
        $u = $this->url;
        if (empty($u)) {
            return asset('images/monasteries/placeholder.jpg');
        }
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) {
            return $u;
        }
        return asset(ltrim($u, '/'));
    }
}
