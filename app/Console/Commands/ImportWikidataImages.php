<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Monastery;
use App\Services\WikidataService;

class ImportWikidataMonasteries extends Command
{
    protected $signature = 'wikidata:import-monasteries {--limit=200}';
    protected $description = 'Import monasteries/churches from Wikidata into monasteries table';

    public function handle(WikidataService $wd): int
    {
        $limit = (int)$this->option('limit');
        $rows = $wd->fetchSerbiaMonasteries($limit);

        $count = 0;
        foreach ($rows as $r) {
            $label = $r['itemLabel']['value'] ?? null;
            $itemUri = $r['item']['value'] ?? null;
            if (!$label || !$itemUri) continue;

            $qid = $wd->parseQid($itemUri);
            [$lat, $lng] = $wd->parseCoords($r['coord']['value'] ?? null);

            $imageUrl = $r['image']['value'] ?? null; // ovo je već URL do commons slike
            // Sačuvaćemo URL direktno u "image" (najjednostavnije),
            // a u view-u koristiš asset() samo za lokalne slike.
            $slug = Str::slug($label);

            $m = Monastery::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $label,
                    'wikidata_id' => $qid,
                    'lat' => $lat,
                    'lng' => $lng,
                    'image' => $imageUrl,    // URL ili null
                    'source_url' => $itemUri // link na Wikidata entitet
                ]
            );

            $count++;
        }

        $this->info("Imported/updated: {$count}");
        return self::SUCCESS;
    }
}