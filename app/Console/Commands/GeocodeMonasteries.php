<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Monastery;
use Illuminate\Support\Facades\Http;

class GeocodeMonasteries extends Command
{
    // Dodao sam opciju --countries (default: rs,xk) i --no-countrycodes ako želiš potpuno bez restrikcije
    protected $signature = 'monasteries:geocode
        {--limit=0}
        {--sleep=1}
        {--force=0}
        {--countries=rs,xk}
        {--no-countrycodes=0}';

    protected $description = 'Popunjava latitude/longitude za manastire preko OpenStreetMap Nominatim (Srbija + Kosovo, sa fallback upitima).';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $sleep = max(0, (int) $this->option('sleep'));
        $force = (int) $this->option('force') === 1;

        $countriesOpt = trim((string) $this->option('countries'));
        $allowedCountries = array_values(array_unique(array_filter(array_map('trim', explode(',', strtolower($countriesOpt))))));
        if (count($allowedCountries) === 0) $allowedCountries = ['rs', 'xk'];

        $noCountrycodes = (int) $this->option('no-countrycodes') === 1;

        $q = Monastery::query()
            ->when(!$force, function ($qq) {
                $qq->where(function ($w) {
                    $w->whereNull('latitude')->orWhereNull('longitude');
                });
            });

        if ($limit > 0) $q->limit($limit);

        $rows = $q->get();

        $this->info("Za geokodiranje: {$rows->count()} (sleep={$sleep}s, force=" . ($force ? '1' : '0') . ", countries=" . implode(',', $allowedCountries) . ", no-countrycodes=" . ($noCountrycodes ? '1' : '0') . ")");

        foreach ($rows as $m) {
            $name = trim((string) $m->name);
            if ($name === '') {
                $this->warn("Preskačem ID={$m->id} (prazno ime)");
                continue;
            }

            // očisti "Nepoznato"
            $city = trim((string) ($m->city ?? ''));
            if (mb_strtolower($city) === 'nepoznato') $city = '';

            $region = trim((string) ($m->region ?? ''));
            if (mb_strtolower($region) === 'nepoznato') $region = '';

            $eparchy = trim((string) ($m->eparchy ?? ''));
            if (mb_strtolower($eparchy) === 'nepoznato') $eparchy = '';

            // naziv bez prefiksa "Manastir "
            $nameNoPrefix = preg_replace('/^\s*manastir\s+/iu', '', $name);

            // Države/oblast u tekstu upita (fallback varijante)
            // Redosled je bitan: prvo SRB, zatim Kosovo varijante, pa bez države.
            $placeHints = [
                'Srbija',
                'Kosovo',
                'Kosovo i Metohija',
                'Kosovo and Metohija',
                '', // bez države kao poslednja šansa
            ];

            // pravimo listu "pametnih" upita (fallback)
            $queries = [];

            foreach ($placeHints as $hint) {
                // 1) manastir + ime + (grad/region) + hint
                $queries[] = trim(implode(' ', array_filter(['Manastir', $nameNoPrefix, $city, $region, $hint])));

                // 2) bez "Manastir"
                $queries[] = trim(implode(' ', array_filter([$nameNoPrefix, $city, $region, $hint])));

                // 3) manastir + ime + hint
                $queries[] = trim(implode(' ', array_filter(['Manastir', $nameNoPrefix, $hint])));

                // 4) samo ime + hint
                $queries[] = trim(implode(' ', array_filter([$nameNoPrefix, $hint])));

                // 5) eparhija pomaže
                if ($eparchy !== '') {
                    $queries[] = trim(implode(' ', array_filter(['Manastir', $nameNoPrefix, $eparchy, $hint])));
                }
            }

            // ukloni duplikate/prazno
            $queries = array_values(array_unique(array_filter($queries)));

            $found = null;

            foreach ($queries as $query) {
                $this->line("Tražim: {$query}");

                try {
                    $params = [
                        'q' => $query,
                        'format' => 'jsonv2',
                        'limit' => 5,
                        'addressdetails' => 1,
                        'accept-language' => 'sr',
                    ];

                    // Ako NE želiš restrikciju, možeš pokrenuti sa --no-countrycodes=1
                    // Inače, šaljemo "rs,xk" (ili šta god upišeš u --countries)
                    if (!$noCountrycodes) {
                        $params['countrycodes'] = implode(',', $allowedCountries);
                    }
// 0) Overpass (precizniji od Nominatim)
$op = $this->overpassFind($nameNoPrefix, $city ?: null);
if ($op) {
    $m->latitude = $op['lat'];
    $m->longitude = $op['lon'];
    $m->save();

    $tn = $op['tags']['name'] ?? '';
    $this->info("✔ Overpass: {$m->name} ({$m->latitude}, {$m->longitude})" . ($tn ? " ↳ {$tn}" : ""));
    if ($sleep > 0) sleep($sleep);
    continue;
}
                    $resp = Http::withHeaders([
                            'User-Agent' => 'PravoslavniSvetionik/1.0 (local dev)'
                        ])
                        ->timeout(25)
                        ->get('https://nominatim.openstreetmap.org/search', $params);

                    $data = $resp->json();

                    if (is_array($data) && count($data) > 0) {
                        // Uzmi prvi koji ima lat/lon i (ako postoji) odgovara dozvoljenim country_code
                        foreach ($data as $hit) {
                            if (empty($hit['lat']) || empty($hit['lon'])) continue;

                            $cc = strtolower((string) data_get($hit, 'address.country_code', ''));
                            // Nominatim nekad za Kosovo vrati rs ili xk, zavisi od objekta i mapiranja.
                            // Ako cc ne postoji, pusti (da ne ubijemo dobre rezultate).
                            if ($cc !== '' && !$noCountrycodes && !in_array($cc, $allowedCountries, true)) {
                                continue;
                            }

                            $found = $hit;
                            break;
                        }

                        // Ako ništa nije prošlo filter, kao poslednju šansu uzmi prvi sa lat/lon
                        if (!$found) {
                            foreach ($data as $hit) {
                                if (!empty($hit['lat']) && !empty($hit['lon'])) {
                                    $found = $hit;
                                    break;
                                }
                            }
                        }
                    }

                    if ($found) break;

                } catch (\Throwable $e) {
                    $this->warn("Greška upita: {$e->getMessage()}");
                }

                if ($sleep > 0) sleep($sleep);
            }

            if ($found) {
                $m->latitude  = (float) $found['lat'];
                $m->longitude = (float) $found['lon'];
                $m->save();

                $disp = (string) ($found['display_name'] ?? '');
                $cc = strtolower((string) data_get($found, 'address.country_code', ''));
                $this->info("✔ Upisano: {$m->name} ({$m->latitude}, {$m->longitude})" . ($cc ? " [{$cc}]" : ""));
                if ($disp) $this->line("   ↳ {$disp}");
            } else {
                $this->warn("✘ Nije pronađeno: {$m->name}");
            }

            if ($sleep > 0) sleep($sleep);
        }

        $this->info("Gotovo.");
        return 0;
    }


    private function overpassFind(string $name, ?string $city = null): ?array
{
    // Bounding box za Srbiju + Kosovo (približno): south,west,north,east
    $bbox = "41.8,18.5,46.3,23.6";

    $nameEsc = addslashes($name);
    $cityEsc = $city ? addslashes($city) : null;

    // Tražimo OSM objekte koji liče na manastir (amenity/historic + orthodox)
    // i imaju ime koje se poklapa (sr/latinica/ćirilica varira, ali "name" često postoji).
    $q = <<<QL
[out:json][timeout:25];
(
  n["amenity"="place_of_worship"]["religion"="christian"]["denomination"~"orthodox|serbian",i]["name"~"$nameEsc",i]($bbox);
  w["amenity"="place_of_worship"]["religion"="christian"]["denomination"~"orthodox|serbian",i]["name"~"$nameEsc",i]($bbox);
  r["amenity"="place_of_worship"]["religion"="christian"]["denomination"~"orthodox|serbian",i]["name"~"$nameEsc",i]($bbox);

  n["historic"="monastery"]["name"~"$nameEsc",i]($bbox);
  w["historic"="monastery"]["name"~"$nameEsc",i]($bbox);
  r["historic"="monastery"]["name"~"$nameEsc",i]($bbox);
);
out center tags;
QL;

    try {
        $resp = Http::withHeaders([
                'User-Agent' => 'PravoslavniSvetionik/1.0 (local dev)'
            ])
            ->timeout(35)
            ->post('https://overpass-api.de/api/interpreter', $q);

        $data = $resp->json();
        if (!is_array($data) || empty($data['elements'])) return null;

        // Izaberi najbolji pogodak: preferiraj "monastery" / "historic=monastery"
        $best = null;
        foreach ($data['elements'] as $el) {
            $tags = $el['tags'] ?? [];
            $type = strtolower((string)($tags['historic'] ?? $tags['amenity'] ?? ''));

            $lat = $el['lat'] ?? ($el['center']['lat'] ?? null);
            $lon = $el['lon'] ?? ($el['center']['lon'] ?? null);

            if ($lat === null || $lon === null) continue;

            $score = 0;
            if (($tags['historic'] ?? '') === 'monastery') $score += 50;
            if (($tags['amenity'] ?? '') === 'place_of_worship') $score += 15;
            if (isset($tags['denomination']) && preg_match('/orthodox|serbian/i', $tags['denomination'])) $score += 15;
            if (isset($tags['name']) && preg_match('/' . preg_quote($name, '/') . '/i', $tags['name'])) $score += 10;

            // Mali boost ako se pominje grad u name/addr ako postoji
            if ($cityEsc) {
                $hay = json_encode($tags, JSON_UNESCAPED_UNICODE);
                if (stripos($hay, $cityEsc) !== false) $score += 5;
            }

            if (!$best || $score > $best['score']) {
                $best = [
                    'lat' => (float)$lat,
                    'lon' => (float)$lon,
                    'tags' => $tags,
                    'score' => $score,
                ];
            }
        }

        return $best;
    } catch (\Throwable $e) {
        return null;
    }
}

private function normalizeName(string $name): string
{
    $name = trim($name);
    $name = preg_replace('/^\s*manastir\s+/iu', '', $name);
    return trim($name);
}
}