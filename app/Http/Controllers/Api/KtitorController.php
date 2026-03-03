<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ktitor;
use Illuminate\Http\Request;

class KtitorController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Ktitor::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        $ktitors = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return response()->json($ktitors);
    }

    public function show(string $slug)
    {
        $ktitor = Ktitor::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($ktitor);
    }
}