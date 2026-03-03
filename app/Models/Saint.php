<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saint extends Model
{
    protected $fillable = [
        'name', 'short_name', 'slug', 'bio', 'icon',
        'wikipedia_url', 'source', 'is_active'
    ];

    public function calendarDays()
    {
        return $this->belongsToMany(CalendarDay::class, 'calendar_day_saint')
            ->withPivot(['priority'])
            ->withTimestamps()
            ->orderBy('pivot_priority');
    }
}