<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['student', 'items.item'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingPayments = Order::where('payment_status', 'pending')->count();

        return view('orders.index', compact('orders', 'totalOrders', 'totalRevenue', 'pendingPayments'));
    }

    public function show($id)
    {
        $order = Order::with(['student', 'items.item', 'enrollments'])->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }

    public function invoice($id)
    {
        return view('orders.invoice');
    }
}
