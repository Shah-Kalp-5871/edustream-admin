<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function show($id)
    {
        return view('orders.show');
    }

    public function invoice($id)
    {
        return view('orders.invoice');
    }
}
