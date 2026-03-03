<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function show($id)
    {
        return view('users.show');
    }

    public function edit($id)
    {
        return view('users.edit');
    }
}
