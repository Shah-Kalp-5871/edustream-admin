@extends('layouts.app', ['title' => 'Order Details'])

@section('subtitle', 'View complete details of this order and purchased items.')

@section('actions')
    <a href="/orders" class="btn btn-ghost" style="border:1px solid var(--border);">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
    <a href="/orders/{{ $order->id }}/invoice" class="btn btn-secondary">
        <i class="fas fa-file-invoice"></i> View Invoice
    </a>
@endsection

@section('styles')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media(max-width:900px){
        .detail-grid { grid-template-columns: 1fr; }
    }

    .detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }
    
    .detail-card h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .info-label {
        font-size: 13.5px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: var(--text);
        text-align: right;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px dashed var(--border);
    }

    .user-profile img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border);
    }

    .user-profile h4 {
        margin: 0 0 4px 0;
        font-size: 18px;
        font-family: 'Outfit', sans-serif;
    }

    .user-profile p {
        margin: 0;
        font-size: 13.5px;
        color: var(--text-muted);
    }

    /* Items table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .items-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        background: rgba(0,0,0,0.02);
    }

    .items-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }
    
    .item-icon {
        width: 40px; height: 40px;
        border-radius: var(--r);
        background: rgba(21, 101, 192, 0.1);
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }
</style>
@endsection

@section('content')

@php
    $badgeClass = match($order->payment_status) {
        'paid' => 'badge-success',
        'pending'  => 'badge-warning',
        'failed' => 'badge-danger',
        default => 'badge-info',
    };
@endphp

<div class="detail-grid">
    <!-- Order Information -->
    <div class="detail-card animate-scale-in stagger-1">
        <h3><i class="fas fa-file-invoice text-primary"></i> Order Information</h3>
        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Order ID</span>
                <span class="info-value" style="color:var(--primary);">{{ $order->order_number ?? '#ORD-'.$order->id }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date Placed</span>
                <span class="info-value">{{ $order->created_at->format('F d, Y - h:i A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Payment Status</span>
                <span class="info-value"><span class="badge {{ $badgeClass }}">{{ ucfirst($order->payment_status ?? 'Pending') }}</span></span>
            </div>
            <div class="info-item">
                <span class="info-label">Payment Method</span>
                <span class="info-value" style="text-transform: uppercase;">{{ $order->payment_method ?? 'N/A' }}</span>
            </div>
            @if($order->razorpay_payment_id)
            <div class="info-item">
                <span class="info-label">Transaction ID</span>
                <span class="info-value" style="font-family: monospace;">{{ $order->razorpay_payment_id }}</span>
            </div>
            @endif
            <div class="info-item" style="border-top:1px dashed var(--border); padding-top:16px;">
                <span class="info-label" style="font-size: 15px;">Total Amount</span>
                <span class="info-value" style="font-size: 20px; color: #059669;">₹{{ number_format($order->total_amount ?? 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Student Details -->
    <div class="detail-card animate-scale-in stagger-2">
        <h3><i class="fas fa-user-graduate text-primary"></i> Student Information</h3>
        
        <div class="user-profile">
            <img src="{{ $order->student?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($order->student?->name ?? 'Unknown').'&background=1565C0&color=fff' }}" alt="{{ $order->student?->name }}">
            <div>
                <h4>{{ $order->student?->name ?? 'Unknown Student' }}</h4>
                <p>Status: <span style="font-weight:600; color:var(--text);">{{ ucfirst($order->student?->status ?? 'Active') }}</span></p>
                @if($order->student?->plan)
                    <p style="margin-top:2px;">Plan: <span style="font-weight:600; text-transform:uppercase; color:var(--primary);">{{ $order->student->plan }}</span></p>
                @endif
            </div>
        </div>

        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Email Address</span>
                <span class="info-value">{{ $order->student?->email ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone Number</span>
                <span class="info-value">{{ $order->student?->mobile ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Registered Date</span>
                <span class="info-value">{{ $order->student?->created_at?->format('F d, Y') ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Items Purchased -->
<div class="detail-card animate-scale-in stagger-3" style="padding: 0; overflow:hidden;">
    <h3 style="padding: 24px 24px 0 24px;"><i class="fas fa-box-open text-primary"></i> Items Purchased in this Order</h3>
    
    <div style="overflow-x: auto;">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th style="text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $orderItem)
                @php
                    $item = $orderItem->item;
                    $type = ucfirst($orderItem->item_type);
                @endphp
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <div class="item-icon">
                                <i class="fas {{ $type === 'Course' ? 'fa-graduation-cap' : ($type === 'Subject' ? 'fa-book' : 'fa-cube') }}"></i>
                            </div>
                            <div>
                                <span style="font-weight:600; font-size: 14.5px; display:block;">{{ $item?->title ?? $item?->name ?? 'Unknown Product' }}</span>
                                <span style="font-size: 13px; color:var(--text-muted);">
                                    @if($type === 'Course' && $item?->category)
                                        {{ $item->category->name }}
                                    @elseif($type === 'Subject' && $item?->course)
                                        Course: {{ $item->course->title }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-neutral">{{ $type }}</span></td>
                    <td style="text-align: right; font-weight:700;">₹{{ number_format($orderItem->price ?? 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 32px; color:var(--border); margin-bottom:12px; display:block;"></i>
                        No explicit items found for this order.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="background: rgba(0,0,0,0.02); padding: 16px 24px; text-align: right; border-top: 1px solid var(--border);">
        <p style="margin:0; font-size:14px; font-weight:500;">Grand Total: <span style="font-size:20px; font-weight:700; color:#059669; margin-left:12px;">₹{{ number_format($order->total_amount ?? 0) }}</span></p>
    </div>
</div>

@endsection
