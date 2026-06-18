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
        $query = Monastery::query()->with('eparchy');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('city', 'like', "%{$q}%")
                   ->orWhere('region', 'like', "%{$q}%");
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

        // Sortiranje
        if ($sort === 'name') {
            $query->orderBy('name');
        } elseif ($sort === 'new') {
            $query->orderByDesc('id');
        } else {
            $query->orderBy('name');
        }

        $monasteries = $query->paginate(24)->withQueryString();

        return view('pages.monasteries.index', compact(
            'monasteries', 'regions', 'eparchies', 'q', 'region', 'eparchy', 'sort'
        ));
    }

    public function show(string $slug)
    {
        // Koristi findOrFail za sigurnost
        return view('pages.monasteries.show', [
            'monastery' => Monastery::with(['profile', 'eparchy'])
                ->where('slug', $slug)
                ->firstOrFail()
        ]);
    }
}