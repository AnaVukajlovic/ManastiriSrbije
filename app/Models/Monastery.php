<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Monastery extends Model
{
    protected $fillable = [
        'name',
        'slug',

        // legacy (da UI radi i bez FK)
        'region',
        'city',

        // FK za filtere
        'region_id',
        'city_id',
        'eparchy_id',

        // tekstovi (ako ih već koristiš u monasteries tabeli)
        'excerpt',
        'description',
        'history',
        'architecture',
        'art',
        'spiritual_life',
        'visiting',
        'sources',

        // geo/media
        'latitude',
        'longitude',
        'image_url',

        // status
        'review_status',
        'is_approved',

        // ostala postojeća polja u tabeli
        'address',
        'phone',
        'email',
        'website',
        'opening_hours',
        'wikipedia_url',
        'wikidata_item',
        'source',
        'wikidata_qid',
        'religion_qid',
        'denomination_qid',
        'is_spc',
        'is_spc_guess',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function tours(): HasMany
    {
        return $this->hasMany(VirtualTour::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(MonasteryImage::class)->orderBy('sort_order');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

public function profile()
{
    return $this->hasOne(\App\Models\MonasteryProfile::class);
}
public function eparchy()
{
    return $this->belongsTo(\App\Models\Eparchy::class);
}
protected $appends = ['lat','lng'];
public function getLatAttribute()
{
    return $this->attributes['latitude'] ?? null;
}

public function getLngAttribute()
{
    return $this->attributes['longitude'] ?? null;
}




}