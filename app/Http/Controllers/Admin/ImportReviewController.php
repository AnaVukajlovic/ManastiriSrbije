<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monastery;
use Illuminate\Http\Request;

class ImportReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', 'pending'); // pending|approved|rejected|all
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 30);
        $perPage = max(10, min(200, $perPage));

        $query = Monastery::query()->with(['images' => fn($q) => $q->orderBy('sort_order')]);

        if ($status !== 'all') {
            $query->where('review_status', $status);
        }

        // “SPC guess” filter (opciono): ?spc=1 / ?spc=0
        if ($request->has('spc')) {
            $spc = (int) $request->query('spc');
            $query->where('is_spc_guess', $spc === 1);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%")
                  ->orWhere('city', 'like', "%{$q}%")
                  ->orWhere('region', 'like', "%{$q}%")
                  ->orWhere('wikidata_qid', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('admin.import_review', compact('rows', 'status', 'q'));
    }

    private function ids(Request $request): array
    {
        $ids = $request->input('ids', []);
        return is_array($ids) ? array_values(array_filter($ids)) : [];
    }

    public function approve(Request $request)
    {
        $ids = $this->ids($request);
        Monastery::whereIn('id', $ids)->update([
            'review_status' => 'approved',
            'is_approved' => true,
        ]);
        return back()->with('ok', 'Odobreno: ' . count($ids));
    }

    public function reject(Request $request)
    {
        $ids = $this->ids($request);
        Monastery::whereIn('id', $ids)->update([
            'review_status' => 'rejected',
            'is_approved' => false,
        ]);
        return back()->with('ok', 'Odbačeno: ' . count($ids));
    }

    public function pending(Request $request)
    {
        $ids = $this->ids($request);
        Monastery::whereIn('id', $ids)->update([
            'review_status' => 'pending',
            'is_approved' => false,
        ]);
        return back()->with('ok', 'Vraćeno na pending: ' . count($ids));
    }

    public function delete(Request $request)
    {
        $ids = $this->ids($request);
        Monastery::whereIn('id', $ids)->delete();
        return back()->with('ok', 'Obrisano: ' . count($ids));
    }
}
