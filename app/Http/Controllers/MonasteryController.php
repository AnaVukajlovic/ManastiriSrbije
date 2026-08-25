<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use App\Models\Eparchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonasteryController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $region   = trim((string) $request->query('region', ''));
        $eparchy  = trim((string) $request->query('eparchy', ''));
        $sort     = trim((string) $request->query('sort', 'popular'));

        // 1. Dropdown: regioni (bez duplikata i praznih polja)
        $regions = Monastery::query()
            ->whereNotNull('region')
            ->where('region', '<>', '')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        // 2. Dropdown: eparhije (rešavamo duplikate i enkodiranje direktno iz baze)
        // Koristimo raw query ako treba da "nateramo" bazu da pravilno čita UTF-8
        $eparchies = Eparchy::query()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get()
            ->unique('name'); // Laravel će ovo očistiti u memoriji ako baza i dalje vraća duplikate

        // 3. Query
        $query = Monastery::query()->with(['eparchy', 'ktitori']);

        if ($q !== '') {
            $terms = \App\Services\SearchService::getSearchTerms($q);
            $query->where(function ($qq) use ($terms) {
                foreach ($terms as $term) {
                    $qq->orWhere('name', 'like', "%{$term}%")
                       ->orWhere('slug', 'like', "%{$term}%")
                       ->orWhere('city', 'like', "%{$term}%")
                       ->orWhere('region', 'like', "%{$term}%")
                       ->orWhere('ktitor', 'like', "%{$term}%")
                       ->orWhere('godina_izgradnje', 'like', "%{$term}%")
                       ->orWhereHas('eparchy', function ($eq) use ($term) {
                           $eq->where('name', 'like', "%{$term}%")
                              ->orWhere('slug', 'like', "%{$term}%");
                       })
                       ->orWhereHas('ktitori', function ($kq) use ($term) {
                           $kq->where('name', 'like', "%{$term}%");
                       });
                }
            });
        }

        if ($region !== '') {
            $query->where('region', $region);
        }

        if ($eparchy !== '') {
            $query->whereHas('eparchy', function ($qq) use ($eparchy) {
                $qq->where('slug', $eparchy);
            });
        }

        // Sortiranje:
        if ($q !== '') {
            $qNorm = mb_strtolower(\App\Services\SearchService::stripDiacritics(\App\Services\SearchService::cyrToLat($q)));
            $safeQ = addslashes($qNorm);
            $query->orderByRaw("
                CASE 
                    WHEN slug = '{$safeQ}' THEN 1
                    WHEN name LIKE '%{$safeQ}%' THEN 2
                    WHEN slug LIKE '%{$safeQ}%' THEN 3
                    WHEN city LIKE '%{$safeQ}%' THEN 4
                    WHEN ktitor LIKE '%{$safeQ}%' THEN 5
                    ELSE 6
                END ASC, name ASC
            ");
        } elseif ($sort === 'popular') {
            // Preporučeno: najznačajniji srednjovekovni manastiri, UNESCO baština i zadužbine Nemanjića
            $famous = [
                'studenica', 'zica', 'visoki-decani', 'gracanica', 'manasija', 'ravanica', 
                'bogorodica-ljeviska', 'mileseva', 'pecka-patrijarsija', 'sopocani', 'tumane', 
                'krusedol', 'djurdjevi-stupovi', 'ljubostinja', 'banjska', 'novo-hopovo', 
                'kovilj', 'moraca', 'tronosa', 'lelic', 'celije-valjevska', 'gornjak'
            ];
            $slugsSql = "'" . implode("','", $famous) . "'";
            $query->orderByRaw("
                CASE 
                    WHEN slug IN ({$slugsSql}) THEN 1
                    WHEN image_url IS NOT NULL AND image_url != '' THEN 2
                    ELSE 3
                END ASC, 
                CASE 
                    WHEN slug = 'studenica' THEN 1
                    WHEN slug = 'zica' THEN 2
                    WHEN slug = 'visoki-decani' THEN 3
                    WHEN slug = 'gracanica' THEN 4
                    WHEN slug = 'bogorodica-ljeviska' THEN 5
                    WHEN slug = 'manasija' THEN 6
                    WHEN slug = 'ravanica' THEN 7
                    WHEN slug = 'mileseva' THEN 8
                    WHEN slug = 'pecka-patrijarsija' THEN 9
                    WHEN slug = 'sopocani' THEN 10
                    WHEN slug = 'tumane' THEN 11
                    ELSE 12
                END ASC, name ASC
            ");
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'new') {
            $query->orderByDesc('id');
        } else {
            $query->orderBy('name', 'asc');
        }

        $monasteries = $query->paginate(24)->withQueryString();

        return view('pages.monasteries.index', compact(
            'monasteries', 'regions', 'eparchies', 'q', 'region', 'eparchy', 'sort'
        ));
    }

    public function show(string $slug)
    {
        // Koristi findOrFail za sigurnost i učitava profile, eparhiju, slike i ktitore
        return view('pages.monasteries.show', [
            'monastery' => Monastery::with(['profile', 'eparchy', 'images', 'ktitori'])
                ->where('slug', $slug)
                ->firstOrFail()
        ]);
    }
}