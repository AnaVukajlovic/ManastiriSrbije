<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MonasteriesCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/monasteries.csv');
        if (!file_exists($path)) {
            $this->command?->error("CSV not found: {$path}");
            return;
        }

        // OVDE UVEZI NAŠ NIZ KOORDINATA KOJI SMO DEFINISALI
        $fixes = [ /* ovde nalepi onaj veliki niz sa koordinatama */ ];

        $handle = fopen($path, 'r');
        fgetcsv($handle, 0, ';', '"'); // Preskoči header
        
        $now = now();
        $insertedOrUpdated = 0;

        while (($row = fgetcsv($handle, 0, ';', '"')) !== false) {
            if (count($row) < 15) continue;

            $slug = trim($row[0]);
            
            $eparchyName = trim($row[8]);
            
            // Najjednostavnija i najbrža pretraga jer su imena sada identična
            $eparchy = DB::table('eparchies')
                ->where('name', $eparchyName)
                ->first();

            $eparchyId = $eparchy ? $eparchy->id : null;

            $eparchyId = $eparchy ? $eparchy->id : null;             // PRIVREMENI TEST ZA ISPIS U TERMINALU
            if (!$eparchy && !empty($eparchyName)) {
                $this->command?->warn("Nije povezano: Iz CSV-a čitam '{$eparchyName}'");
            }
            $eparchyId = $eparchy ? $eparchy->id : null;

            // 2. KORIŠĆENJE FIXES NIZA ZA KOORDINATE
$lat = (float)str_replace(',', '.', $row[5] ?? 0);
            $lng = (float)str_replace(',', '.', $row[6] ?? 0);

            if (isset($fixes[$slug])) {
                $lat = $fixes[$slug][0];
                $lng = $fixes[$slug][1];
            }

            // NOVO REŠENJE: Direktan "hardkodovani" spas za Banjsku preko imena
            if (str_contains(strtolower(trim($row[1])), 'banjska')) {
                $lat = 42.971389;
                $lng = 20.781667;
            }

            $data = [
                'name' => trim($row[1]),
                'slug' => $slug,
                'region' => $row[2] ?? null,
                'city' => $row[3] ?? null,
                'description_short' => $row[4] ?? null,
                'lat' => $lat,
                'lng' => $lng,
                'latitude' => $lat,  // Dodajemo i latitude/longitude kolone ako ih koristiš
                'longitude' => $lng,
                'status' => $row[7] ?? 'aktivan',
                'eparchy_id' => $eparchyId,
                'description' => $row[9] ?? null,
                'image_url' => $row[10] ?? null,
                'wikipedia_url' => $row[11] ?? null,
                'source' => $row[12] ?? null,
                'ktitor' => !empty(trim($row[13])) ? trim($row[13]) : 'Nepoznato',
                'godina_izgradnje' => !empty(trim($row[14])) ? trim($row[14]) : null,
                'updated_at' => $now,
            ];

            DB::table('monasteries')->updateOrInsert(['slug' => $slug], $data);
            $insertedOrUpdated++;
        }
        fclose($handle);
        $this->command?->info("Uvezeno: {$insertedOrUpdated} manastira.");
    }
}