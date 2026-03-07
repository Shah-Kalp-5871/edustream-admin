<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course', 'subject', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalEnrollments = Enrollment::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingPayments = Order::where('payment_status', 'pending')->count();

        return view('orders.index', compact('enrollments', 'totalEnrollments', 'totalRevenue', 'pendingPayments'));
    }

    public function show($id)
    {
        return view('orders.show');
    }

    public function invoice($id)
    {
        return view('orders.invoice');
    }
}
