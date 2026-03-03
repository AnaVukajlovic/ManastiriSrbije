<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KtitorImage extends Model
{
    protected $fillable = ['ktitor_id','path','caption','source','credit','sort'];

    public function ktitor()
    {
        return $this->belongsTo(Ktitor::class);
    }
}