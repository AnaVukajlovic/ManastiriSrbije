<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Omiljeni manastiri (belongsToMany -> Monastery)
        $favorites = $user->favoriteMonasteries()
            ->with('eparchy')
            ->orderBy('name')
            ->paginate(24);

        // ✅ PREPORUKA: koristi pages/favorites/index.blade.php
        // return view('pages.favorites.index', compact('favorites'));

        // ✅ Ako ti je fajl zapravo resources/views/pages/favorites.blade.php:
        // return view('pages.favorites', compact('favorites'));

        // Pošto si ranije rekla da si prešla na folder favorites/index.blade.php,
        // ostavljam ovu varijantu kao default:
        return view('pages.favorites.index', compact('favorites'));
    }

    public function toggle(Request $request, Monastery $monastery)
    {
        $user = $request->user();

        // Samo status (bez dodavanja/uklanjanja)
        if ($request->boolean('onlyStatus')) {
            $favorited = $user->favoriteMonasteries()
                ->whereKey($monastery->getKey())
                ->exists();

            return response()->json(['favorited' => $favorited]);
        }

        $exists = $user->favoriteMonasteries()
            ->whereKey($monastery->getKey())
            ->exists();

        if ($exists) {
            $user->favoriteMonasteries()->detach($monastery->getKey());
            $favorited = false;
        } else {
            $user->favoriteMonasteries()->attach($monastery->getKey());
            $favorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back()->with(
            'status',
            $favorited ? 'Dodato u omiljene.' : 'Uklonjeno iz omiljenih.'
        );
    }
}