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

    public function getImageSrcAttribute(): string
    {
        $p = (string) ($this->path ?? '');
        if ($p !== '') {
            if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
                return $p;
            }
            $clean = ltrim($p, '/');
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
        }
        return asset('images/sample/studenica.jpg');
    }
}