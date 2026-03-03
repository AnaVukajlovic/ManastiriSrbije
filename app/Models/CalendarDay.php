<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDay extends Model
{
protected $fillable = [
  'date','feast_name','saint_name','fasting_type','note','is_red_letter'
];

    protected $casts = [
        'date' => 'date',
        'is_red_letter' => 'boolean',
    ];

    public function saints()
    {
        return $this->belongsToMany(Saint::class, 'calendar_day_saint')
            ->withPivot(['priority'])
            ->withTimestamps()
            ->orderBy('pivot_priority');
    }
}