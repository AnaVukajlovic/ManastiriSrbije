<?php

namespace App\Services;

use App\Models\Quote;
use Carbon\Carbon;

class QuoteService
{
    public static function today(): ?Quote
    {
        $dayOfYear = Carbon::now('Europe/Belgrade')->dayOfYear; // 1..366

        $quote = Quote::where('is_active', true)
            ->where('day_of_year', $dayOfYear)
            ->first();

        if ($quote) {
            return $quote;
        }

        $quotes = Quote::where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($quotes->isEmpty()) return null;

        $idx = ($dayOfYear - 1) % $quotes->count();
        return $quotes[$idx];
    }
}