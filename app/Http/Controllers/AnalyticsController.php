<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\Note;
use App\Models\QaPaper;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Enrollment Trends (Last 30 Days)
        $days = 30;
        $enrollmentTrends = Enrollment::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $enrollmentLabels = [];
        $enrollmentValues = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $enrollmentLabels[] = Carbon::now()->subDays($i)->format('d M');
            $trend = $enrollmentTrends->firstWhere('date', $date);
            $enrollmentValues[] = $trend ? $trend->count : 0;
        }

        // 2. Content Distribution
        $contentDistribution = [
            'Videos' => Video::count(),
            'Quizzes' => Quiz::count(),
            'Notes' => Note::count(),
            'QA Papers' => QaPaper::count(),
        ];

        // 3. Revenue by Course
        $revenueByCourse = Course::select('courses.name', DB::raw('SUM(orders.total_amount) as revenue'))
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->join('orders', 'enrollments.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('courses.id', 'courses.name')
            ->orderBy('revenue', 'DESC')
            ->get();

        // 4. Top Performing Courses (by enrollment)
        $topCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'DESC')
            ->take(5)
            ->get();

        // Calculate peak count for bar chart scaling
        $maxEnrollments = $topCourses->max('enrollments_count') ?: 1;
        foreach($topCourses as $course) {
            $course->percentage = ($course->enrollments_count / $maxEnrollments) * 100;
        }

        return view('analytics.index', compact(
            'enrollmentLabels',
            'enrollmentValues',
            'contentDistribution',
            'revenueByCourse',
            'topCourses'
        ));
    }
}
