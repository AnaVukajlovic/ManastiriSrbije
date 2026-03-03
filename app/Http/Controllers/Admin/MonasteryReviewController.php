<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monastery;
use Illuminate\Http\Request;

class MonasteryReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending'); // pending|approved|rejected|all
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 30);
        $perPage = max(10, min(200, $perPage));

        $query = Monastery::query()->with(['images' => fn($q) => $q->orderBy('sort_order')]);

        if ($status !== 'all') {
            $query->where('review_status', $status);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%")
                  ->orWhere('wikidata_qid', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('admin.monasteries.index', compact('items', 'status', 'q'));
    }

    public function approve(Monastery $monastery)
    {
        $monastery->update([
            'review_status' => 'approved',
            'is_spc' => true,
        ]);

        return back()->with('ok', 'Označeno kao SPC ✅');
    }

    public function reject(Monastery $monastery)
    {
        $monastery->update([
            'review_status' => 'rejected',
            'is_spc' => false,
        ]);

        return back()->with('ok', 'Označeno kao nije SPC ❌');
    }

    public function resetStatus(Monastery $monastery)
    {
        $monastery->update([
            'review_status' => 'pending',
            'is_spc' => null,
        ]);

        return back()->with('ok', 'Vraćeno na pending 🔄');
    }
}
