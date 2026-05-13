<?php

namespace App\Http\Controllers;

class AdventureController extends Controller
{
    public function index()
    {
        return view('avantura.index');
    }

    public function play()
    {
        return view('avantura.play');
    }
}