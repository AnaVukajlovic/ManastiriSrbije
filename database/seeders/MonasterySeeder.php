<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monastery;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MonasterySeeder extends Seeder
{
public function run(): void
{
    $csvPath = storage_path('app/import/monasteries.csv');
    if (!File::exists($csvPath)) return;

    $file = fopen($csvPath, 'r');
    $headers = fgetcsv($file, 0, ';');
    $h = array_flip($headers);

    while (($row = fgetcsv($file, 0, ';')) !== false) {
        if (count($row) < count($headers)) continue;

        // U tvom MonasterySeeder.php, u delu gde se upisuju podaci:
\App\Models\Monastery::updateOrInsert(
    ['slug' => str_replace('"', '', $row[$h['slug']])],
    [
        'name'              => $row[$h['name']] ?? 'Nepoznato',
        'ktitor'            => $row[$h['ktitor']] ?? null, // OVO MORA DA BUDE TU
        'godina_izgradnje'  => $row[$h['godina_izgradnje']] ?? null, // OVO MORA DA BUDE TU
        'region'            => $row[$h['region']] ?? 'Nepoznato',
        'city'              => $row[$h['city']] ?? 'Nepoznato',
        'updated_at'        => now(),
    ]
);
    }
    fclose($file);
     $this->command->info("Uvoz završen.");
}

       
    
}