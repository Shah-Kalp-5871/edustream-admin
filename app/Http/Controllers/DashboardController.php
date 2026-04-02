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
    public function exportReport()
    {
        $fileName = 'dashboard_report_' . date('Y-m-d') . '.csv';

        $stats = [
            'Total Students' => Student::count(),
            'Total Enrollments' => Enrollment::count(),
            'Total Revenue' => '₹' . number_format(Order::where('payment_status', 'paid')->sum('total_amount')),
            'Total Quizzes' => Quiz::count(),
            'Total Content Items' => Course::count() + Subject::count() + Video::count() + Note::count() + QaPaper::count() + Quiz::count(),
        ];

        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest()
            ->take(50)
            ->get();

        $callback = function() {
            // This will be handled by the stream response
        };

        return response()->stream(function() use($stats, $recentEnrollments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['DASHBOARD SUMMARY']);
            fputcsv($file, ['Category', 'Value']);

            foreach ($stats as $label => $value) {
                fputcsv($file, [$label, $value]);
            }

            fputcsv($file, []);
            fputcsv($file, ['RECENT ENROLLMENTS (Last 50)']);
            fputcsv($file, ['ID', 'Student', 'Course', 'Status', 'Date']);

            foreach ($recentEnrollments as $enrollment) {
                fputcsv($file, [
                    '#ENR-' . $enrollment->id,
                    $enrollment->student->name ?? 'N/A',
                    $enrollment->course->name ?? 'N/A',
                    ucfirst($enrollment->status),
                    $enrollment->created_at->format('d M, Y')
                ]);
            }

            fclose($file);
        }, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}
