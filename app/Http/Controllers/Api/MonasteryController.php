<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Monastery;

class MonasteryController extends Controller
{
    /**
     * API lista manastira (paginacija).
     * Po potrebi možeš dodati filtere kasnije.
     */
    public function index(Request $request)
    {
        return response()->json(
            Monastery::query()
                ->with('profile')
                ->paginate(12)
        );
    }

    /**
     * API detalj (po slugu).
     * Važno: tvoja ruta je /monasteries/{slug}, zato ovde tražimo po slug-u.
     */
    public function show(string $slug)
    {
        $monastery = Monastery::query()
            ->where('slug', $slug)
            ->with('profile')
            ->firstOrFail();

        return response()->json($monastery);
    }

    /**
     * Regioni (korisno za filtere)
     */
    public function regions()
    {
        $regions = Monastery::query()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return response()->json($regions);
    }

    /**
     * Gradovi (korisno za filtere)
     */
    public function cities()
    {
        $cities = Monastery::query()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return response()->json($cities);
    }

    /**
     * Podaci za mapu.
     * Bitno: u bazi imaš latitude/longitude, a frontend očekuje lat/lng,
     * pa vraćamo lat/lng kao alias.
     */
    public function map(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $eparchy = trim((string) $request->query('eparchy', ''));

        $rows = Monastery::query()
            ->select([
                'id',
                'slug',
                'name',
                'eparchy',
                'city',
                'region',
                'image_url',
            ])
            // aliasi da frontend i map.js rade bez menjanja:
            ->selectRaw('latitude as lat, longitude as lng')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('city', 'like', "%{$q}%")
                        ->orWhere('region', 'like', "%{$q}%")
                        ->orWhere('eparchy', 'like', "%{$q}%");
                });
            })
            ->when($eparchy !== '', fn ($qq) => $qq->where('eparchy', $eparchy))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('name')
            ->limit(5000)
            ->get();

        return response()->json([
            'ok'    => true,
            'count' => $rows->count(),
            'items' => $rows,
        ]);
    }
}