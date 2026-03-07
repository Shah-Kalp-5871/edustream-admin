<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Note;
use App\Models\Video;
use App\Models\QaPaper;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends BaseApiController
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_content' => Note::count() + Video::count() + QaPaper::count(),
            'total_enrollments' => Enrollment::count(),
            'active_quizzes' => Quiz::where('status', 'active')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount')
        ];

        $recentEnrollments = Enrollment::with(['student', 'course', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentActivity = ActivityLog::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $this->response(true, 'Dashboard stats fetched successfully', [
            'stats' => $stats,
            'recent_enrollments' => $recentEnrollments,
            'recent_activity' => $recentActivity
        ]);
    }
}
