<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $region = trim((string) $request->query('region', ''));
        $eparchy = trim((string) $request->query('eparchy', ''));

        $query = Monastery::query()
            ->select([
                'id',
                'slug',
                'name',
                'region',
                'city',
                'image',
                'image_url',
                'eparchy_id',
                'latitude',
                'longitude',
                'lat',
                'lng',
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('city', 'like', "%{$q}%")
                  ->orWhere('region', 'like', "%{$q}%");
            });
        }

        if ($region !== '') {
            $query->where('region', $region);
        }

        if ($eparchy !== '') {
            $epId = DB::table('eparchies')
                ->where('slug', $eparchy)
                ->value('id');

            if ($epId) {
                $query->where('eparchy_id', $epId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $monasteries = $query
            ->orderBy('name')
            ->get();

        $eparchyMap = DB::table('eparchies')
            ->pluck('name', 'id');

        $regions = Monastery::query()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->values();

        $eparchies = DB::table('eparchies')
            ->select('name', 'slug')
            ->orderBy('name')
            ->get();

        $eparchyName = null;
        if ($eparchy !== '') {
            $eparchyName = $eparchies->firstWhere('slug', $eparchy)?->name;
        }

        $points = $monasteries->map(function ($m) use ($eparchyMap) {
            $latRaw = $m->latitude ?? $m->lat ?? null;
            $lngRaw = $m->longitude ?? $m->lng ?? null;

            $lat = is_string($latRaw) ? str_replace(',', '.', trim($latRaw)) : $latRaw;
            $lng = is_string($lngRaw) ? str_replace(',', '.', trim($lngRaw)) : $lngRaw;

            if ($lat === null || $lng === null || $lat === '' || $lng === '') {
                return null;
            }

            if (!is_numeric($lat) || !is_numeric($lng)) {
                return null;
            }

            $lat = (float) $lat;
            $lng = (float) $lng;

            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                return null;
            }

            return [
                'slug' => $m->slug,
                'name' => $m->name,
                'lat' => $lat,
                'lng' => $lng,
                'city' => $m->city,
                'region' => $m->region,
                'eparchy' => $m->eparchy_id ? ($eparchyMap[$m->eparchy_id] ?? null) : null,
            ];
        })->filter()->values();

        $geoTotal = $points->count();

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
            'geo_total' => $geoTotal,
        ]);
    }

    public function show(string $slug)
    {
        return redirect()->route('monasteries.show', $slug);
    }
}