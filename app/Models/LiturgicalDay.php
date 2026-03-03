<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiturgicalDay extends Model
{
    protected $fillable = ['date','feast','fasting','weather'];
}
