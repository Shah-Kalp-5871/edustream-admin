<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends BaseApiController
{
    public function index()
    {
        $categories = Category::withCount('courses')->orderBy('sort_order')->get();
        return $this->response(true, 'Categories fetched successfully', $categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('category-')),
            'sort_order' => Category::max('sort_order') + 1
        ]);

        return $this->response(true, 'Category created successfully', $category, 211);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('category-'))
        ]);

        return $this->response(true, 'Category updated successfully', $category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->response(true, 'Category deleted successfully');
    }
}
