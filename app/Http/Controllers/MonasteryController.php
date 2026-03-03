<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use App\Models\Eparchy;
use Illuminate\Http\Request;

class MonasteryController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $region   = trim((string) $request->query('region', ''));
        $eparchy  = trim((string) $request->query('eparchy', '')); // ✅ NOVO (slug)
        $sort     = trim((string) $request->query('sort', 'popular'));

        // dropdown: regioni
        $regions = Monastery::query()
            ->whereNotNull('region')
            ->where('region', '<>', '')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->values();

        // dropdown: eparhije (iz tabele eparchies)
        $eparchies = Eparchy::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        // query
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

        // ✅ filter po eparhiji (slug)
        if ($eparchy !== '') {
            $query->whereHas('eparchy', function ($qq) use ($eparchy) {
                $qq->where('slug', $eparchy);
            });
        }

        // sortiranje
        if ($sort === 'name') {
            $query->orderBy('name');
        } elseif ($sort === 'new') {
            $query->orderByDesc('id');
        } else {
            $query->orderBy('name');
        }

        $monasteries = $query->paginate(24)->withQueryString();

        return view('pages.monasteries.index', compact(
            'monasteries',
            'regions',
            'eparchies',
            'q',
            'region',
            'eparchy',
            'sort'
        ));
    }

    public function show(string $slug)
    {
        $monastery = Monastery::query()
            ->with(['profile', 'eparchy'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.monasteries.show', compact('monastery'));
    }
}