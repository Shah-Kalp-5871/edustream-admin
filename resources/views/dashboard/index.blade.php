@extends('layouts.app', ['title' => 'Dashboard'])

@section('subtitle', 'Welcome back! Here\'s what\'s happening with EduStream today.')

@section('actions')
    <button class="btn btn-secondary">
        <i class="fas fa-download"></i> Export Report
    </button>
    <a href="/content" class="btn btn-primary">
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
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +12%</span>
        </div>
        <div>
            <div class="stat-value">14,284</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="sparkline">
            <span style="height:40%; background:#1565C0;"></span>
            <span style="height:55%; background:#1565C0;"></span>
            <span style="height:45%; background:#1565C0;"></span>
            <span style="height:70%; background:#1565C0;"></span>
            <span style="height:60%; background:#1565C0;"></span>
            <span style="height:80%; background:#1565C0;"></span>
            <span style="height:75%; background:#1565C0;"></span>
            <span style="height:100%; background:#1565C0;"></span>
        </div>
    </div>

    <!-- Total Content -->
    <div class="stat-card stagger-2">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #7c3aed;">
                <i class="fas fa-folder-open"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +5%</span>
        </div>
        <div>
            <div class="stat-value">452</div>
            <div class="stat-label">Content Items</div>
        </div>
        <div class="sparkline">
            <span style="height:50%; background:#7c3aed;"></span>
            <span style="height:60%; background:#7c3aed;"></span>
            <span style="height:55%; background:#7c3aed;"></span>
            <span style="height:65%; background:#7c3aed;"></span>
            <span style="height:60%; background:#7c3aed;"></span>
            <span style="height:75%; background:#7c3aed;"></span>
            <span style="height:80%; background:#7c3aed;"></span>
            <span style="height:90%; background:#7c3aed;"></span>
        </div>
    </div>

    <!-- Total Enrollments -->
    <div class="stat-card stagger-3">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(5,150,105,0.1); color: #059669;">
                <i class="fas fa-ticket"></i>
            </div>
            <span class="stat-change up"><i class="fas fa-arrow-trend-up"></i> +24%</span>
        </div>
        <div>
            <div class="stat-value">3,120</div>
            <div class="stat-label">Total Enrollments</div>
        </div>
        <div class="sparkline">
            <span style="height:30%; background:#059669;"></span>
            <span style="height:50%; background:#059669;"></span>
            <span style="height:65%; background:#059669;"></span>
            <span style="height:55%; background:#059669;"></span>
            <span style="height:70%; background:#059669;"></span>
            <span style="height:85%; background:#059669;"></span>
            <span style="height:90%; background:#059669;"></span>
            <span style="height:100%; background:#059669;"></span>
        </div>
    </div>

    <!-- Total Quizzes -->
    <div class="stat-card stagger-4">
        <div class="stat-card-top">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #d97706;">
                <i class="fas fa-brain"></i>
            </div>
            <span class="stat-change down"><i class="fas fa-arrow-trend-down"></i> -2%</span>
        </div>
        <div>
            <div class="stat-value">86</div>
            <div class="stat-label">Active Quizzes</div>
        </div>
        <div class="sparkline">
            <span style="height:90%; background:#d97706;"></span>
            <span style="height:80%; background:#d97706;"></span>
            <span style="height:85%; background:#d97706;"></span>
            <span style="height:75%; background:#d97706;"></span>
            <span style="height:70%; background:#d97706;"></span>
            <span style="height:65%; background:#d97706;"></span>
            <span style="height:60%; background:#d97706;"></span>
            <span style="height:55%; background:#d97706;"></span>
        </div>
    </div>

    <!-- Revenue (featured) -->
    <div class="stat-card featured stagger-5">
        <div class="stat-card-top">
            <div class="stat-icon">
                <i class="fas fa-indian-rupee-sign"></i>
            </div>
            <span class="stat-change"><i class="fas fa-arrow-trend-up"></i> +18%</span>
        </div>
        <div>
            <div class="stat-value">₹42,500</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="sparkline">
            <span style="height:40%;"></span>
            <span style="height:55%;"></span>
            <span style="height:45%;"></span>
            <span style="height:70%;"></span>
            <span style="height:80%;"></span>
            <span style="height:65%;"></span>
            <span style="height:90%;"></span>
            <span style="height:100%;"></span>
        </div>
    </div>
</div>

<!-- Bottom Grid: Enrollments + Activity -->
<div class="bottom-grid">

    <!-- Recent Enrollments -->
    <div class="card animate-scale-in" style="animation-delay:0.1s;">
        <div class="flex-between card-pad" style="border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 16px; font-weight: 700;">Recent Enrollments</h3>
            <a href="/orders" class="btn btn-ghost btn-sm">View All <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
        </div>
        <div class="table-wrap">
            <table class="data-table enroll-table">
                <thead>
                    <tr>
                        <th>Enrollment ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#ENR-3421</td>
                        <td>Arjun Sharma</td>
                        <td>Full Stack Web Dev</td>
                        <td style="font-weight:700;">₹1,999</td>
                        <td><span class="badge badge-success">Enrolled</span></td>
                    </tr>
                    <tr>
                        <td>#ENR-3420</td>
                        <td>Priya Patel</td>
                        <td>Advanced React Mastery</td>
                        <td style="font-weight:700;">₹2,499</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#ENR-3419</td>
                        <td>Rahul Verma</td>
                        <td>Laravel Backend Pro</td>
                        <td style="font-weight:700;">₹1,499</td>
                        <td><span class="badge badge-success">Enrolled</span></td>
                    </tr>
                    <tr>
                        <td>#ENR-3418</td>
                        <td>Sneha Mehta</td>
                        <td>Python Data Science</td>
                        <td style="font-weight:700;">₹3,499</td>
                        <td><span class="badge badge-danger">Cancelled</span></td>
                    </tr>
                    <tr>
                        <td>#ENR-3417</td>
                        <td>Kunal Joshi</td>
                        <td>UI/UX Fundamentals</td>
                        <td style="font-weight:700;">₹999</td>
                        <td><span class="badge badge-success">Enrolled</span></td>
                    </tr>
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
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name=Arjun+Sharma&background=1565C0&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>Arjun Sharma</strong> enrolled in Full Stack Web Dev</div>
                    <div class="feed-time">2 minutes ago</div>
                </div>
                <span class="feed-badge badge badge-success" style="font-size:10px; padding:2px 6px;">+₹1,999</span>
            </div>
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name=Priya+Patel&background=7c3aed&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>Priya Patel</strong> registered a new account</div>
                    <div class="feed-time">15 minutes ago</div>
                </div>
                <span class="badge badge-info" style="font-size:10px; padding:2px 6px;">New</span>
            </div>
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name=Rahul+Verma&background=059669&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>Rahul Verma</strong> completed "Module 1 Quiz"</div>
                    <div class="feed-time">32 minutes ago</div>
                </div>
                <span class="badge badge-warning" style="font-size:10px; padding:2px 6px;">82%</span>
            </div>
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name=Sneha+Mehta&background=d97706&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>Sneha Mehta</strong> cancelled enrollment in Python DS</div>
                    <div class="feed-time">1 hour ago</div>
                </div>
                <span class="badge badge-danger" style="font-size:10px; padding:2px 6px;">Refund</span>
            </div>
            <div class="feed-item">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=1565C0&color=fff" class="feed-avatar" alt="">
                <div class="feed-content">
                    <div class="feed-title"><strong>You</strong> added "CSS Flexbox Masterclass" video</div>
                    <div class="feed-time">3 hours ago</div>
                </div>
                <span class="badge badge-neutral" style="font-size:10px; padding:2px 6px;">Content</span>
            </div>
        </div>
    </div>

</div>

@endsection
