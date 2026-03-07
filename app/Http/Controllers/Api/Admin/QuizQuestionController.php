<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends BaseApiController
{
    public function index($quizId)
    {
        $questions = QuizQuestion::where('quiz_id', $quizId)->with('options')->get();
        return $this->response(true, 'Questions fetched successfully', $questions);
    }

    public function store(Request $request, $quizId)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:mcq',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $question = DB::transaction(function () use ($request, $quizId) {
            $question = QuizQuestion::create([
                'quiz_id' => $quizId,
                'question_text' => $request->question_text,
                'type' => $request->question_type,
                'marks' => $request->marks ?? 1,
                'sort_order' => QuizQuestion::where('quiz_id', $quizId)->max('sort_order') + 1,
            ]);

            foreach ($request->options as $index => $option) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                    'sort_order' => $index + 1,
                ]);
            }

            return $question;
        });

        return $this->response(true, 'Question added successfully', $question->load('options'), 211);
    }

    public function update(Request $request, $id)
    {
        $question = QuizQuestion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.id' => 'sometimes|exists:quiz_options,id',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        DB::transaction(function () use ($request, $question) {
            $question->update([
                'question_text' => $request->question_text,
                'marks' => $request->marks ?? $question->marks,
            ]);

            // Simple update: delete and recreation if no IDs provided, or sync
            $optionIds = collect($request->options)->pluck('id')->filter()->toArray();
            QuizOption::where('question_id', $question->id)->whereNotIn('id', $optionIds)->delete();

            foreach ($request->options as $index => $option) {
                if (isset($option['id'])) {
                    QuizOption::where('id', $option['id'])->update([
                        'option_text' => $option['option_text'],
                        'is_correct' => $option['is_correct'],
                        'sort_order' => $index + 1,
                    ]);
                } else {
                    QuizOption::create([
                        'question_id' => $question->id,
                        'option_text' => $option['option_text'],
                        'is_correct' => $option['is_correct'],
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        });

        return $this->response(true, 'Question updated successfully', $question->load('options'));
    }

    public function destroy($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $question->delete();
        return $this->response(true, 'Question deleted successfully');
    }
}
