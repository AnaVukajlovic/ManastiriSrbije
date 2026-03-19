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

        $probe = fopen($path, 'r');
        if ($probe === false) {
            $this->command?->error("Cannot open CSV: {$path}");
            return;
        }

        $firstPhysicalLine = fgets($probe);
        fclose($probe);

        if ($firstPhysicalLine === false || trim($firstPhysicalLine) === '') {
            $this->command?->error("CSV empty.");
            return;
        }

        $firstPhysicalLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstPhysicalLine);

        // Delimiter detekcija SAMO iz header reda
        $delimiter = (substr_count($firstPhysicalLine, ';') > substr_count($firstPhysicalLine, ',')) ? ';' : ',';

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->command?->error("Cannot open CSV: {$path}");
            return;
        }

        $firstRow = fgetcsv($handle, 0, $delimiter);
        if ($firstRow === false || !is_array($firstRow) || count($firstRow) === 0) {
            fclose($handle);
            $this->command?->error("CSV header missing.");
            return;
        }

        if (isset($firstRow[0])) {
            $firstRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $firstRow[0]);
        }

        $header = array_map(fn ($h) => trim((string) $h), $firstRow);

        $now = now();
        $insertedOrUpdated = 0;
        $cleanStart = true;

        $has = fn (string $col) => Schema::hasColumn('monasteries', $col);

        $HAS_LAT = $has('lat');
        $HAS_LNG = $has('lng');
        $HAS_LATITUDE = $has('latitude');
        $HAS_LONGITUDE = $has('longitude');
        $HAS_EPAR = $has('eparchy');
        $HAS_EPAR_ID = $has('eparchy_id');

        if ($cleanStart) {
            $driver = DB::getDriverName();

            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF;');
                DB::table('monasteries')->delete();
                DB::statement('PRAGMA foreign_keys = ON;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('monasteries')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }

        $getAny = function (array $assoc, array $keys): ?string {
            foreach ($keys as $k) {
                if (array_key_exists($k, $assoc)) {
                    $v = $assoc[$k];

                    if ($v === null) {
                        continue;
                    }

                    $v = trim((string) $v);
                    if ($v !== '') {
                        return $v;
                    }
                }
            }
            return null;
        };

        $toFloat = function (?string $v): ?float {
            if ($v === null) return null;
            $v = trim($v);
            if ($v === '') return null;
            $v = str_replace(',', '.', $v);
            return is_numeric($v) ? (float) $v : null;
        };

        $toBool = function (?string $v): ?int {
            if ($v === null) return null;
            $vv = strtolower(trim($v));
            if ($vv === '') return null;

            if (in_array($vv, ['1', 'true', 'yes', 'da'], true)) return 1;
            if (in_array($vv, ['0', 'false', 'no', 'ne'], true)) return 0;

            return null;
        };

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (!is_array($row)) {
                continue;
            }

            $nonEmpty = array_filter($row, fn ($x) => $x !== null && trim((string) $x) !== '');
            if (count($nonEmpty) === 0) {
                continue;
            }

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            } elseif (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }

            $assoc = [];
            foreach ($header as $j => $key) {
                $assoc[$key] = isset($row[$j]) ? trim((string) $row[$j]) : null;
            }

            $name = $getAny($assoc, ['name', 'naziv']);
            if (!$name) {
                continue;
            }

            $slug = $getAny($assoc, ['slug']) ?: Str::slug($name);

            $eparchyName = $getAny($assoc, ['eparchy', 'eparchy_name', 'eparhija']);
            $eparchyId = null;

            if ($eparchyName) {
                $eparchySlug = Str::slug($eparchyName);

                try {
                    $existing = DB::table('eparchies')->where('slug', $eparchySlug)->first();

                    if ($existing) {
                        $eparchyId = $existing->id;
                    } else {
                        $eparchyId = DB::table('eparchies')->insertGetId([
                            'name' => $eparchyName,
                            'slug' => $eparchySlug,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                } catch (\Throwable $e) {
                    $eparchyId = null;
                }
            }

            $region = $getAny($assoc, ['region', 'oblast', 'okrug']);
            $city = $getAny($assoc, ['city', 'mesto', 'grad']);

            $address = $getAny($assoc, ['address', 'adresa']);
            $phone = $getAny($assoc, ['phone', 'telefon']);
            $email = $getAny($assoc, ['email']);
            $website = $getAny($assoc, ['website', 'site', 'sajt']);

            $latitude = $toFloat($getAny($assoc, ['latitude', 'lat']));
            $longitude = $toFloat($getAny($assoc, ['longitude', 'lng']));

            $description = $getAny($assoc, ['description', 'opis', 'excerpt']);
            $imageUrl = $getAny($assoc, ['image_url', 'image']);
            $wikipediaUrl = $getAny($assoc, ['wikipedia_url', 'wikipedia', 'wiki', 'source_url']);
            $wikidataItem = $getAny($assoc, ['wikidata_item']);
            $wikidataQid = $getAny($assoc, ['wikidata_qid', 'qid']);

            $type = $getAny($assoc, ['type', 'tip']);
            $openingHours = $getAny($assoc, ['opening_hours', 'radno_vreme']);
            $history = $getAny($assoc, ['history', 'istorija']);
            $source = $getAny($assoc, ['source', 'izvor']);

            $reviewStatus = $getAny($assoc, ['review_status', 'status']) ?? 'pending';
            $isApproved = $toBool($getAny($assoc, ['is_approved'])) ?? 0;

            $isSpc = $toBool($getAny($assoc, ['is_spc']));
            $isSpcGuess = $toBool($getAny($assoc, ['is_spc_guess']));
            $religionQid = $getAny($assoc, ['religion_qid']);
            $denomQid = $getAny($assoc, ['denomination_qid']);

            $data = [
                'name' => $name,
                'slug' => $slug,
                'region' => $region,
                'city' => $city,
                'description' => $description,
                'type' => $type,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'website' => $website,
                'opening_hours' => $openingHours,
                'wikidata_item' => $wikidataItem,
                'wikidata_qid' => $wikidataQid,
                'wikipedia_url' => $wikipediaUrl,
                'history' => $history,
                'image_url' => $imageUrl,
                'source' => $source,
                'religion_qid' => $religionQid,
                'denomination_qid' => $denomQid,
                'is_spc' => $isSpc,
                'is_spc_guess' => $isSpcGuess,
                'review_status' => $reviewStatus,
                'is_approved' => $isApproved,
                'updated_at' => $now,
                'created_at' => $now,
            ];

            if ($HAS_LATITUDE) $data['latitude'] = $latitude;
            if ($HAS_LONGITUDE) $data['longitude'] = $longitude;
            if ($HAS_LAT) $data['lat'] = $latitude;
            if ($HAS_LNG) $data['lng'] = $longitude;

            if ($HAS_EPAR && $eparchyName) {
                $data['eparchy'] = $eparchyName;
            }

            if ($HAS_EPAR_ID) {
                $data['eparchy_id'] = $eparchyId;
            }

            DB::table('monasteries')->updateOrInsert(
                ['slug' => $slug],
                $data
            );

            $insertedOrUpdated++;
        }

        fclose($handle);

        $this->command?->info("Imported/updated monasteries: {$insertedOrUpdated}");
        $this->command?->info("CSV used: {$path} (delimiter: {$delimiter})");

        try {
            $eCount = DB::table('eparchies')->count();
            $this->command?->info("Eparchies in DB: {$eCount}");
        } catch (\Throwable $e) {
            //
        }
    }
}