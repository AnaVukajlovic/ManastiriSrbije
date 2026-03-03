<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonasteryProfile extends Model
{
    protected $fillable = [
        'monastery_id',
        'intro',
        'history',
        'architecture',
        'ktitor_text',
        'art_frescoes',
        'interesting_facts',
        'sources_json',
    ];

    protected $casts = [
        'sources_json' => 'array',
    ];

    public function monastery(): BelongsTo
    {
        return $this->belongsTo(Monastery::class);
    }
}