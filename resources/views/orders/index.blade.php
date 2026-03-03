@extends('layouts.app', ['title' => 'Enrollment Management'])

@section('subtitle', 'Track and manage all course enrollments and payments.')

@section('actions')
    <button class="btn btn-secondary">
        <i class="fas fa-file-invoice"></i> Export CSV
    </button>
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
            <div class="es-val">3,120</div>
            <div class="es-label">Total Enrollments</div>
        </div>
    </div>
    <div class="enroll-stat stagger-2">
        <div class="es-icon" style="background:rgba(5,150,105,0.1); color:#059669;">
            <i class="fas fa-indian-rupee-sign"></i>
        </div>
        <div>
            <div class="es-val" style="color:#059669;">₹4,25,000</div>
            <div class="es-label">Revenue Generated</div>
        </div>
    </div>
    <div class="enroll-stat stagger-3">
        <div class="es-icon" style="background:rgba(245,158,11,0.1); color:#d97706;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="es-val" style="color:#d97706;">23</div>
            <div class="es-label">Pending Payments</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="enroll-filters">
    <div>
        <label class="form-label">Search Enrollment</label>
        <div style="display:flex;align-items:center;gap:8px;background:var(--surface);border:1.5px solid var(--border);border-radius:var(--r);padding:9px 14px;">
            <i class="fas fa-search" style="color:var(--text-muted); font-size:13px;"></i>
            <input type="text" placeholder="Enrollment ID, student name or course…" style="background:none;border:none;outline:none;font-family:inherit;font-size:13.5px;width:100%;color:var(--text);">
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
                    <th>Enrollment ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $enrollments = [
                    ['id'=>'#ENR-7821','name'=>'Arjun Sharma','email'=>'arjun@example.com','course'=>'Full Stack Bundle','date'=>'Mar 01, 2026','amount'=>'₹1,999','payment'=>'UPI','status'=>'enrolled'],
                    ['id'=>'#ENR-7820','name'=>'Priya Patel','email'=>'priya@example.com','course'=>'Advanced React','date'=>'Mar 01, 2026','amount'=>'₹2,499','payment'=>'Card','status'=>'pending'],
                    ['id'=>'#ENR-7819','name'=>'Rahul Verma','email'=>'rahul@example.com','course'=>'Laravel Mastery','date'=>'Feb 28, 2026','amount'=>'₹1,499','payment'=>'UPI','status'=>'enrolled'],
                    ['id'=>'#ENR-7818','name'=>'Sneha Mehta','email'=>'sneha@example.com','course'=>'Python Data Science','date'=>'Feb 27, 2026','amount'=>'₹3,499','payment'=>'Card','status'=>'cancelled'],
                    ['id'=>'#ENR-7817','name'=>'Kunal Joshi','email'=>'kunal@example.com','course'=>'UI/UX Fundamentals','date'=>'Feb 26, 2026','amount'=>'₹999','payment'=>'UPI','status'=>'enrolled'],
                    ['id'=>'#ENR-7816','name'=>'Ananya Singh','email'=>'ananya@example.com','course'=>'Full Stack Bundle','date'=>'Feb 25, 2026','amount'=>'₹1,999','payment'=>'Net Banking','status'=>'refunded'],
                ];
                @endphp

                @foreach($enrollments as $e)
                @php
                    $badgeClass = match($e['status']) {
                        'enrolled' => 'badge-success',
                        'pending'  => 'badge-warning',
                        'cancelled' => 'badge-danger',
                        'refunded' => 'badge-neutral',
                        default => 'badge-info',
                    };
                    $label = ucfirst($e['status']);
                @endphp
                <tr>
                    <td style="font-weight:700; color:var(--primary);">{{ $e['id'] }}</td>
                    <td>
                        <div class="student-cell">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($e['name']) }}&background=1565C0&color=fff" alt="">
                            <div>
                                <span style="display:block; font-weight:600; font-size:13.5px;">{{ $e['name'] }}</span>
                                <small style="color:var(--text-muted);">{{ $e['email'] }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:500;">{{ $e['course'] }}</td>
                    <td style="color:var(--text-muted);">{{ $e['date'] }}</td>
                    <td style="font-weight:700;">{{ $e['amount'] }}</td>
                    <td>
                        <span class="badge badge-neutral">{{ $e['payment'] }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn btn-ghost btn-sm">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex-between" style="padding:14px 20px; border-top: 1px solid var(--border);">
        <p style="font-size:13px; color:var(--text-muted);">Showing 1–6 of 3,120 enrollments</p>
        <div style="display:flex; gap:6px;">
            <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">3</button>
            <span style="display:flex;align-items:center;padding:0 4px;color:var(--text-muted);">…</span>
            <button class="btn btn-ghost btn-sm">312</button>
            <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

@endsection
