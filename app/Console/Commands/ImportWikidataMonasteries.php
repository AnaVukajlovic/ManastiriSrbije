<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Monastery;
use App\Models\MonasteryImage;

class ImportWikidataMonasteries extends Command
{
    protected $signature = 'import:wikidata {--limit=3000} {--fresh}';
    protected $description = 'Import manastira/crkava iz Srbije sa Wikidata (SR latinica + 1 slika svuda)';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        if ($limit < 1) $limit = 300;
        if ($limit > 5000) $limit = 5000;

        if ($this->option('fresh')) {
            $this->warn("Brišem postojeće zapise (monasteries + monastery_images) ...");
            MonasteryImage::truncate();
            Monastery::truncate();
        }

        $this->info("Preuzimam podatke sa Wikidata (limit={$limit})...");

        $query = <<<SPARQL
SELECT ?item ?srLatnLabel ?srLabel ?srwikiTitle ?coord ?image ?religion WHERE {
  ?item wdt:P17 wd:Q403 .

  VALUES ?type { wd:Q44613 wd:Q16970 } .
  ?item wdt:P31/wdt:P279* ?type .

  OPTIONAL { ?item rdfs:label ?srLatnLabel FILTER(LANG(?srLatnLabel) = "sr-Latn") }
  OPTIONAL { ?item rdfs:label ?srLabel     FILTER(LANG(?srLabel)     = "sr") }

  OPTIONAL {
    ?article schema:about ?item ;
             schema:isPartOf <https://sr.wikipedia.org/> ;
             schema:name ?srwikiTitle .
  }

  OPTIONAL { ?item wdt:P625 ?coord. }
  OPTIONAL { ?item wdt:P18 ?image. }
  OPTIONAL { ?item wdt:P140 ?religion. }
}
LIMIT {$limit}
SPARQL;

        $url = 'https://query.wikidata.org/sparql';

        $res = Http::withHeaders([
                'User-Agent' => 'ManastiriSrbije/1.0 (contact: test@example.com)',
                'Accept' => 'application/sparql-results+json',
            ])
            ->timeout(60)
            ->retry(3, 2000)
            ->get($url, [
                'query' => $query,
                'format' => 'json',
            ]);

        if (!$res->ok()) {
            $this->error("Greška pri preuzimanju. HTTP: " . $res->status());
            $this->line(substr($res->body(), 0, 1200));
            return Command::FAILURE;
        }

        $payload = $res->json();
        $rows = $payload['results']['bindings'] ?? [];

        $this->info("Wikidata rows: " . count($rows));

        // Religija QIDs (grubo)
        $rejectIfReligionIn = [
            'Q43229', // Islam
            'Q9592',  // Catholic Church
            'Q93191', // Protestantism
            'Q748',   // Judaism
        ];

        $spcQid = 'Q188814';    // Serbian Orthodox Church (P140)
        $orthodoxQid = 'Q35032';// Eastern Orthodox Church (široko)

        $count = 0;
        $skippedNoSr = 0;
        $rejectedByReligion = 0;

        foreach ($rows as $r) {
            // Naziv (sr-Latn -> sr -> srwiki)
            $name = $r['srLatnLabel']['value'] ?? null;
            if (!$name) $name = $r['srLabel']['value'] ?? null;
            if (!$name) $name = $r['srwikiTitle']['value'] ?? null;

            if (!$name) { $skippedNoSr++; continue; }

            $name = trim($this->cyrToLat($name));
            $baseSlug = Str::slug($name);
            if ($baseSlug === '') continue;

            // QID
            $itemUrl = $r['item']['value'] ?? '';
            $qid = null;
            if ($itemUrl && preg_match('~/(Q\d+)$~', $itemUrl, $m)) {
                $qid = $m[1];
            }

            // garantuj jedinstven slug: dodaj QID (najstabilnije)
            $slug = $qid ? ($baseSlug . '-' . strtolower($qid)) : $baseSlug;


            // QID
            $itemUrl = $r['item']['value'] ?? '';
            $qid = null;
            if ($itemUrl && preg_match('~/(Q\d+)$~', $itemUrl, $m)) {
                $qid = $m[1];
            }

            // Religija QID
            $religionUrl = $r['religion']['value'] ?? null;
            $religionQid = null;
            if ($religionUrl && preg_match('~/(Q\d+)$~', $religionUrl, $mm)) {
                $religionQid = $mm[1];
            }

            // Odmah preskoči ako je jasno da nije SPC (ako je uopšte označeno)
            if ($religionQid && in_array($religionQid, $rejectIfReligionIn, true)) {
                $rejectedByReligion++;
                continue;
            }

            // Status/guess
            $reviewStatus = 'pending';
            $isSpcGuess = null;

            if ($religionQid === $spcQid) {
                $reviewStatus = 'approved';
                $isSpcGuess = 1;
            } elseif ($religionQid === $orthodoxQid) {
                $reviewStatus = 'pending';
                $isSpcGuess = 1;
            }

            // Koordinate
            $lat = null; $lng = null;
            if (isset($r['coord']['value'])) {
                if (preg_match('/Point\(([^ ]+) ([^ ]+)\)/', $r['coord']['value'], $m2)) {
                    $lng = (float) $m2[1];
                    $lat = (float) $m2[2];
                }
            }

            // Slika
            $imageUrl = $r['image']['value'] ?? null;
            if (!$imageUrl) $imageUrl = 'https://placehold.co/900x600?text=Manastiri+Srbije';

            $desc = 'Podaci preuzeti sa Wikidata/Wikipedia.';

            // Upis: primarni ključ je wikidata_qid ako postoji, fallback slug
            if ($qid) {
                $mon = Monastery::updateOrCreate(
                    ['wikidata_qid' => $qid],
                    [
                        'name' => $name,
                        'slug' => $slug,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'description' => $desc,
                        'review_status' => $reviewStatus,
                        'is_spc_guess' => $isSpcGuess,
                        'religion_qid' => $religionQid,
                    ]
                );
            } else {
                $mon = Monastery::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'description' => $desc,
                        'review_status' => $reviewStatus,
                        'is_spc_guess' => $isSpcGuess,
                        'religion_qid' => $religionQid,
                    ]
                );
            }

            // 1 slika
            $mon->images()->updateOrCreate(
                ['sort_order' => 1],
                ['url' => $imageUrl]
            );

            $count++;
        }

        $this->info("Uvezeno: {$count} objekata.");
        $this->warn("Preskočeno (nema srpski naziv ni srwiki): {$skippedNoSr}");
        $this->warn("Preskočeno (nije SPC po religiji): {$rejectedByReligion}");

        return Command::SUCCESS;
    }

    private function cyrToLat(string $text): string
    {
        $map = [
            'Љ'=>'Lj','Њ'=>'Nj','Џ'=>'Dž','Ђ'=>'Đ','Ј'=>'J','Ћ'=>'Ć','Ч'=>'Č','Ш'=>'Š','Ж'=>'Ž',
            'љ'=>'lj','њ'=>'nj','џ'=>'dž','ђ'=>'đ','ј'=>'j','ћ'=>'ć','ч'=>'č','ш'=>'š','ж'=>'ž',
            'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','З'=>'Z','И'=>'I','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
            'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C',
            'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','з'=>'z','и'=>'i','к'=>'k','л'=>'l','м'=>'m','н'=>'n',
            'о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
        ];
        return strtr($text, $map);
    }
}
