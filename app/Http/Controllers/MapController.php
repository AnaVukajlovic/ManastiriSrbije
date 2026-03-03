<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->query('q', ''));
        $region  = trim((string) $request->query('region', ''));
        $eparchy = trim((string) $request->query('eparchy', '')); // slug eparhije
        $onlyGeo = (int) $request->query('only_geo', 0) === 1;

        // radi i ako budeš kasnije punila lat/lng
        $latExpr = DB::raw('COALESCE(monasteries.latitude, monasteries.lat) as lat');
        $lngExpr = DB::raw('COALESCE(monasteries.longitude, monasteries.lng) as lng');

        $query = Monastery::query()
            ->leftJoin('eparchies', 'monasteries.eparchy_id', '=', 'eparchies.id')
            ->select([
                'monasteries.slug',
                'monasteries.name',
                'monasteries.region',
                'monasteries.city',
                'monasteries.image_url',
                'eparchies.name as eparchy_name',
                'eparchies.slug as eparchy_slug',
                $latExpr,
                $lngExpr,
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('monasteries.name', 'like', "%{$q}%")
                  ->orWhere('monasteries.city', 'like', "%{$q}%")
                  ->orWhere('monasteries.region', 'like', "%{$q}%")
                  ->orWhere('eparchies.name', 'like', "%{$q}%");
            });
        }

        if ($region !== '') {
            $query->where('monasteries.region', $region);
        }

        if ($eparchy !== '') {
            $query->where('eparchies.slug', $eparchy);
        }

        if ($onlyGeo) {
            $query->whereNotNull('monasteries.latitude')
                  ->whereNotNull('monasteries.longitude');
        }

        $regions = Monastery::query()
            ->whereNotNull('region')->where('region','!=','')
            ->distinct()->orderBy('region')->pluck('region')->values();

        $eparchies = DB::table('eparchies')
            ->select('name','slug')
            ->orderBy('name')->get();

        // ✅ UVEK vrati listu (bez limita) – imaš samo 268
        $monasteries = $query->orderBy('monasteries.name')->get();

        // points: samo oni sa koordinatama
        $points = $monasteries
            ->filter(fn($m) => is_numeric($m->lat) && is_numeric($m->lng))
            ->map(fn($m) => [
                'slug' => $m->slug,
                'name' => $m->name,
                'lat' => (float)$m->lat,
                'lng' => (float)$m->lng,
                'city' => $m->city,
                'region' => $m->region,
                'eparchy' => $m->eparchy_name,
            ])->values();

        $eparchyName = null;
        if ($eparchy !== '') {
            $ep = $eparchies->firstWhere('slug', $eparchy);
            $eparchyName = $ep?->name;
        }

        return view('map.index', [
            'q' => $q,
            'region' => $region,
            'regions' => $regions,
            'eparchy' => $eparchy,
            'eparchy_name' => $eparchyName,
            'eparchies' => $eparchies,
            'monasteries' => $monasteries,
            'points' => $points,
            'total' => $monasteries->count(),
            'geo_total' => $points->count(),
        ]);
    }

    public function show(string $slug)
    {
        return redirect()->route('monasteries.show', $slug);
    }
}