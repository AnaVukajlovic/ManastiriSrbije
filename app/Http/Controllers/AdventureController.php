<?php

use App\Http\Controllers\GameController;

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