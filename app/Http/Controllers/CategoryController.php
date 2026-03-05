<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function getCategories()
    {
        return [
            ['id' => 1, 'name' => 'Primary School', 'courses_count' => 12],
            ['id' => 2, 'name' => 'Secondary School', 'courses_count' => 15],
            ['id' => 3, 'name' => 'High School', 'courses_count' => 8],
            ['id' => 4, 'name' => 'Higher Secondary', 'courses_count' => 10],
            ['id' => 5, 'name' => 'Undergraduate', 'courses_count' => 6],
            ['id' => 6, 'name' => 'Postgraduate', 'courses_count' => 4],
        ];
    }

    public function index()
    {
        $categories = $this->getCategories();
        return view('content.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('content.categories.create');
    }

    public function edit($id)
    {
        $id = (int)$id;
        $categories = $this->getCategories();
        $category = collect($categories)->firstWhere('id', $id);
        return view('content.categories.edit', compact('category'));
    }
}
