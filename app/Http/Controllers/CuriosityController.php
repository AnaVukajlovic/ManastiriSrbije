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
            $terms = \App\Services\SearchService::getSearchTerms($q);
            $query->where(function ($qq) use ($terms) {
                foreach ($terms as $term) {
                    $qq->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('excerpt', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%");
                }
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
        $legendSlugs = [
            'legendarijum-price',
            'legendarijum',
            'legende',
            'nemanjici-price',
            'legende-nemanjici',
            'price-o-nemanjicima',
            'sveti-sava-price',
            'legendarijum-i-price',
        ];

        if (in_array($slug, $legendSlugs, true)) {
            return view('pages.pravoslavni.modules.curiosities.legendarijum-price');
        }

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

        return view('pages.pravoslavni.modules.curiosities.show', compact('item', 'more'));
    }
}