<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'time_limit_minutes' => 'nullable|integer',
            'passing_percentage' => 'nullable|integer',
        ]);

        $quiz = Quiz::create([
            'subject_id' => $id,
            'title' => $request->title,
            'description' => $request->description,
            'time_limit_minutes' => $request->time_limit_minutes ?? 30,
            'passing_percentage' => $request->passing_percentage ?? 40,
            'is_free' => $request->has('is_free'),
            'status' => 'inactive',
            'sort_order' => Quiz::where('subject_id', $id)->max('sort_order') + 1,
        ]);

        return redirect('/content/quiz/' . $quiz->id . '/manage')->with('success', 'Quiz created successfully! Now add some questions.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function toggleFree(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['is_free' => $request->is_free]);
        return response()->json(['success' => true, 'message' => 'Access status updated successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);
        $quiz = Quiz::findOrFail($id);
        $quiz->update($request->only(['title', 'description', 'sort_order']));
        return response()->json(['success' => true, 'message' => 'Quiz updated successfully']);
    }

    public function manage($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        $subjectName = $quiz->subject->name;
        return view('content.subjectdetails.quiz.manage', compact('quiz', 'subjectName'));
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return back()->with('success', 'Quiz deleted successfully!');
    }

    public function storeQuestion(Request $request, $id)
    {
        $request->validate(['question_text' => 'required']);
        $question = \App\Models\QuizQuestion::create([
            'quiz_id' => $id,
            'question_text' => $request->question_text,
            'type' => $request->type ?? 'mcq',
            'marks' => $request->marks ?? 1,
            'sort_order' => \App\Models\QuizQuestion::where('quiz_id', $id)->max('sort_order') + 1,
        ]);
        return back()->with('success', 'Question added!');
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = \App\Models\QuizQuestion::findOrFail($id);
        $question->update($request->all());
        return back()->with('success', 'Question updated!');
    }

    public function deleteQuestion($id)
    {
        $question = \App\Models\QuizQuestion::findOrFail($id);
        $question->delete();
        return back()->with('success', 'Question deleted!');
    }

    public function storeOption(Request $request, $id)
    {
        $request->validate(['option_text' => 'required']);
        \App\Models\QuizOption::create([
            'question_id' => $id,
            'option_text' => $request->option_text,
            'is_correct' => $request->has('is_correct'),
            'sort_order' => \App\Models\QuizOption::where('question_id', $id)->max('sort_order') + 1,
        ]);
        return back()->with('success', 'Option added!');
    }

    public function deleteOption($id)
    {
        $option = \App\Models\QuizOption::findOrFail($id);
        $option->delete();
        return back()->with('success', 'Option deleted!');
    }
}
