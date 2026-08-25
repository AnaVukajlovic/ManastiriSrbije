<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuotesSeeder extends Seeder
{
    public function run(): void
    {
        Quote::query()->delete();

        $path = storage_path('app/seed/quotes.json');
        if (!file_exists($path)) {
            $path = storage_path('app/private/seed/quotes.json');
        }

        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) {
                foreach ($data as $q) {
                    Quote::create([
                        'day_of_year' => $q['day_of_year'] ?? null,
                        'text'        => $q['text'],
                        'author'      => $q['author'] ?? 'Patrijarh Pavle',
                        'source'      => $q['source'] ?? null,
                        'is_active'   => $q['is_active'] ?? true,
                        'weight'      => $q['weight'] ?? 1,
                    ]);
                }
                return;
            }
        }

        // Fallback ako fajl ne postoji
        for ($i = 1; $i <= 366; $i++) {
            Quote::create([
                'day_of_year' => $i,
                'text'        => "Budimo ljudi, makar i po cenu života, ali neljudi nemojmo biti ni po cenu celog sveta.",
                'author'      => 'Patrijarh Pavle',
                'source'      => 'Pouke',
                'is_active'   => true,
                'weight'      => 1,
            ]);
        }
    }
}