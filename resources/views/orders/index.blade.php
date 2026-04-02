@extends('layouts.app', ['title' => 'Order Management'])

@section('subtitle', 'Track and manage all orders, payments, and purchased items.')

@section('actions')
    <a href="{{ route('orders.export') }}" class="btn btn-secondary">
        <i class="fas fa-file-invoice"></i> Export CSV
    </a>
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Manual Enrollment
    </button>
@endsection

@section('styles')
<style>
    .enroll-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .enroll-stat {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all var(--tr);
    }

    .enroll-stat:hover { box-shadow: var(--shadow); transform: translateY(-1px); }

    .es-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .es-val {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 3px;
    }

    .es-label { font-size: 12.5px; color: var(--text-muted); font-weight: 500; }

    /* Filters */
    .enroll-filters {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
        margin-bottom: 20px;
    }

    @media(max-width:900px){
        .enroll-filters { grid-template-columns: 1fr 1fr; }
        .enroll-stats { grid-template-columns: 1fr; }
    }

    .student-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-cell img {
        width: 32px; height: 32px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')

<!-- Summary Stats -->
<div class="enroll-stats">
    <div class="enroll-stat stagger-1">
        <div class="es-icon" style="background:rgba(21,101,192,0.1); color:var(--primary);">
            <i class="fas fa-ticket"></i>
        </div>
        <div>
            <div class="es-val">{{ number_format($totalOrders) }}</div>
            <div class="es-label">Total Orders</div>
        </div>
    </div>
    <div class="enroll-stat stagger-2">
        <div class="es-icon" style="background:rgba(5,150,105,0.1); color:#059669;">
            <i class="fas fa-indian-rupee-sign"></i>
        </div>
        <div>
            <div class="es-val" style="color:#059669;">₹{{ number_format($totalRevenue) }}</div>
            <div class="es-label">Revenue Generated</div>
        </div>
    </div>
    <div class="enroll-stat stagger-3">
        <div class="es-icon" style="background:rgba(245,158,11,0.1); color:#d97706;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="es-val" style="color:#d97706;">{{ $pendingPayments }}</div>
            <div class="es-label">Pending Payments</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="enroll-filters">
    <div>
        <label class="form-label">Search Order</label>
        <div style="display:flex;align-items:center;gap:8px;background:var(--surface);border:1.5px solid var(--border);border-radius:var(--r);padding:9px 14px;">
            <i class="fas fa-search" style="color:var(--text-muted); font-size:13px;"></i>
            <input type="text" placeholder="Order ID, student name or item…" style="background:none;border:none;outline:none;font-family:inherit;font-size:13.5px;width:100%;color:var(--text);">
        </div>
    </div>
    <div>
        <label class="form-label">Status</label>
        <select class="form-input">
            <option>All Status</option>
            <option>Enrolled</option>
            <option>Pending</option>
            <option>Cancelled</option>
            <option>Refunded</option>
        </select>
    </div>
    <div>
        <label class="form-label">Date Range</label>
        <select class="form-input">
            <option>Last 30 Days</option>
            <option>Today</option>
            <option>This Month</option>
            <option>All Time</option>
        </select>
    </div>
    <div>
        <label class="form-label">&nbsp;</label>
        <button class="btn btn-ghost" style="height:42px; border:1px solid var(--border);">
            <i class="fas fa-rotate-left"></i> Reset
        </button>
    </div>
</div>

<!-- Table -->
<div class="card animate-scale-in">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Student</th>
                    <th>Items Bought</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                @php
                    $badgeClass = match($order->payment_status) {
                        'paid' => 'badge-success',
                        'pending'  => 'badge-warning',
                        'failed' => 'badge-danger',
                        default => 'badge-info',
                    };
                    $label = ucfirst($order->payment_status ?? 'Pending');
                @endphp
                <tr>
                    <td style="font-weight:700; color:var(--primary);">{{ $order->order_number ?? '#ORD-'.$order->id }}</td>
                    <td>
                        <div class="student-cell">
                            <img src="{{ $order->student?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($order->student?->name ?? 'Unknown').'&background=1565C0&color=fff' }}" alt="">
                            <div>
                                <span style="display:block; font-weight:600; font-size:13.5px;">{{ $order->student?->name ?? 'Unknown Student' }}</span>
                                <small style="color:var(--text-muted);">{{ $order->student?->email ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:500;">
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items as $orderItem)
                                <span style="display:inline-block; margin-bottom: 2px;">• {{ $orderItem->item?->title ?? $orderItem->item?->name ?? ucfirst($orderItem->item_type) }}</span><br>
                            @endforeach
                        @else
                            <span style="color:var(--text-muted);">N/A</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $order->created_at->format('M d, Y') }}</td>
                    <td style="font-weight:700;">₹{{ number_format($order->total_amount ?? 0) }}</td>
                    <td>
                        <span class="badge badge-neutral" style="text-transform:uppercase;">{{ $order->payment_method ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </td>
                    <td style="text-align:right;">
                        <a href="/orders/{{ $order->id }}" class="btn btn-ghost btn-sm" title="View Order Details">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex-between" style="padding:14px 20px; border-top: 1px solid var(--border);">
        <p style="font-size:13px; color:var(--text-muted);">Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</p>
        <div style="display:flex; gap:6px;">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection
