<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Note;
use App\Models\Video;
use App\Models\QaPaper;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 5 Categories
        $categories = [];
        for ($i = 1; $i <= 5; $i++) {
            $categories[] = Category::create([
                'name' => "Category $i",
                'slug' => "category-$i",
                'status' => 'active',
                'sort_order' => $i,
            ]);
        }

        // 2. Create 5 Courses for each category
        foreach ($categories as $cat) {
            for ($j = 1; $j <= 2; $j++) { // 2 per category = 10 total
                $course = Course::create([
                    'category_id' => $cat->id,
                    'name' => "Course {$cat->id}-$j",
                    'slug' => "course-{$cat->id}-$j",
                    'description' => "Description for course {$cat->id}-$j",
                    'price' => rand(499, 2999),
                    'status' => 'active',
                    'sort_order' => $j,
                ]);

                // 3. Create 3 Subjects for each course
                for ($k = 1; $k <= 3; $k++) {
                    $subject = Subject::create([
                        'course_id' => $course->id,
                        'name' => "Subject {$course->id}-$k",
                        'slug' => "subject-{$course->id}-$k",
                        'status' => 'active',
                        'sort_order' => $k,
                    ]);

                    // 4. Create dummy content for each subject
                    // Notes
                    for ($n = 1; $n <= 2; $n++) {
                        Note::create([
                            'subject_id' => $subject->id,
                            'name' => "Note $n for {$subject->name}",
                            'file_path' => "notes/sample.pdf",
                            'file_type' => 'pdf',
                            'is_free' => $n == 1,
                            'status' => 'active',
                        ]);
                    }

                    // Videos
                    for ($v = 1; $v <= 2; $v++) {
                        Video::create([
                            'subject_id' => $subject->id,
                            'name' => "Video $v for {$subject->name}",
                            'video_url' => "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
                            'video_source' => 'youtube',
                            'is_free' => $v == 1,
                            'status' => 'active',
                        ]);
                    }

                    // Papers
                    for ($p = 1; $p <= 2; $p++) {
                        QaPaper::create([
                            'subject_id' => $subject->id,
                            'name' => "Paper $p for {$subject->name}",
                            'file_path' => "papers/sample.pdf",
                            'file_type' => 'pdf',
                            'is_free' => $p == 1,
                            'status' => 'active',
                        ]);
                    }

                    // Quizzes
                    for ($q = 1; $q <= 2; $q++) {
                        $quiz = Quiz::create([
                            'subject_id' => $subject->id,
                            'title' => "Quiz $q for {$subject->name}",
                            'time_limit_minutes' => 30,
                            'passing_percentage' => 40,
                            'total_marks' => 10,
                            'status' => 'active',
                        ]);

                        // Questions for Quiz
                        for ($qn = 1; $qn <= 5; $qn++) {
                            $question = QuizQuestion::create([
                                'quiz_id' => $quiz->id,
                                'question_text' => "Sample Question $qn for Quiz {$quiz->id}?",
                                'type' => 'mcq',
                                'marks' => 2,
                                'sort_order' => $qn,
                            ]);

                            // Options
                            for ($o = 1; $o <= 4; $o++) {
                                QuizOption::create([
                                    'question_id' => $question->id,
                                    'option_text' => "Option $o",
                                    'is_correct' => $o == 1,
                                    'sort_order' => $o,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // 5. Create 10 Students
        for ($s = 1; $s <= 10; $s++) {
            $student = Student::create([
                'name' => "Student $s",
                'email' => "student$s@example.com",
                'mobile' => "987654321$s",
                'status' => 'active',
                'plan' => $s % 2 == 0 ? 'premium' : 'free',
            ]);

            // 6. Create some Orders and Enrollments
            if ($s <= 5) {
                $course = Course::inRandomOrder()->first();
                $subject = $course->subjects()->first();

                $order = Order::create([
                    'student_id' => $student->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'total_amount' => $course->price,
                    'payment_status' => 'completed',
                    'payment_method' => 'upi',
                    'payment_id' => 'pay_' . Str::random(10),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => 'course',
                    'item_id' => $course->id,
                    'price' => $course->price,
                ]);

                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'subject_id' => $subject ? $subject->id : null,
                    'order_id' => $order->id,
                    'status' => 'active',
                ]);
            }
        }
    }
}
