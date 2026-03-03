<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eparchy extends Model
{
    protected $fillable = ['name', 'slug'];

    public function monasteries()
    {
        return $this->hasMany(\App\Models\Monastery::class);
    }

}
