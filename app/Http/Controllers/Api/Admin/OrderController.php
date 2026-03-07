<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{
    public function index(Request $request)
    {
        $orders = Order::with('student')
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 15));

        return $this->response(true, 'Orders fetched successfully', $orders);
    }

    public function show($id)
    {
        $order = Order::with('student', 'items')->findOrFail($id);
        return $this->response(true, 'Order details fetched', $order);
    }
}
