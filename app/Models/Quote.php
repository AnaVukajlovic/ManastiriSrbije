<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [

  'text','author','source','is_active','weight','day_of_year'

    ];
}