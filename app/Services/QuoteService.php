<?php

namespace App\Services;

use App\Models\Quote;
use Carbon\Carbon;

class QuoteService
{
    public static function today(): ?Quote
    {
        $quotes = Quote::where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($quotes->isEmpty()) return null;

        // deterministički izbor po datumu (isti citat svima tog dana)
        $day = Carbon::today()->dayOfYear; // 1..365/366
        $idx = ($day - 1) % $quotes->count();

        return $quotes[$idx];
    }
}