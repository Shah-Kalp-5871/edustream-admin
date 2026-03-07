<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Note;
use App\Models\QaPaper;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_enrollments' => Enrollment::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_quizzes' => Quiz::count(),
            'content_items' => Course::count() + Subject::count() + Video::count() + Note::count() + QaPaper::count() + Quiz::count(),
        ];

        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        $activityLogs = ActivityLog::with('admin')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentEnrollments', 'activityLogs'));
    }
}
