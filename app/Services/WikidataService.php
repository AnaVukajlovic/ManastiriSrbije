<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WikidataService
{
    /**
     * Vrati rezultate (QID, label, coords, Commons image) za manastire
     * koji su "monastery" (Q44613) ili "church building" (Q16970)
     * i nalaze se na teritoriji Srbije (Q403).
     */
    public function fetchSerbiaMonasteries(int $limit = 200): array
    {
        $sparql = <<<SPARQL
SELECT ?item ?itemLabel ?coord ?image WHERE {
  VALUES ?type { wd:Q44613 wd:Q16970 }     # monastery, church building
  ?item wdt:P31 ?type .
  ?item wdt:P17 wd:Q403 .                  # Serbia

  OPTIONAL { ?item wdt:P625 ?coord . }
  OPTIONAL { ?item wdt:P18 ?image . }

  SERVICE wikibase:label { bd:serviceParam wikibase:language "sr,en". }
}
LIMIT $limit
SPARQL;

       $res = Http::retry(3, 800)          // 3 pokušaja, pauza 0.8s
    ->timeout(60)                   // veći timeout
    ->connectTimeout(15)
    ->withHeaders([
        'Accept' => 'application/sparql-results+json',
        'User-Agent' => 'PravoslavniSvetionik/1.0 (ana-local; contact: none)'
    ])
    ->get('https://query.wikidata.org/sparql', [
        'format' => 'json',
        'query'  => $sparql,
    ]);

        if (!$res->ok()) {
            throw new \RuntimeException("Wikidata SPARQL error: ".$res->status());
        }

        return $res->json()['results']['bindings'] ?? [];
    }

    public function parseQid(string $uri): ?string
    {
        // https://www.wikidata.org/entity/Q123 -> Q123
        if (preg_match('~/(Q\d+)$~', $uri, $m)) return $m[1];
        return null;
    }

    public function parseCoords(?string $wkt): array
    {
        // WKT: Point(LON LAT)
        if (!$wkt) return [null, null];
        if (preg_match('~Point\(([-0-9.]+)\s+([-0-9.]+)\)~', $wkt, $m)) {
            return [(float)$m[2], (float)$m[1]]; // lat, lng
        }
        return [null, null];
    }
}