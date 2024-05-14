<?php

namespace App\Http\Controllers;

use App\Models\Area;

class MainController extends Controller
{
    public function index()
    {
        $arias = Area::orderBy('id')->cursorPaginate(50);
        return view('pages.welcome', compact('arias'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function news()
    {
        return view('pages.news');
    }
}
