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
            <div class="us-val">{{ number_format($totalStudents) }}</div>
            <div class="us-label">Total Students</div>
        </div>
    </div>
    <div class="user-stat stagger-2">
        <div class="us-icon" style="background:rgba(5,150,105,0.1); color:#059669;">
            <i class="fas fa-circle-dot"></i>
        </div>
        <div>
            <div class="us-val" style="color:#059669;">{{ number_format($activeNow) }}</div>
            <div class="us-label">Active Now</div>
        </div>
    </div>
    <div class="user-stat stagger-3">
        <div class="us-icon" style="background:rgba(99,102,241,0.1); color:#6366f1;">
            <i class="fas fa-user-check"></i>
        </div>
        <div>
            <div class="us-val" style="color:#6366f1;">{{ $newToday > 0 ? '+' . $newToday : '0' }}</div>
            <div class="us-label">New Today</div>
        </div>
    </div>
    <div class="user-stat stagger-4">
        <div class="us-icon" style="background:rgba(245,158,11,0.1); color:#d97706;">
            <i class="fas fa-crown"></i>
        </div>
        <div>
            <div class="us-val" style="color:#d97706;">{{ number_format($premiumStudents) }}</div>
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
                    <th>Joined</th>
                    <th>Courses</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                <tr>
                    <td>
                        <div class="avatar-group">
                            <img src="{{ $s->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background=1565C0&color=fff' }}" alt="">
                            <div>
                                <span style="display:block; font-weight:600; font-size:13.5px;">{{ $s->name }}</span>
                                <small style="color:var(--text-muted);">{{ $s->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge {{ $s->plan === 'premium' ? 'rb-premium' : 'rb-free' }}">
                            {{ ucfirst($s->plan) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $s->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $s->created_at->format('M d, Y') }}</td>
                    <td style="font-weight:700;">{{ $s->enrollments->count() }}</td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            <a href="{{ url('/users/'.$s->id) }}" class="btn btn-ghost btn-sm" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ url('/users/'.$s->id.'/toggle-status') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" style="color:{{ $s->status==='active' ? '#ef4444' : '#059669' }};" title="{{ $s->status==='active' ? 'Block' : 'Unblock' }}">
                                    <i class="fas fa-{{ $s->status==='active' ? 'ban' : 'check-circle' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex-between" style="padding:14px 20px; border-top:1px solid var(--border);">
        <p style="font-size:13px; color:var(--text-muted);">Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</p>
        <div style="display:flex; gap:6px;">
            {{ $students->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection
