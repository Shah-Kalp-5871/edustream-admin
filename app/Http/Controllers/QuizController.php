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
        
        $questions = $quiz->questions->map(function($q) {
            return [
                'id'            => $q->id,
                'question_text' => $q->question_text,
                'marks'         => $q->marks,
                'options'       => $q->options->map(function($o) {
                    return [
                        'id'          => $o->id,
                        'option_text' => $o->option_text,
                        'is_correct'  => (bool)$o->is_correct,
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        return view('content.subjectdetails.quiz.manage', compact('quiz', 'subjectName', 'questions'));
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

    public function updateOption(Request $request, $id)
    {
        $option = \App\Models\QuizOption::findOrFail($id);
        $option->update([
            'option_text' => $request->option_text,
            'is_correct'  => $request->is_correct ? 1 : 0,
        ]);
        return response()->json(['success' => true, 'message' => 'Option updated']);
    }

    /**
     * Bulk save all questions + options at once.
     * Replaces ALL existing questions for this quiz.
     */
    public function bulkSave(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $questions = $request->input('questions', []);

        // Delete old questions (cascade deletes options via DB constraint / model)
        $quiz->questions()->each(function ($q) { $q->options()->delete(); $q->delete(); });

        foreach ($questions as $i => $q) {
            $question = \App\Models\QuizQuestion::create([
                'quiz_id'       => $id,
                'question_text' => $q['question_text'],
                'type'          => 'mcq',
                'marks'         => $q['marks'] ?? 1,
                'sort_order'    => $i + 1,
            ]);
            foreach ($q['options'] ?? [] as $j => $opt) {
                \App\Models\QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['option_text'],
                    'is_correct'  => !empty($opt['is_correct']) ? 1 : 0,
                    'sort_order'  => $j + 1,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Quiz saved successfully!', 'count' => count($questions)]);
    }

    /**
     * Import questions from a JSON file upload.
     */
    public function importJson(Request $request, $id)
    {
        $request->validate(['json_file' => 'required|file|mimes:json,txt']);
        $quiz = Quiz::findOrFail($id);

        $contents = file_get_contents($request->file('json_file')->getRealPath());
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['questions'])) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON format. Make sure it has a "questions" key.'], 422);
        }

        // Delete old questions
        $quiz->questions()->each(function ($q) { $q->options()->delete(); $q->delete(); });

        foreach ($data['questions'] as $i => $q) {
            $question = \App\Models\QuizQuestion::create([
                'quiz_id'       => $id,
                'question_text' => $q['question_text'],
                'type'          => 'mcq',
                'marks'         => $q['marks'] ?? 1,
                'sort_order'    => $i + 1,
            ]);
            foreach ($q['options'] ?? [] as $j => $opt) {
                \App\Models\QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['option_text'],
                    'is_correct'  => !empty($opt['is_correct']) ? 1 : 0,
                    'sort_order'  => $j + 1,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => count($data['questions']) . ' questions imported!', 'redirect' => url('/content/quiz/' . $id . '/manage')]);
    }
}
