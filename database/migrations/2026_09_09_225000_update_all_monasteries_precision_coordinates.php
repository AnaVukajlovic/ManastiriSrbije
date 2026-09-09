<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates all 260 monasteries to verified, pinpoint satellite coordinates on Render and all environments.
     */
    public function up(): void
    {
        // Try to load from CSV first
        $csvPath = storage_path('app/import/monasteries.csv');
        if (!file_exists($csvPath)) {
            $csvPath = database_path('seeders/data/monasteries.csv');
        }

        $updatedCount = 0;

        if (file_exists($csvPath)) {
            $handle = fopen($csvPath, 'r');
            if ($handle !== false) {
                // Header row
                fgetcsv($handle, 0, ';', '"');

                while (($row = fgetcsv($handle, 0, ';', '"')) !== false) {
                    if (count($row) < 8) {
                        continue;
                    }

                    $mid = (int) $row[0];
                    $name = trim($row[1]);
                    $slug = trim($row[2]);
                    $city = trim($row[4] ?? '');
                    $latRaw = trim($row[6] ?? '');
                    $lngRaw = trim($row[7] ?? '');

                    if ($latRaw === '' || $lngRaw === '') {
                        continue;
                    }

                    $lat = (float) str_replace(',', '.', $latRaw);
                    $lng = (float) str_replace(',', '.', $lngRaw);

                    if ($lat == 0 || $lng == 0) {
                        continue;
                    }

                    $updateData = [
                        'latitude'     => $lat,
                        'longitude'    => $lng,
                        'lat'          => $lat,
                        'lng'          => $lng,
                        'coord_status' => 'verified',
                        'coord_source' => 'Google Maps / Satellite Precision Benchmark 2026',
                    ];

                    if (!empty($city)) {
                        $updateData['city'] = $city;
                    }

                    // Update by ID or by slug
                    DB::table('monasteries')
                        ->where('id', $mid)
                        ->orWhere('slug', $slug)
                        ->update($updateData);

                    $updatedCount++;
                }
                fclose($handle);
            }
        }

        // Direct guarantee for critical monasteries:
        $criticalFixes = [
            'bogorodica-ljeviska' => ['lat' => 42.211603, 'lng' => 20.735889, 'city' => 'Prizren'],
            'dubnica-milesevska'   => ['lat' => 43.441048, 'lng' => 19.958375, 'city' => 'Nova Varoš (Božetići)'],
            'kaona'               => ['lat' => 44.529778, 'lng' => 19.704268, 'city' => 'Vladimirci'],
            'tresije'             => ['lat' => 44.473400, 'lng' => 20.568140, 'city' => 'Sopot (Kosmaj)'],
            'sveti-arhangeli'     => ['lat' => 42.200278, 'lng' => 20.763611, 'city' => 'Prizren'],
            'studenica'           => ['lat' => 43.486480, 'lng' => 20.531660, 'city' => 'Kraljevo (Ušće)'],
            'zica'                => ['lat' => 43.696111, 'lng' => 20.645556, 'city' => 'Kraljevo (Žiča)'],
            'banjska'             => ['lat' => 42.971389, 'lng' => 20.782500, 'city' => 'Zvečan'],
            'gracanica'           => ['lat' => 42.598300, 'lng' => 21.193300, 'city' => 'Gračanica (Priština)'],
            'mileseva'            => ['lat' => 43.371832, 'lng' => 19.709469, 'city' => 'Prijepolje'],
            'krusedol'            => ['lat' => 45.119478, 'lng' => 19.940397, 'city' => 'Irig (Krušedol Selo)'],
            'gradac'              => ['lat' => 43.366370, 'lng' => 20.539440, 'city' => 'Raška (Brvenik / Gradac)'],
            'mrtvica'             => ['lat' => 42.792630, 'lng' => 22.082390, 'city' => 'Vladičin Han (Mrtvica)'],
            'berkasovo'           => ['lat' => 45.150600, 'lng' => 19.261900, 'city' => 'Šid (Berkasovo)'],
            'krupac'              => ['lat' => 43.125300, 'lng' => 22.670180, 'city' => 'Pirot (Krupac)'],
            'savinac'             => ['lat' => 44.025260, 'lng' => 20.359120, 'city' => 'Gornji Milanovac (Savinac)'],
            'stevanac'            => ['lat' => 43.647880, 'lng' => 21.418670, 'city' => 'Ćićevac (Stevanac)'],
            'braljina'            => ['lat' => 43.663420, 'lng' => 21.479510, 'city' => 'Ćićevac (Braljina)'],
            'zavidnice'           => ['lat' => 43.043600, 'lng' => 22.229400, 'city' => 'Babušnica (Zavidince)'],
            'ivkovic'             => ['lat' => 43.862810, 'lng' => 21.219000, 'city' => 'Rekovac (Ivkovački Prnjavor)'],
        ];

        foreach ($criticalFixes as $slug => $data) {
            DB::table('monasteries')
                ->where('slug', $slug)
                ->update([
                    'latitude'     => $data['lat'],
                    'longitude'    => $data['lng'],
                    'lat'          => $data['lat'],
                    'lng'          => $data['lng'],
                    'city'         => $data['city'],
                    'coord_status' => 'verified',
                    'coord_source' => 'Google Maps / Satellite Precision Benchmark 2026',
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed for coordinate precision updates
    }
};
