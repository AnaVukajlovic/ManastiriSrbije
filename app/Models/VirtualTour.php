<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualTour extends Model
{
    protected $fillable = ['monastery_id','title','provider','embed_url','sort_order'];

    public function monastery()
    {
        return $this->belongsTo(Monastery::class);
    }
}
