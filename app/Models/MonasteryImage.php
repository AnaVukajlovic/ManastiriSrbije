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
}
