<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentApiController extends Controller
{
    public function categories()
    {
        $categories = Category::active()->withCount('courses')->get();
        return response()->json($categories);
    }

    public function courses(Request $request)
    {
        $query = Course::active()->with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(10);
        return response()->json($courses);
    }

    public function courseDetails($id)
    {
        $course = Course::with(['subjects' => function($q) {
            $q->active()->orderBy('sort_order');
        }])->findOrFail($id);

        return response()->json($course);
    }

    public function subjectDetails(Request $request, $id)
    {
        $subject = Subject::with(['notes', 'videos', 'qaPapers', 'quizzes'])->findOrFail($id);
        $student = auth()->guard('api-student')->user();

        // Check enrollment
        $isEnrolled = Enrollment::where('student_id', $student->id)
            ->where('subject_id', $id)
            ->active()
            ->exists();

        // Process content to add 'locked' status
        $subject->notes->each(function($note) use ($isEnrolled) {
            $note->is_locked = !$note->is_free && !$isEnrolled;
            if ($note->is_locked) unset($note->file_path);
        });

        $subject->videos->each(function($video) use ($isEnrolled) {
            $video->is_locked = !$video->is_free && !$isEnrolled;
            if ($video->is_locked) unset($video->video_url);
        });

        $subject->qaPapers->each(function($paper) use ($isEnrolled) {
            $paper->is_locked = !$paper->is_free && !$isEnrolled;
            if ($paper->is_locked) unset($paper->file_path);
        });

        return response()->json($subject);
    }

    public function myCourses()
    {
        $student = auth()->guard('api-student')->user();
        $enrollments = Enrollment::where('student_id', $student->id)
            ->active()
            ->with(['course', 'subject'])
            ->get();
            
        return response()->json($enrollments);
    }

    public function quizDetails($id)
    {
        $quiz = Quiz::with(['questions.options'])->findOrFail($id);
        
        // Hide correct answers from options to prevent cheating in API response
        $quiz->questions->each(function($question) {
            $question->options->each(function($option) {
                unset($option->is_correct);
            });
        });

        return response()->json($quiz);
    }

    public function submitQuiz(Request $request, $id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        $student = auth()->guard('api-student')->user();
        
        $answers = $request->input('answers', []); // format: [question_id => option_id]
        
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        $earnedMarks = 0;
        $totalMarks = $quiz->questions->sum('marks');

        DB::beginTransaction();
        try {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'start_time' => $request->start_time ?? now(),
                'end_time' => now(),
                'total_questions' => $totalQuestions,
                'status' => 'completed',
            ]);

            foreach ($quiz->questions as $question) {
                $selectedOptionId = $answers[$question->id] ?? null;
                $isCorrect = false;
                
                if ($selectedOptionId) {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && $correctOption->id == $selectedOptionId) {
                        $isCorrect = true;
                        $correctAnswers++;
                        $earnedMarks += $question->marks;
                    }
                }

                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                    'quiz_option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                    'marks_earned' => $isCorrect ? $question->marks : 0,
                ]);
            }

            $attempt->update([
                'correct_answers' => $correctAnswers,
                'score' => ($totalMarks > 0) ? ($earnedMarks / $totalMarks) * 100 : 0,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Quiz submitted successfully',
                'result' => [
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'score' => $attempt->score,
                    'earned_marks' => $earnedMarks,
                    'total_marks' => $totalMarks,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Submission failed: ' . $e->getMessage()], 500);
        }
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'payment_method' => 'required|string',
            'payment_id' => 'nullable|string',
        ]);

        $course = Course::findOrFail($request->course_id);
        $student = auth()->guard('api-student')->user();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'student_id' => $student->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $course->price,
                'payment_status' => 'completed', // Assuming instant payment for now
                'payment_method' => $request->payment_method,
                'payment_id' => $request->payment_id,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'item_type' => 'App\Models\Course',
                'item_id' => $course->id,
                'price' => $course->price,
            ]);

            // Create Enrollment
            Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'order_id' => $order->id,
                'status' => 'active',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Enrollment successful',
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Checkout failed'], 500);
        }
    }

    public function orderHistory()
    {
        $student = auth()->guard('api-student')->user();
        $orders = Order::where('student_id', $student->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($orders);
    }
}
