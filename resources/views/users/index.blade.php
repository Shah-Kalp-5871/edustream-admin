@extends('layouts.app', ['title' => 'Students'])

@section('subtitle', 'Manage your student community, track progress and activity.')

@section('actions')
    <button class="btn btn-secondary">
        <i class="fas fa-download"></i> Export
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add Student
    </button>
@endsection

@section('styles')
<style>
    .user-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media(max-width:900px) { .user-stats { grid-template-columns: repeat(2,1fr); } }

    .user-stat {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 16px 18px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all var(--tr);
    }

    .user-stat:hover { box-shadow: var(--shadow); transform: translateY(-1px); }

    .us-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .us-val {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
    }

    .us-label { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    .avatar-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar-group img {
        width: 34px; height: 34px;
        border-radius: 50%;
        border: 2px solid var(--surface);
        object-fit: cover;
    }

    .role-badge {
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .rb-premium { background: #fffbeb; color: #d97706; }
    .rb-free    { background: #eff6ff; color: var(--primary); }
</style>
@endsection

@section('content')

<!-- Student Stats -->
<div class="user-stats">
    <div class="user-stat stagger-1">
        <div class="us-icon" style="background:rgba(21,101,192,0.1); color:var(--primary);">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="us-val">14,250</div>
            <div class="us-label">Total Students</div>
        </div>
    </div>
    <div class="user-stat stagger-2">
        <div class="us-icon" style="background:rgba(5,150,105,0.1); color:#059669;">
            <i class="fas fa-circle-dot"></i>
        </div>
        <div>
            <div class="us-val" style="color:#059669;">142</div>
            <div class="us-label">Active Now</div>
        </div>
    </div>
    <div class="user-stat stagger-3">
        <div class="us-icon" style="background:rgba(99,102,241,0.1); color:#6366f1;">
            <i class="fas fa-user-check"></i>
        </div>
        <div>
            <div class="us-val" style="color:#6366f1;">+42</div>
            <div class="us-label">New Today</div>
        </div>
    </div>
    <div class="user-stat stagger-4">
        <div class="us-icon" style="background:rgba(245,158,11,0.1); color:#d97706;">
            <i class="fas fa-crown"></i>
        </div>
        <div>
            <div class="us-val" style="color:#d97706;">2,840</div>
            <div class="us-label">Premium Students</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card animate-scale-in" style="padding: 0;">
    <!-- Table header with search -->
    <div class="flex-between" style="padding: 16px 20px; border-bottom: 1px solid var(--border);">
        <div style="display:flex; align-items:center; gap:8px; background:var(--surface-2); border:1.5px solid var(--border); border-radius:var(--r); padding:8px 14px; min-width:320px;">
            <i class="fas fa-search" style="color:var(--text-muted); font-size:13px;"></i>
            <input type="text" placeholder="Search by name, email or phone…" style="background:none;border:none;outline:none;font-family:inherit;font-size:13.5px;width:100%;color:var(--text);">
        </div>
        <div style="display:flex; gap:8px;">
            <button class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <select class="form-input btn-sm" style="width:auto; padding:6px 28px 6px 10px; font-size:12.5px;">
                <option>All Students</option>
                <option>Premium</option>
                <option>Free</option>
                <option>Blocked</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Enrolled</th>
                    <th>Courses</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $students = [
                    ['name'=>'Arjun Sharma','email'=>'arjun@example.com','plan'=>'premium','status'=>'active','date'=>'Oct 12, 2025','courses'=>12,'color'=>'1565C0'],
                    ['name'=>'Priya Patel','email'=>'priya@example.com','plan'=>'free','status'=>'active','date'=>'Nov 03, 2025','courses'=>4,'color'=>'7c3aed'],
                    ['name'=>'Rahul Verma','email'=>'rahul@example.com','plan'=>'premium','status'=>'active','date'=>'Oct 28, 2025','courses'=>8,'color'=>'059669'],
                    ['name'=>'Sneha Mehta','email'=>'sneha@example.com','plan'=>'free','status'=>'blocked','date'=>'Sep 15, 2025','courses'=>2,'color'=>'ef4444'],
                    ['name'=>'Kunal Joshi','email'=>'kunal@example.com','plan'=>'free','status'=>'active','date'=>'Dec 01, 2025','courses'=>6,'color'=>'d97706'],
                    ['name'=>'Ananya Singh','email'=>'ananya@example.com','plan'=>'premium','status'=>'active','date'=>'Jan 10, 2026','courses'=>15,'color'=>'f97316'],
                ];
                @endphp

                @foreach($students as $s)
                <tr>
                    <td>
                        <div class="avatar-group">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($s['name']) }}&background={{ $s['color'] }}&color=fff" alt="">
                            <div>
                                <span style="display:block; font-weight:600; font-size:13.5px;">{{ $s['name'] }}</span>
                                <small style="color:var(--text-muted);">{{ $s['email'] }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge {{ $s['plan']==='premium' ? 'rb-premium' : 'rb-free' }}">
                            {{ ucfirst($s['plan']) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $s['status']==='active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($s['status']) }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $s['date'] }}</td>
                    <td style="font-weight:700;">{{ $s['courses'] }}</td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            <button class="btn btn-ghost btn-sm" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-ghost btn-sm" style="color:{{ $s['status']==='active' ? '#ef4444' : '#059669' }};" title="{{ $s['status']==='active' ? 'Block' : 'Unblock' }}">
                                <i class="fas fa-{{ $s['status']==='active' ? 'ban' : 'check-circle' }}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex-between" style="padding:14px 20px; border-top:1px solid var(--border);">
        <p style="font-size:13px; color:var(--text-muted);">Showing 1–6 of 14,250 students</p>
        <div style="display:flex; gap:6px;">
            <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">3</button>
            <span style="align-self:center; color:var(--text-muted);">…</span>
            <button class="btn btn-ghost btn-sm">1425</button>
            <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

@endsection
