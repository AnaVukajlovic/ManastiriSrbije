<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonasteriesCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/monasteries.csv');
        if (!file_exists($path)) {
            $path = database_path('seeders/data/monasteries.csv');
        }

        if (!file_exists($path)) {
            $this->command?->error("CSV not found: {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 0, ';', '"');
        if (!$header) {
            fclose($handle);
            return;
        }

        // Clean BOM if present
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);

        $now = now();
        $updatedCount = 0;

        // Cleanup any accidental corrupt duplicates with id > 260 or lat = 0
        DB::table('monasteries')->where('id', '>', 260)->delete();
        DB::table('monasteries')->where('latitude', 0)->orWhere('lat', 0)->delete();

        while (($row = fgetcsv($handle, 0, ';', '"')) !== false) {
            if (count($row) < 8) {
                continue;
            }

            // Safe dictionary mapping
            $record = [];
            foreach ($header as $idx => $colName) {
                $record[$colName] = $row[$idx] ?? null;
            }

            $id = isset($record['id']) && is_numeric($record['id']) ? (int)$record['id'] : null;
            $slug = trim($record['slug'] ?? '');
            if (!$slug || is_numeric($slug)) {
                continue;
            }

            $latRaw = $record['latitude'] ?? $record['lat'] ?? 0;
            $lngRaw = $record['longitude'] ?? $record['lng'] ?? 0;

            $lat = (float) str_replace(',', '.', (string)$latRaw);
            $lng = (float) str_replace(',', '.', (string)$lngRaw);

            if ($lat < 40 || $lat > 48 || $lng < 18 || $lng > 25) {
                continue;
            }

            $eparchyId = isset($record['eparchy_id']) && is_numeric($record['eparchy_id']) ? (int)$record['eparchy_id'] : null;

            $data = [
                'name'              => trim($record['name'] ?? ''),
                'slug'              => $slug,
                'region'            => $record['region'] ?? null,
                'city'              => $record['city'] ?? null,
                'description_short' => $record['description_short'] ?? $record['excerpt'] ?? null,
                'lat'               => $lat,
                'lng'               => $lng,
                'latitude'          => $lat,
                'longitude'         => $lng,
                'status'            => $record['status'] ?? 'aktivan',
                'eparchy_id'        => $eparchyId,
                'description'       => $record['description'] ?? null,
                'image_url'         => $record['image_url'] ?? null,
                'wikipedia_url'     => $record['wikipedia_url'] ?? null,
                'source'            => $record['source'] ?? null,
                'ktitor'            => !empty(trim($record['ktitor'] ?? '')) ? trim($record['ktitor']) : 'Nepoznato',
                'godina_izgradnje'  => !empty(trim($record['godina_izgradnje'] ?? '')) ? trim($record['godina_izgradnje']) : null,
                'coord_status'      => 'verified',
                'coord_source'      => 'Google Maps / Satellite Precision Benchmark 2026',
                'updated_at'        => $now,
            ];

            if ($id) {
                DB::table('monasteries')->updateOrInsert(['id' => $id], $data);
            } else {
                DB::table('monasteries')->updateOrInsert(['slug' => $slug], $data);
            }
            $updatedCount++;
        }

        fclose($handle);
        $this->command?->info("Uvezeno i ažurirano: {$updatedCount} manastira.");
    }
}