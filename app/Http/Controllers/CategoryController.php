<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->orderBy('sort_order')->get();
        return view('content.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('content.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,deleted_at,NULL',
            'icon_url' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('category-')),
            'icon_url' => $request->icon_url,
            'status' => $request->status,
            'sort_order' => Category::max('sort_order') + 1,
        ]);

        return redirect('/content/categories')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('content.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,deleted_at,NULL',
            'icon_url' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('category-')),
            'icon_url' => $request->icon_url,
            'status' => $request->status,
        ]);

        return redirect('/content/categories')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::withCount('courses')->findOrFail($id);
        
        if ($category->courses_count > 0) {
            return redirect('/content/categories')->with('error', 'Cannot delete category. Please delete associated Courses & Standards first.');
        }

        $category->delete();

        return redirect('/content/categories')->with('success', 'Category deleted successfully!');
    }
}
