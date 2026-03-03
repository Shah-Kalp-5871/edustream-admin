<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function alert()
    {
        return view('components.alert');
    }

    public function modal()
    {
        return view('components.modal');
    }

    public function breadcrumb()
    {
        return view('components.breadcrumb');
    }

    public function tableActions()
    {
        return view('components.table-actions');
    }
}
