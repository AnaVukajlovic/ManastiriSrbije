<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VaskrsController extends Controller
{
    public function index()
    {
        // default godina = trenutna
        $year = (int) date('Y');

        return view('pages.pravoslavni.modules.vaskrs.index', compact('year'));
    }

    public function show(string $slug)
    {
        // za sada imamo jednu “show” stranicu
        if ($slug !== 'sve-o-vaskrsu') {
            abort(404);
        }

        $year = (int) date('Y');

        return view('pages.pravoslavni.modules.vaskrs.show', compact('year'));
    }
}