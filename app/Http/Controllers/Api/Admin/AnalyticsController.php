<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends BaseApiController
{
    public function dashboard()
    {
        // Summary for charts etc
        $revenueData = Order::where('payment_status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $studentGrowth = Student::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        return $this->response(true, 'Analytics summary fetched', [
            'revenue_history' => $revenueData,
            'student_growth' => $studentGrowth
        ]);
    }

    public function revenue()
    {
        $revenueByCourse = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('courses', 'order_items.item_id', '=', 'courses.id')
            ->where('orders.payment_status', 'completed')
            ->where('order_items.item_type', 'course')
            ->select('courses.name', DB::raw('SUM(order_items.price) as total'))
            ->groupBy('courses.id', 'courses.name')
            ->get();

        return $this->response(true, 'Revenue breakdown fetched', [
            'by_course' => $revenueByCourse
        ]);
    }

    public function users()
    {
        $userStats = [
            'active_students' => Student::where('status', 'active')->count(),
            'blocked_students' => Student::where('status', 'blocked')->count(),
            'premium_students' => Student::where('plan', 'premium')->count(),
            'free_students' => Student::where('plan', 'free')->count(),
        ];

        return $this->response(true, 'User analytics fetched', $userStats);
    }
}
