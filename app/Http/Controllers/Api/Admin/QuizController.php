<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Quiz::query();
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        $quizzes = $query->with('subject')->withCount('questions')->orderBy('created_at', 'desc')->get();
        return $this->response(true, 'Quizzes fetched successfully', $quizzes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_percentage' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $quiz = Quiz::create($request->all());

        return $this->response(true, 'Quiz created successfully', $quiz, 211);
    }

    public function show($id)
    {
        $quiz = Quiz::with('subject', 'questions.options')->findOrFail($id);
        return $this->response(true, 'Quiz details fetched', $quiz);
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_percentage' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $quiz->update($request->all());

        return $this->response(true, 'Quiz updated successfully', $quiz);
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return $this->response(true, 'Quiz deleted successfully');
    }
}
