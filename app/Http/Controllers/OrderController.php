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
    public function export()
    {
        $fileName = 'orders_report_' . date('Y-m-d') . '.csv';
        $orders = Order::with(['student', 'items.item'])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order ID', 'Student', 'Email', 'Items', 'Amount', 'Payment Status', 'Method', 'Date'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $items = $order->items->map(function($item) {
                    return $item->item?->name ?? $item->item?->title ?? ucfirst($item->item_type);
                })->implode(', ');

                fputcsv($file, [
                    $order->order_number ?? '#ORD-'.$order->id,
                    $order->student?->name ?? 'N/A',
                    $order->student?->email ?? 'N/A',
                    $items,
                    '₹' . number_format($order->total_amount ?? 0),
                    ucfirst($order->payment_status),
                    strtoupper($order->payment_method ?? 'N/A'),
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
