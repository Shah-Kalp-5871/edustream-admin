<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function app()
    {
        return view('layouts.app');
    }

    public function auth()
    {
        return view('layouts.auth');
    }

    public function sidebar()
    {
        return view('layouts.sidebar');
    }

    public function navbar()
    {
        return view('layouts.navbar');
    }

    public function footer()
    {
        return view('layouts.footer');
    }
}
