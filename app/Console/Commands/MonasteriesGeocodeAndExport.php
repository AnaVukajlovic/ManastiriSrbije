<?php

namespace App\Console\Commands;

use App\Models\Monastery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MonasteriesGeocodeAndExport extends Command
{
    protected $signature = 'monasteries:geocode-export
        {--only-missing : Popunjava samo manastire bez koordinata}
        {--sleep=1100 : Pauza između upita u ms (Nominatim policy)}
        {--export=storage/app/import/monasteries_with_coords.csv : Putanja za export CSV}';

    protected $description = 'Geokodira manastire preko OSM Nominatim (bez izmišljanja) i eksportuje CSV sa latitude/longitude.';

    public function handle(): int
    {
        $onlyMissing = (bool) $this->option('only-missing');
        $sleepMs = (int) $this->option('sleep');
        $exportPath = base_path($this->option('export'));

        // 1) uzmi manastire
        $q = Monastery::query();

        if ($onlyMissing) {
            $q->where(function ($w) {
                $w->whereNull('latitude')->orWhereNull('longitude');
            });
        }

        $items = $q->orderBy('id')->get();

        if ($items->isEmpty()) {
            $this->info('Nema manastira za obradu.');
            return self::SUCCESS;
        }

        $this->info("Ukupno za obradu: {$items->count()}");

        // 2) geokodiranje
        $updated = 0;
        $skipped = 0;

        foreach ($items as $m) {
            $name = trim((string)$m->name);
            $city = trim((string)($m->city ?? ''));

            // Ako već ima koordinate i only-missing=1, preskoči
            if ($onlyMissing && $m->latitude !== null && $m->longitude !== null) {
                $skipped++;
                continue;
            }

            // normalizuj naziv za pretragu
            $baseName = preg_replace('/^Manastir\s+/iu', '', $name);
            $baseName = trim((string)$baseName);

            // više pokušaja (bez izmišljanja)
            $queries = [];

            if ($city !== '' && Str::lower($city) !== 'nepoznato') {
                $queries[] = "Manastir {$baseName}, {$city}, Srbija";
            }
            $queries[] = "Manastir {$baseName}, Srbija";
            $queries[] = "{$baseName} manastir, Srbija";

            $found = null;

            foreach ($queries as $queryStr) {
                $resp = Http::withHeaders([
                        // važan user-agent (Nominatim to traži)
                        'User-Agent' => 'PravoslavniSvetionik/1.0 (local dev)',
                        'Accept-Language' => 'sr,en;q=0.8',
                    ])
                    ->timeout(30)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $queryStr,
                        'format' => 'json',
                        'addressdetails' => 1,
                        'limit' => 5,
                    ]);

                if (!$resp->ok()) {
                    usleep($sleepMs * 1000);
                    continue;
                }

                $arr = $resp->json();
                if (!is_array($arr) || count($arr) === 0) {
                    usleep($sleepMs * 1000);
                    continue;
                }

                // uzmi prvi "najbolji" rezultat koji liči na manastir/crkvu
                foreach ($arr as $hit) {
                    $class = (string)($hit['class'] ?? '');
                    $type  = (string)($hit['type'] ?? '');
                    $disp  = (string)($hit['display_name'] ?? '');
                    $lat   = $hit['lat'] ?? null;
                    $lon   = $hit['lon'] ?? null;

                    if (!$lat || !$lon) continue;

                    // minimalna sigurnost:
                    // - da je u Srbiji po display_name ili address
                    $country = (string)($hit['address']['country'] ?? '');
                    $okCountry =
                        Str::contains(Str::lower($disp), 'serbia') ||
                        Str::contains(Str::lower($disp), 'srbija') ||
                        Str::contains(Str::lower($country), 'serbia') ||
                        Str::contains(Str::lower($country), 'srbija');

                    if (!$okCountry) continue;

                    // - da je religijski objekat (amenity/place_of_worship, tourism/attraction, itd)
                    $looksReligious =
                        ($class === 'amenity' && in_array($type, ['place_of_worship', 'monastery', 'church'], true)) ||
                        ($class === 'tourism' && in_array($type, ['attraction', 'museum'], true)) ||
                        in_array($type, ['monastery', 'place_of_worship', 'church'], true);

                    if (!$looksReligious) continue;

                    $found = [
                        'lat' => (float)$lat,
                        'lon' => (float)$lon,
                        'display' => $disp,
                        'query_used' => $queryStr,
                    ];
                    break 2;
                }

                usleep($sleepMs * 1000);
            }

            if ($found) {
                $m->latitude = $found['lat'];
                $m->longitude = $found['lon'];
                $m->save();

                $updated++;
                $this->line("OK: {$name} => {$found['lat']},{$found['lon']}");
            } else {
                // ne diramo, ostaje null (bez izmišljanja)
                $this->warn("NEMA: {$name} (nije nađen pouzdano)");
            }

            usleep($sleepMs * 1000);
        }

        $this->info("Geokodiranje završeno. Updated: {$updated}, Skipped: {$skipped}");

        // 3) export CSV (uvek exportuje celu tabelu)
        $all = Monastery::query()->orderBy('id')->get();

        // Kolone koje tvoj seeder razume + koordinate
        $cols = [
            'slug','name','region','city','description_short','lat','lng','status','eparchy',
            'description','image_url','wikipedia_url','source'
        ];

        // napravi CSV
        $dir = dirname($exportPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $fp = fopen($exportPath, 'w');
        if (!$fp) {
            $this->error("Ne mogu da upišem export: {$exportPath}");
            return self::FAILURE;
        }

        // header
        fputcsv($fp, $cols, ';');

        foreach ($all as $m) {
            // eparhija name iz relacije (ako postoji)
            $epName = $m->eparchy?->name ?? '';

            $row = [
                $m->slug,
                $m->name,
                $m->region ?? '',
                $m->city ?? '',
                $m->excerpt ?? ($m->description_short ?? ''),
                $m->latitude ?? '',
                $m->longitude ?? '',
                'active',
                $epName,
                $m->description ?? '',
                $m->image_url ?? '',
                $m->wikipedia_url ?? '',
                $m->source ?? '',
            ];

            // obavezno ; delimiter
            fputcsv($fp, $row, ';');
        }

        fclose($fp);

        $this->info("Export gotov: {$exportPath}");
        return self::SUCCESS;
    }
}