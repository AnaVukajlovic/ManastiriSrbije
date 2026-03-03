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

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if (!$lines || count($lines) < 2) {
            $this->command?->error("CSV empty.");
            return;
        }

        // UTF-8 BOM remove
        $lines[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lines[0]);

        // delimiter detect
        $delimiter = (substr_count($lines[0], ';') > substr_count($lines[0], ',')) ? ';' : ',';

        $rows = array_map(fn ($line) => str_getcsv($line, $delimiter), $lines);
        $rows = array_values(array_filter($rows, function ($r) {
            if (!is_array($r)) return false;
            $nonEmpty = array_filter($r, fn($x) => $x !== null && trim((string)$x) !== '');
            return count($nonEmpty) > 0;
        }));

        if (count($rows) < 2) {
            $this->command?->error("CSV empty after parsing.");
            return;
        }

        $header = array_map(fn($h) => trim((string)$h), $rows[0]);

        $now = now();
        $insertedOrUpdated = 0;

        // Ako hoćeš update bez brisanja: stavi false
        $cleanStart = true;

        // Provera kolona (da se seeder prilagodi tvojoj šemi)
        $has = fn(string $col) => Schema::hasColumn('monasteries', $col);

        $HAS_LAT = $has('lat');
        $HAS_LNG = $has('lng');
        $HAS_LATITUDE = $has('latitude');
        $HAS_LONGITUDE = $has('longitude');

        $HAS_EPAR = $has('eparchy');      // string kolona (ako postoji)
        $HAS_EPAR_ID = $has('eparchy_id'); // FK kolona (kod tebe postoji)

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

        // Helper: uzmi vrednost iz CSV po mogućim ključevima
        $getAny = function(array $assoc, array $keys): ?string {
            foreach ($keys as $k) {
                if (array_key_exists($k, $assoc)) {
                    $v = trim((string)($assoc[$k] ?? ''));
                    if ($v !== '') return $v;
                }
            }
            return null;
        };

        // Helper: float
        $toFloat = function(?string $v): ?float {
            if ($v === null) return null;
            $v = trim($v);
            if ($v === '') return null;
            $v = str_replace(',', '.', $v);
            return is_numeric($v) ? (float)$v : null;
        };

        // Helper: bool-ish
        $toBool = function(?string $v): ?int {
            if ($v === null) return null;
            $vv = strtolower(trim($v));
            if ($vv === '') return null;
            if (in_array($vv, ['1','true','yes','da'], true)) return 1;
            if (in_array($vv, ['0','false','no','ne'], true)) return 0;
            return null;
        };

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (!is_array($row) || count($row) === 0) continue;

            // assoc from header
            $assoc = [];
            foreach ($header as $j => $key) {
                $assoc[$key] = isset($row[$j]) ? trim((string)$row[$j]) : null;
            }

            $name = $getAny($assoc, ['name', 'naziv']);
            if (!$name) continue;

            $slug = $getAny($assoc, ['slug']);
            $slug = $slug ? $slug : Str::slug($name);

            // --- EPARHIJA ---
            // u CSV: eparchy ili eparchy_name ili eparhija
            $eparchyName = $getAny($assoc, ['eparchy', 'eparchy_name', 'eparhija']);
            $eparchyId = null;

            if ($eparchyName) {
                $eparchySlug = Str::slug($eparchyName);

                // tabela eparchies mora postojati ako koristiš eparchy_id
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
                    // Ako nema tabele eparchies, samo preskoči id
                    $eparchyId = null;
                }
            }

            // mapiranja (podržava i stare CSV nazive)
            $region = $getAny($assoc, ['region', 'oblast', 'okrug']);
            $city   = $getAny($assoc, ['city', 'mesto', 'grad']);

            $address = $getAny($assoc, ['address', 'adresa']);
            $phone   = $getAny($assoc, ['phone', 'telefon']);
            $email   = $getAny($assoc, ['email']);
            $website = $getAny($assoc, ['website', 'site', 'sajt']);

            // koordinate (CSV može: lat/lng ili latitude/longitude)
            $latitude  = $toFloat($getAny($assoc, ['latitude', 'lat']));
            $longitude = $toFloat($getAny($assoc, ['longitude', 'lng']));

            // opis: description ili excerpt
            $description = $getAny($assoc, ['description', 'opis', 'excerpt']);

            // image: image_url ili image
            $imageUrl = $getAny($assoc, ['image_url', 'image']);

            // izvori
            $wikipediaUrl = $getAny($assoc, ['wikipedia_url', 'wiki', 'source_url']);
            $wikidataItem = $getAny($assoc, ['wikidata_item']);
            $wikidataQid  = $getAny($assoc, ['wikidata_qid', 'qid']);

            $type = $getAny($assoc, ['type', 'tip']);
            $openingHours = $getAny($assoc, ['opening_hours', 'radno_vreme']);

            $history = $getAny($assoc, ['history', 'istorija']);

            $source = $getAny($assoc, ['source', 'izvor']);

            // status/moderacija
            $reviewStatus = $getAny($assoc, ['review_status']) ?? 'pending';
            $isApproved   = $toBool($getAny($assoc, ['is_approved'])) ?? 0;

            // spc heuristika (opciono)
            $isSpc       = $toBool($getAny($assoc, ['is_spc']));
            $isSpcGuess  = $toBool($getAny($assoc, ['is_spc_guess']));
            $religionQid = $getAny($assoc, ['religion_qid']);
            $denomQid    = $getAny($assoc, ['denomination_qid']);

            // Payload za monasteries (u skladu sa postojećim kolonama)
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
            ];

            // created_at samo ako prvi put insertujemo (najbezbolnije ovako)
            // updateOrInsert ne zna da razlikuje insert/update, pa stavljamo i created_at uvek (kao ranije),
            // ali ako želiš da čuva created_at, reci pa prebacimo na upsert.
            $data['created_at'] = $now;

            // ✅ koordinate - upiši u kolone koje postoje
            if ($HAS_LATITUDE)  $data['latitude']  = $latitude;
            if ($HAS_LONGITUDE) $data['longitude'] = $longitude;
            if ($HAS_LAT)       $data['lat']       = $latitude;
            if ($HAS_LNG)       $data['lng']       = $longitude;

            // ✅ eparhija - upiši string ako postoji, a FK ako postoji
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

        $this->command?->info("Imported/updated monasteries: {$insertedOrUpdated}");
        $this->command?->info("CSV used: {$path} (delimiter: {$delimiter})");

        try {
            $eCount = DB::table('eparchies')->count();
            $this->command?->info("Eparchies in DB: {$eCount}");
        } catch (\Throwable $e) {
            // ignore
        }
    }
}