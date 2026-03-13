@extends('layouts.app', ['title' => 'Dashboard'])

@section('subtitle', 'Welcome back! Here\'s what\'s happening with EduStream today.')

@section('actions')
    <button class="btn btn-secondary">
        <i class="fas fa-download"></i> Export Report
    </button>
    <a href="{{ url('content') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Content
    </a>
@endsection

@section('styles')
<style>
    /* ---- Stat Cards ---- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 22px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: box-shadow var(--tr), transform var(--tr);
    }

    .stat-card:hover {
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }

    .stat-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }

    .stat-change {
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 3px;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .stat-change.up { background: #ecfdf5; color: #059669; }
    .stat-change.down { background: #fef2f2; color: #dc2626; }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 30px;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 4px;
    }

    /* Sparkline bars (CSS only) */
    .sparkline {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 24px;
    }

    .sparkline span {
        flex: 1;
        border-radius: 2px 2px 0 0;
        min-height: 4px;
        opacity: 0.5;
        transition: opacity var(--tr);
    }

    .stat-card:hover .sparkline span { opacity: 0.85; }

    /* Revenue card (gradient) */
    .stat-card.featured {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-color: transparent;
        box-shadow: 0 8px 24px rgba(21,101,192,0.35);
        color: #fff;
    }

    .stat-card.featured .stat-value,
    .stat-card.featured .stat-label { color: #fff; }
    .stat-card.featured .stat-label { opacity: 0.75; }
    .stat-card.featured .stat-change { background: rgba(255,255,255,0.18); color: #fff; }
    .stat-card.featured .stat-icon { background: rgba(255,255,255,0.15); color: #fff; }
    .stat-card.featured .sparkline span { background: rgba(255,255,255,0.5); }

    /* ---- Bottom grid ---- */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 20px;
    }

    @media (max-width: 1200px) {
        .bottom-grid { grid-template-columns: 1fr; }
    }

    /* Enrollment table */
    .enroll-table td:first-child { font-weight: 700; color: var(--primary); }

    /* Activity Feed */
    .activity-feed {
        display: flex;
        flex-direction: column;
    }

    .feed-item {
        display: flex;
        gap: 12px;
        padding: 14px 22px;
        border-bottom: 1px solid #f0f6ff;
        transition: background var(--tr);
    }

    .feed-item:last-child { border-bottom: none; }
    .feed-item:hover { background: var(--surface-2); }

    .feed-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
    }

    .feed-content { flex: 1; }

    .feed-title {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 2px;
    }

    .feed-title strong { font-weight: 700; }

    .feed-time {
        font-size: 11.5px;
        color: var(--text-muted);
    }

    .feed-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        align-self: center;
    }
</style>
@endsection

@section('content')

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Students -->
    <div class="stat-card stagger-1">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(21,101,192,0.1); color: var(--primary);">
                <i class="fas fa-users"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +{{ rand(5, 15) }}%</span>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_students']) }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="sparkline">
            @for($i=0; $i<8; $i++)
                <span style="height:{{ rand(40, 100) }}%; background:#1565C0;"></span>
            @endfor
        </div>
    </div>

    <!-- Total Content -->
    <div class="stat-card stagger-2">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #7c3aed;">
                <i class="fas fa-folder-open"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +{{ rand(2, 8) }}%</span>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['content_items']) }}</div>
            <div class="stat-label">Content Items</div>
        </div>
        <div class="sparkline">
            @for($i=0; $i<8; $i++)
                <span style="height:{{ rand(40, 100) }}%; background:#7c3aed;"></span>
            @endfor
        </div>
    </div>

    <!-- Total Enrollments -->
    <div class="stat-card stagger-3">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(5,150,105,0.1); color: #059669;">
                <i class="fas fa-ticket"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +{{ rand(10, 30) }}%</span>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_enrollments']) }}</div>
            <div class="stat-label">Total Enrollments</div>
        </div>
        <div class="sparkline">
            @for($i=0; $i<8; $i++)
                <span style="height:{{ rand(40, 100) }}%; background:#059669;"></span>
            @endfor
        </div>
    </div>

    <!-- Total Quizzes -->
    <div class="stat-card stagger-4">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #d97706;">
                <i class="fas fa-brain"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +{{ rand(1, 5) }}%</span>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_quizzes']) }}</div>
            <div class="stat-label">Active Quizzes</div>
        </div>
        <div class="sparkline">
            @for($i=0; $i<8; $i++)
                <span style="height:{{ rand(40, 100) }}%; background:#d97706;"></span>
            @endfor
        </div>
    </div>

    <!-- Revenue (featured) -->
    <div class="stat-card featured stagger-5">
        <div class="stat-card-top">
            <div class="stat-icon">
                <i class="fas fa-indian-rupee-sign"></i>
            </div>
            <span class="stat-change"><i class="fas fa-arrow-trend-up"></i> +{{ rand(10, 25) }}%</span>
        </div>
        <div>
            <div class="stat-value">₹{{ number_format($stats['total_revenue']) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="sparkline">
            @for($i=0; $i<8; $i++)
                <span style="height:{{ rand(40, 100) }}%;"></span>
            @endfor
        </div>
    </div>
</div>

<!-- Bottom Grid: Enrollments + Activity -->
<div class="bottom-grid">

    <!-- Recent Enrollments -->
    <div class="card animate-scale-in" style="animation-delay:0.1s;">
        <div class="flex-between card-pad" style="border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 16px; font-weight: 700;">Recent Enrollments</h3>
            <a href="{{ url('orders') }}" class="btn btn-ghost btn-sm">View All <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
        </div>
        <div class="table-wrap">
            <table class="data-table enroll-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEnrollments as $enrollment)
                    <tr>
                        <td style="font-weight:700; color:var(--primary);">#ENR-{{ $enrollment->id }}</td>
                        <td>{{ $enrollment->student->name ?? 'N/A' }}</td>
                        <td>{{ $enrollment->course->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $enrollment->status == 'active' ? 'success' : ($enrollment->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $enrollment->created_at->format('d M, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px; color: var(--text-muted);">No recent enrollments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="card animate-scale-in" style="animation-delay:0.15s;">
        <div class="flex-between card-pad" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700;">Activity Feed</h3>
            <span class="badge badge-info">Live</span>
        </div>
        <div class="activity-feed">
            @forelse($activityLogs as $log)
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($log->admin->name ?? 'System') }}&background=1565C0&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>{{ $log->admin->name ?? 'System' }}</strong> {{ $log->action }}</div>
                    <div class="feed-time">{{ $log->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div style="padding: 20px; text-align: center; color: var(--text-muted);">
                <i class="fas fa-clock-rotate-left" style="font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                No recent activity.
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
