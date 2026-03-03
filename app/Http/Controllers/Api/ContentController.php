<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\ContentItem;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function categories(Request $request)
    {
        $module = (string) $request->query('module', 'orthodox');

        $cats = ContentCategory::query()
            ->where('module', $module)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id','module','title','slug','description','sort_order']);

        return response()->json(['data' => $cats]);
    }

    public function items(string $categorySlug)
    {
        $cat = ContentCategory::where('slug', $categorySlug)->firstOrFail();

        $items = ContentItem::where('category_id', $cat->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id','category_id','title','slug','excerpt','body','source_url','sort_order']);

        return response()->json(['data' => $items, 'category' => $cat]);
    }

    public function show(string $categorySlug, string $itemSlug)
    {
        $cat = ContentCategory::where('slug', $categorySlug)->firstOrFail();

        $item = ContentItem::where('category_id', $cat->id)
            ->where('slug', $itemSlug)
            ->firstOrFail(['id','category_id','title','slug','excerpt','body','source_url','sort_order']);

        return response()->json(['data' => $item, 'category' => $cat]);
    }
}
