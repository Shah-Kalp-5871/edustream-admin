@extends('layouts.app', ['title' => 'Student Profile'])

@section('subtitle', 'Viewing detailed profile and activity for ' . $student->name)

@section('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    @media(max-width: 992px) {
        .profile-grid { grid-template-columns: 1fr; }
    }

    .profile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 30px 24px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px;
        border: 4px solid var(--surface-2);
        object-fit: cover;
    }

    .info-group {
        text-align: left;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13.5px;
    }

    .info-label { color: var(--text-muted); }
    .info-value { font-weight: 600; color: var(--text); }

    .tab-nav {
        display: flex;
        gap: 24px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 24px;
    }

    .tab-link {
        padding: 12px 4px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .tab-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .enrollment-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        margin-bottom: 12px;
        transition: transform 0.2s;
    }

    .enrollment-card:hover {
        transform: translateX(4px);
        border-color: var(--primary-light);
    }

    .course-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
</style>
@endsection

@section('content')
<div class="animate-fade-up">
    <!-- Back Button -->
    <div style="margin-bottom: 24px;">
        <a href="{{ url('/users') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <div class="profile-grid">
        <!-- Sidebar: Basic Info -->
        <div class="profile-card">
            <img src="{{ $student->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=1565C0&color=fff&size=256' }}" class="profile-avatar" alt="">
            <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">{{ $student->name }}</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">Student ID: #{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</p>
            
            <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 24px;">
                <span class="badge {{ $student->status === 'active' ? 'badge-success' : 'badge-danger' }}" style="padding: 4px 12px;">
                    {{ ucfirst($student->status) }}
                </span>
                <span class="badge {{ $student->plan === 'premium' ? 'badge-warning' : 'badge-info' }}" style="padding: 4px 12px; background: {{ $student->plan === 'premium' ? '#fffbeb' : '#eff6ff' }}; color: {{ $student->plan === 'premium' ? '#d97706' : '#1d4ed8' }};">
                    {{ ucfirst($student->plan) }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <form action="{{ url('/users/'.$student->id.'/toggle-status') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $student->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" style="width: 100%; font-size: 13px;">
                        <i class="fas fa-{{ $student->status === 'active' ? 'ban' : 'check-circle' }}"></i>
                        {{ $student->status === 'active' ? 'Block User' : 'Unblock' }}
                    </button>
                </form>
                <a href="{{ url('/users/'.$student->id.'/edit') }}" class="btn btn-outline" style="width: 100%; font-size: 13px;">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>

            <div class="info-group">
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value">{{ $student->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone Number</span>
                    <span class="info-value">{{ $student->mobile ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Joined On</span>
                    <span class="info-value">{{ $student->created_at->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Activity</span>
                    <span class="info-value">{{ $student->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content: Courses & Activity -->
        <div class="card" style="padding: 24px;">
            <div class="tab-nav">
                <a href="#" class="tab-link active">Enrolled Courses ({{ $student->enrollments->count() }})</a>
                <a href="#" class="tab-link">Order History</a>
                <a href="#" class="tab-link">Device Log</a>
            </div>

            <div id="enrolled-courses">
                @forelse($student->enrollments as $enrollment)
                <div class="enrollment-card">
                    <div class="course-icon" style="background: {{ $enrollment->course->color_code ?? '#E3F2FD' }}20; color: {{ $enrollment->course->color_code ?? '#1565C0' }};">
                        <i class="{{ $enrollment->course->icon_url ?? 'fas fa-book' }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 4px;">{{ $enrollment->course->name }}</h4>
                        <p style="font-size: 12px; color: var(--text-muted);">Enrolled on {{ $enrollment->created_at->format('M d, Y') }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="badge badge-success" style="font-size: 10px;">Active</span>
                    </div>
                </div>
                @empty
                <div style="padding: 48px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-book-open" style="font-size: 40px; margin-bottom: 16px; opacity: 0.2;"></i>
                    <p>This student is not enrolled in any courses yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
