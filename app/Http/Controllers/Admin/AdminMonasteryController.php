<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Monastery;

class AdminMonasteryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending'); // pending|approved|rejected|all
        $q = trim((string) $request->query('q', ''));

        $query = Monastery::with(['images'])->orderBy('name');

        if ($status !== 'all') {
            $query->where('review_status', $status);
        }

        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('name','like',"%{$q}%")
                  ->orWhere('slug','like',"%{$q}%")
                  ->orWhere('description','like',"%{$q}%");
            });
        }

        $items = $query->paginate(50)->withQueryString();

        return view('admin.monasteries.index', compact('items','status','q'));
    }

    public function approve(int $id)
    {
        $m = Monastery::findOrFail($id);
        $m->update(['review_status' => 'approved', 'is_spc_guess' => 1]);
        return back();
    }

    public function reject(int $id)
    {
        $m = Monastery::findOrFail($id);
        $m->update(['review_status' => 'rejected', 'is_spc_guess' => 0]);
        return back();
    }

    public function destroy(int $id)
    {
        $m = Monastery::findOrFail($id);
        $m->delete();
        return back();
    }
}
