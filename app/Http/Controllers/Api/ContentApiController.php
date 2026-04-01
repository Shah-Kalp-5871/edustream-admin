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
use App\Models\CartItem;
use App\Models\NoteFolder;
use App\Models\VideoFolder;
use App\Models\QaPaperFolder;
use App\Models\Video;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentApiController extends Controller
{
    public function home()
    {
        $categories = Category::active()->orderBy('sort_order')->take(8)->get();
        $featuredCourses = Course::active()->with('subjects')->where('price', '>', 0)->orderBy('created_at', 'desc')->take(5)->get();
        
        $banners = \App\Models\Banner::active()->orderBy('sort_order')->get()->map(function($banner) {
            return [
                'id'          => $banner->id,
                'title'       => $banner->title,
                'subtitle'    => $banner->subtitle,
                'icon'        => $banner->icon ?? 'fa-graduation-cap',
                'color_start' => $banner->color_start ?? '#1565C0',
                'color_end'   => $banner->color_end ?? '#7B1FA2',
                'link'        => $banner->redirect_url,
            ];
        });

        // Recommended course logic
        $recommended = $this->getRecommendedCourse();

        // Personalized Data
        $student = auth()->guard('api-student')->user();
        $personalizedVideos = [];
        $personalizedNotes = [];
        $personalizedQuizzes = [];

        if ($student && $student->course_id) {
            $subjectIds = Subject::where('course_id', $student->course_id)->active()->pluck('id');
            
            $personalizedVideos = Video::whereIn('subject_id', $subjectIds)
                ->active()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
                
            $personalizedNotes = Note::whereIn('subject_id', $subjectIds)
                ->active()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $personalizedQuizzes = Quiz::whereIn('subject_id', $subjectIds)
                ->active()
                ->withCount('questions')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        return response()->json([
            'categories' => $categories,
            'featured_courses' => $featuredCourses,
            'banners' => $banners,
            'recommended_course' => $recommended,
            'personalized_videos' => $personalizedVideos,
            'personalized_notes' => $personalizedNotes,
            'personalized_quizzes' => $personalizedQuizzes,
        ]);
    }

    public function recommendedCourse()
    {
        return response()->json($this->getRecommendedCourse());
    }

    private function getRecommendedCourse()
    {
        $student = auth()->guard('api-student')->user();
        
        // 1. Try to find the student's selected course
        $course = null;
        if ($student && $student->course_id) {
            $course = Course::active()->find($student->course_id);
        }

        // 2. Fallback to the course marked as Global Fallback (is_recommended)
        if (!$course) {
            $course = Course::active()->where('is_recommended', true)->first();
        }

        // 3. Last resort: any active course
        if (!$course) {
            $course = Course::active()->first();
        }

        if (!$course) return null;

        // Calculate MRP from active subjects
        $subjects = $course->subjects()->active()->get();
        $mrp = $subjects->sum('price');

        return [
            'id' => $course->id,
            'name' => $course->name,
            'description' => $course->description,
            'price' => $course->price,
            'mrp' => $mrp,
            'save' => max(0, $mrp - $course->price),
            'subjects' => $subjects->map(function($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'description' => $s->description,
                    'price' => $s->price,
                    'icon' => $s->icon_url ?? 'fa-solid fa-book',
                    'color' => $s->color_code ?? '#1565C0',
                ];
            }),
            'thumbnail_url' => $course->thumbnail_url,
            'icon_url' => $course->icon_url ?? 'fa-solid fa-graduation-cap',
            'color_code' => $course->color_code ?? '#1565C0',
        ];
    }

    public function allCourses()
    {
        // For the signup dropdown, we just need courses, optionally their category info
        $courses = Course::active()->with(['category:id,name', 'subjects'])->orderBy('category_id')->get();
        return response()->json($courses);
    }

    public function categories()
    {
        $categories = Category::active()->withCount('courses')->get();
        return response()->json($categories);
    }

    public function categoryCourses($id)
    {
        $courses = Course::active()->where('category_id', $id)->with('subjects')->withCount('subjects')->get();
        return response()->json($courses);
    }

    public function courseSubjects($id)
    {
        $course = Course::active()->findOrFail($id);
        $student = auth()->guard('api-student')->user();
        
        $subjects = Subject::active()->where('course_id', $id)->orderBy('sort_order')->get();
        
        // Check if student has full course enrollment
        $isCourseEnrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $id)
            ->whereNull('subject_id')
            ->active()
            ->exists();
        
        // Get individually enrolled subject IDs for this course
        $enrolledSubjectIds = Enrollment::where('student_id', $student->id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->active()
            ->pluck('subject_id')
            ->toArray();
            
        return response()->json([
            'course'               => $course,
            'subjects'             => $subjects->map(function($s) use ($isCourseEnrolled, $enrolledSubjectIds, $course) {
                $data = $s->toArray();
                // A subject is effectively free if the course is free OR the subject itself is free
                $data['is_free'] = $course->is_free || $s->is_free;
                return $data;
            }),
            'is_enrolled'          => $isCourseEnrolled || $course->is_free,   // true = full course purchased or free
            'enrolled_subject_ids' => $enrolledSubjectIds,        // list of individually purchased subject IDs
            'course_is_free'       => $course->is_free,
        ]);
    }

    public function subjectDetails(Request $request, $id)
    {
        $subject = Subject::with('course')->findOrFail($id);
        $student = auth()->guard('api-student')->user();

        // Check if access should be granted (free course, free subject, or enrolled)
        $courseIsFree = $subject->course ? $subject->course->is_free : false;
        $subjectIsFree = $subject->is_free;
        $isEffectivelyFree = $courseIsFree || $subjectIsFree;
        $isEnrolled = $isEffectivelyFree || Enrollment::where('student_id', $student->id)
            ->where(function($q) use ($subject) {
                $q->where('subject_id', $subject->id)
                  ->orWhere('course_id', $subject->course_id);
            })
            ->active()
            ->exists();

        $videoFolders = $subject->videoFolders()->active()->whereNull('parent_id')->withCount('videos')->get();
        $noteFolders = $subject->noteFolders()->active()->whereNull('parent_id')->withCount('notes')->get();
        $paperFolders = $subject->qaPaperFolders()->active()->whereNull('parent_id')->withCount('qaPapers')->get();
        $quizzes = $subject->quizzes()->active()->withCount('questions')->get();
        
        $rootVideos = $subject->videos()->active()->whereNull('folder_id')->get();
        $rootNotes = $subject->notes()->active()->whereNull('folder_id')->get();
        $rootPapers = $subject->qaPapers()->active()->whereNull('folder_id')->get();

        // Attach free content flag to folders
        $videoFolders->each(function($f) { $f->is_free = $f->videos()->active()->free()->exists(); });
        $noteFolders->each(function($f) { $f->is_free = $f->notes()->active()->free()->exists(); });
        $paperFolders->each(function($f) { $f->is_free = $f->qaPapers()->active()->free()->exists(); });

        // Apply item-level locking/free logic to root items
        $rootVideos->each(function($v) use ($isEnrolled) {
            $v->is_locked = !$v->is_free && !$isEnrolled;
            if ($v->is_locked) unset($v->video_url, $v->file_path);
            $v->processing_status = $v->processing_status ?? 'completed';
        });

        $rootNotes->each(function($n) use ($isEnrolled) {
            $n->is_locked = !$n->is_free && !$isEnrolled;
            if ($n->is_locked) unset($n->file_path);
        });

        $rootPapers->each(function($p) use ($isEnrolled) {
            $p->is_locked = !$p->is_free && !$isEnrolled;
            if ($p->is_locked) unset($p->file_path);
        });

        return response()->json([
            'subject' => $subject,
            'is_enrolled' => $isEnrolled,
            'is_free' => $isEffectivelyFree,
            'sections' => [
                'video_folders' => $videoFolders,
                'note_folders' => $noteFolders,
                'paper_folders' => $paperFolders,
                'root_videos' => $rootVideos,
                'root_notes' => $rootNotes,
                'root_papers' => $rootPapers,
                'quizzes' => $quizzes,
            ],
            'content_summary' => [
                'notes_count' => $subject->notes()->active()->count(),
                'videos_count' => $subject->videos()->active()->count(),
                'papers_count' => $subject->qaPapers()->active()->count(),
                'quizzes_count' => $subject->quizzes()->active()->count(),
            ]
        ]);
    }

    public function subjectNotes(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $student = auth()->guard('api-student')->user();
        $isEnrolled = $this->checkEnrollment($student->id, $subject);
        $folderId = $request->query('folder_id');

        $folders = NoteFolder::where('subject_id', $subject->id)
            ->where('parent_id', $folderId)
            ->active()
            ->withCount('notes')
            ->orderBy('sort_order')
            ->get();

        $notes = $subject->notes()
            ->where('folder_id', $folderId)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $folders->each(function($f) { $f->is_free = $f->notes()->active()->free()->exists(); });
        
        $notes->each(function($note) use ($isEnrolled) {
            $note->is_locked = !$note->is_free && !$isEnrolled;
            if ($note->is_locked) unset($note->file_path);
        });

        return response()->json([
            'folders' => $folders,
            'files' => $notes
        ]);
    }

    public function subjectVideos(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $student = auth()->guard('api-student')->user();
        $isEnrolled = $this->checkEnrollment($student->id, $subject);
        $folderId = $request->query('folder_id');

        $folders = VideoFolder::where('subject_id', $subject->id)
            ->where('parent_id', $folderId)
            ->active()
            ->withCount('videos')
            ->orderBy('sort_order')
            ->get();

        $videos = $subject->videos()
            ->where('folder_id', $folderId)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $folders->each(function($f) { $f->is_free = $f->videos()->active()->free()->exists(); });

        $videos->each(function($video) use ($isEnrolled) {
            $video->is_locked = !$video->is_free && !$isEnrolled;
            // Optionally, do not return video_url if locked, or keep it to be handled by Signed URL endpoint later
            if ($video->is_locked) unset($video->video_url, $video->file_path);
            
            // Send processing status to the frontend
            $video->processing_status = $video->processing_status ?? 'completed'; // Fallback for old videos
        });

        return response()->json([
            'folders' => $folders,
            'files' => $videos
        ]);
    }

    public function subjectPapers(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $student = auth()->guard('api-student')->user();
        $isEnrolled = $this->checkEnrollment($student->id, $subject);
        $folderId = $request->query('folder_id');

        $folders = QaPaperFolder::where('subject_id', $subject->id)
            ->where('parent_id', $folderId)
            ->active()
            ->withCount('qaPapers')
            ->orderBy('sort_order')
            ->get();

        $papers = $subject->qaPapers()
            ->where('folder_id', $folderId)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $folders->each(function($f) { $f->is_free = $f->qaPapers()->active()->free()->exists(); });

        $papers->each(function($paper) use ($isEnrolled) {
            $paper->is_locked = !$paper->is_free && !$isEnrolled;
            if ($paper->is_locked) unset($paper->file_path);
        });

        return response()->json([
            'folders' => $folders,
            'files' => $papers
        ]);
    }

    public function subjectQuizzes($id)
    {
        $subject = Subject::findOrFail($id);
        $quizzes = $subject->quizzes()->active()->withCount('questions')->orderBy('sort_order')->get();
        return response()->json($quizzes);
    }

    public function quizHub()
    {
        $categories = Category::active()
            ->whereHas('courses', function ($q) {
                $q->active()->whereHas('subjects', function ($sq) {
                    $sq->active()->whereHas('quizzes', function ($qz) {
                        $qz->active();
                    });
                });
            })
            ->with(['courses' => function ($q) {
                $q->active()
                    ->whereHas('subjects', function ($sq) {
                        $sq->active()->whereHas('quizzes', function ($qz) {
                            $qz->active();
                        });
                    })
                    ->orderBy('sort_order')
                    ->with(['subjects' => function ($sq) {
                        $sq->active()
                            ->whereHas('quizzes', function ($qz) {
                                $qz->active();
                            })
                            ->orderBy('sort_order')
                            ->with(['quizzes' => function ($qz) {
                                $qz->active()->withCount('questions')->orderBy('sort_order');
                            }]);
                    }]);
            }])
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    private function checkEnrollment($studentId, $subject)
    {
        // If the course or subject is marked free, access is always granted
        if ($subject->is_free) return true;
        if ($subject->course && $subject->course->is_free) return true;

        return Enrollment::where('student_id', $studentId)
            ->where(function($q) use ($subject) {
                $q->where('subject_id', $subject->id)
                  ->orWhere('course_id', $subject->course_id);
            })
            ->active()
            ->exists();
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
        $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::with('questions.options')->findOrFail($id);
        $submittedAnswers = $request->answers; // [question_id => selected_option_index]
        $score = 0;
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->firstWhere('is_correct', true);
            $correctOptionId = $correctOption ? $correctOption->id : null;

            if (isset($submittedAnswers[$question->id]) && $submittedAnswers[$question->id] == $correctOptionId) {
                $score++;
            }
        }

        $percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
        $status = ($percentage >= ($quiz->passing_percentage ?? 0)) ? 'passed' : 'failed';

        // Record the Attempt
        QuizAttempt::create([
            'student_id' => auth()->guard('api-student')->id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'percentage' => $percentage,
            'status' => $status,
            'completed_at' => now(),
        ]);

        return response()->json([
            'score'      => $score,
            'total'      => $totalQuestions,
            'percentage' => $percentage,
            'quiz'       => [
                'id'        => $quiz->id,
                'title'     => $quiz->title,
                'questions' => $quiz->questions->map(function($q) {
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
                        })
                    ];
                })
            ],
        ]);
    }

    public function getCart()
    {
        $student = auth()->guard('api-student')->user();
        $cartItems = CartItem::where('student_id', $student->id)->with('item')->get();
        
        $total = $cartItems->sum('price');
        
        return response()->json([
            'items' => $cartItems,
            'total' => $total
        ]);
    }

    public function addToCart(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Adding to cart', $request->all());
        \Illuminate\Support\Facades\Log::info('Current User: ' . (auth()->guard('api-student')->check() ? auth()->guard('api-student')->id() : 'None'));

        $request->validate([
            'item_type' => 'required|in:course,subject,bundle',
            'item_id' => 'required_unless:item_type,bundle',
            'bundle_subjects' => 'required_if:item_type,bundle|nullable|array',
            'price' => 'required_if:item_type,bundle|nullable|numeric',
        ]);

        $student = auth()->guard('api-student')->user();
        $itemType = $request->item_type;

        if ($itemType === 'course') {
            $course = Course::findOrFail($request->item_id);
            
            // Constraint 1: Remove existing subjects from same course if adding course
            CartItem::where('student_id', $student->id)
                ->where('item_type', Subject::class)
                ->whereIn('item_id', $course->subjects()->pluck('id'))
                ->delete();

            $model = Course::class;
            $itemId = $course->id;
            $price = $course->price;
            $bundleSubjects = null;
        } elseif ($itemType === 'subject') {
            $subject = Subject::findOrFail($request->item_id);
            
            // Constraint 2: Don't add if parent course is already in cart
            $courseInCart = CartItem::where('student_id', $student->id)
                ->where('item_type', Course::class)
                ->where('item_id', $subject->course_id)
                ->exists();

            if ($courseInCart) {
                return response()->json(['message' => 'Course for this subject is already in cart'], 400);
            }

            $model = Subject::class;
            $itemId = $subject->id;
            $price = $subject->price;
            $bundleSubjects = null;
        } else {
            // Bundle
            $model = 'bundle';
            $itemId = 0; // Or a hash/id if needed
            $price = $request->price;
            $bundleSubjects = $request->bundle_subjects;
        }

        // Check if exact item already in cart
        $exists = CartItem::where('student_id', $student->id)
            ->where('item_type', $model)
            ->where('item_id', $itemId)
            ->when($model === 'bundle', function($q) use ($bundleSubjects) {
                // For bundles, usually we just add a new one or check subset, 
                // but for simplicity let's just let it add or check exact list
                $q->where('bundle_subjects', json_encode($bundleSubjects));
            })
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Item already in cart'], 400);
        }

        try {
            $cartItem = CartItem::create([
                'student_id' => $student->id,
                'item_type' => $model,
                'item_id' => $itemId,
                'bundle_subjects' => $bundleSubjects,
                'price' => $price,
            ]);
            \Illuminate\Support\Facades\Log::info('Cart item created successfully', ['id' => $cartItem->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create cart item', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to save: ' . $e->getMessage()], 500);
        }


        \Illuminate\Support\Facades\Log::info('Successfully added to cart', ['item' => $cartItem->toArray()]);
        return response()->json(['message' => 'Added to cart', 'item' => $cartItem]);
    }

    public function removeFromCart($id)
    {
        $student = auth()->guard('api-student')->user();
        $cartItem = CartItem::where('student_id', $student->id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function initiateRazorpayOrder(Request $request)
    {
        $student = auth()->guard('api-student')->user();
        $cartItems = CartItem::where('student_id', $student->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $totalAmount = $cartItems->sum('price');
        $receiptId = 'rcpt_' . Str::random(10);

        try {
            $razorpayService = new \App\Services\RazorpayService();
            $razorpayOrder = $razorpayService->createOrder($totalAmount, $receiptId, [
                'student_id' => $student->id,
                'email' => $student->email,
            ]);

            return response()->json([
                'razorpay_order_id' => $razorpayOrder['id'],
                'razorpay_key' => config('services.razorpay.key_id'),
                'amount' => $totalAmount * 100, // in paise
                'currency' => 'INR',
                'name' => 'EduStream',
                'description' => 'Course Purchase',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to initiate payment: ' . $e->getMessage()], 500);
        }
    }

    public function verifyRazorpayPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $razorpayService = new \App\Services\RazorpayService();
        $isValid = $razorpayService->verifySignature(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature
        );

        if (!$isValid) {
            return response()->json(['error' => 'Invalid payment signature'], 400);
        }

        $student = auth()->guard('api-student')->user();
        
        return $this->fulfillOrder($student, $request->all());
    }

    private function fulfillOrder($student, $paymentData)
    {
        $cartItems = CartItem::where('student_id', $student->id)->get();

        if ($cartItems->isEmpty()) {
            // Might have been fulfilled by webhook already
            $existingOrder = Order::where('razorpay_order_id', $paymentData['razorpay_order_id'])->first();
            if ($existingOrder) {
                return response()->json(['message' => 'Order already fulfilled', 'order' => $existingOrder]);
            }
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'student_id' => $student->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $cartItems->sum('price'),
                'payment_status' => 'completed',
                'payment_method' => 'razorpay',
                'payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_order_id' => $paymentData['razorpay_order_id'],
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_signature' => $paymentData['razorpay_signature'],
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $cartItem->item_type,
                    'item_id' => $cartItem->item_id,
                    'bundle_subjects' => $cartItem->bundle_subjects,
                    'price' => $cartItem->price,
                ]);

                // Create Enrollment
                if ($cartItem->item_type === Course::class) {
                    Enrollment::updateOrCreate([
                        'student_id' => $student->id,
                        'course_id' => $cartItem->item_id,
                        'subject_id' => null,
                    ], [
                        'order_id' => $order->id,
                        'status' => 'active',
                    ]);
                } elseif ($cartItem->item_type === Subject::class) {
                    Enrollment::updateOrCreate([
                        'student_id' => $student->id,
                        'subject_id' => $cartItem->item_id,
                    ], [
                        'course_id' => Subject::find($cartItem->item_id)->course_id ?? null,
                        'order_id' => $order->id,
                        'status' => 'active',
                    ]);
                } elseif ($cartItem->item_type === 'bundle') {
                    foreach ($cartItem->bundle_subjects as $subjectId) {
                        Enrollment::updateOrCreate([
                            'student_id' => $student->id,
                            'subject_id' => $subjectId,
                        ], [
                            'course_id' => Subject::find($subjectId)->course_id ?? null,
                            'order_id' => $order->id,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            // Clear cart
            CartItem::where('student_id', $student->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order fulfilled successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Fulfillment failed: ' . $e->getMessage()], 500);
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
