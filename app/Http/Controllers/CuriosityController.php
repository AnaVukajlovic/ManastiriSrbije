<?php

namespace App\Http\Controllers;

use App\Models\Curiosity;
use Illuminate\Http\Request;

class CuriosityController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));

        $query = Curiosity::query()->where('is_published', true);

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $items = $query
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categories = Curiosity::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

return view('pages.pravoslavni.modules.curiosities.index', compact('items', 'q', 'category', 'categories'));    }

    public function show(string $slug)
    {
        $item = Curiosity::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $more = Curiosity::query()
            ->where('is_published', true)
            ->where('id', '!=', $item->id)
            ->orderByDesc('id')
            ->limit(6)
            ->get();

return view('pages.pravoslavni.modules.curiosities.show', compact('item', 'more'));    }
}