<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends BaseApiController
{
    public function index()
    {
        $courses = Course::with('category')->withCount('subjects')->orderBy('sort_order')->get();
        return $this->response(true, 'Courses fetched successfully', $courses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $course = Course::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-graduation-cap',
            'color_code' => $request->color_code ?? '#1565C0',
            'sort_order' => Course::max('sort_order') + 1,
        ]);

        return $this->response(true, 'Course created successfully', $course, 211);
    }

    public function show($id)
    {
        $course = Course::with('category', 'subjects')->findOrFail($id);
        return $this->response(true, 'Course details fetched', $course);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $course->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url,
            'color_code' => $request->color_code,
        ]);

        return $this->response(true, 'Course updated successfully', $course);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return $this->response(true, 'Course deleted successfully');
    }
}
