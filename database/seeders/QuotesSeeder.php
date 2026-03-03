<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuotesSeeder extends Seeder
{
    public function run(): void
    {
        // Na SQLite je bezbednije ovo nego truncate, da ne pravi FK probleme
        Quote::query()->delete();

        for ($i = 1; $i <= 365; $i++) {
            Quote::create([
                'text' => "Budimo ljudi.",
                'author' => 'Patrijarh Pavle',
                'source' => null,
                'is_active' => true,
                'weight' => 1,
                'day_of_year' => $i,
            ]);
        }
    }
}