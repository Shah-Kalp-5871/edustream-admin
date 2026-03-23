<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubjectController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Subject::query();
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        $subjects = $query->with('course')->withCount(['notes', 'videos', 'quizzes', 'qaPapers'])->orderBy('sort_order')->get();
        return $this->response(true, 'Subjects fetched successfully', $subjects);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
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

        $subject = Subject::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('subject-')),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-book',
            'color_code' => $request->color_code ?? '#1565C0',
            'sort_order' => Subject::where('course_id', $request->course_id)->max('sort_order') + 1,
        ]);

        return $this->response(true, 'Subject created successfully', $subject, 211);
    }

    public function show($id)
    {
        $subject = Subject::with('course')->withCount(['notes', 'videos', 'quizzes', 'qaPapers'])->findOrFail($id);
        return $this->response(true, 'Subject details fetched', $subject);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validator = Validator::make($request->all(), [
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

        $subject->update([
            'name' => $request->name,
            'slug' => !empty(Str::slug($request->name)) ? Str::slug($request->name) : (trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/[\s]+/u', '-', mb_strtolower($request->name, 'UTF-8'))), '-') ?: uniqid('subject-')),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url,
            'color_code' => $request->color_code,
        ]);

        return $this->response(true, 'Subject updated successfully', $subject);
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return $this->response(true, 'Subject deleted successfully');
    }
}
