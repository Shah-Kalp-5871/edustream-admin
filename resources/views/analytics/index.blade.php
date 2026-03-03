@extends('layouts.app', ['title' => 'Analytics'])

@section('subtitle', 'Enrollment trends, revenue insights, and content performance.')

@section('actions')
    <div style="display:flex; gap:6px;" id="periodTabs">
        <button class="btn btn-primary btn-sm period-btn" data-period="7">7 Days</button>
        <button class="btn btn-ghost btn-sm period-btn" data-period="30">30 Days</button>
        <button class="btn btn-ghost btn-sm period-btn" data-period="90">90 Days</button>
    </div>
@endsection

@section('styles')
<style>
    .analytics-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .analytics-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media(max-width:1100px){
        .analytics-grid  { grid-template-columns: 1fr; }
        .analytics-bottom { grid-template-columns: 1fr; }
    }

    .chart-card { position: relative; }

    .chart-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .chart-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .chart-container {
        height: 260px;
        position: relative;
        margin-top: 14px;
    }

    .top-course-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f6ff;
    }

    .top-course-row:last-child { border-bottom: none; }

    .tc-rank {
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .tc-bar {
        height: 6px;
        background: var(--primary);
        border-radius: 3px;
        margin-top: 4px;
        transition: width 0.5s var(--ease);
    }
</style>
@endsection

@section('content')

<div class="analytics-grid">

    <!-- Enrollment Over Time -->
    <div class="card card-pad chart-card animate-scale-in">
        <div class="flex-between">
            <div>
                <div class="chart-title">Enrollment Trends</div>
                <div class="chart-subtitle">Daily enrollments over selected period</div>
            </div>
            <div>
                <span class="badge badge-success">+24% this period</span>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>

    <!-- Donut: Content Distribution -->
    <div class="card card-pad chart-card animate-scale-in" style="animation-delay:0.08s;">
        <div>
            <div class="chart-title">Content Types</div>
            <div class="chart-subtitle">Distribution of content items</div>
        </div>
        <div class="chart-container" style="height:200px; display:flex; align-items:center; justify-content:center;">
            <canvas id="contentDonut"></canvas>
        </div>
        <div style="display:flex; flex-direction:column; gap:6px; margin-top:10px;">
            @foreach([['Videos','#1565C0',220],['Quizzes','#7c3aed',86],['PDFs','#ec4899',94],['Live','#ef4444',12]] as $ct)
            <div style="display:flex; align-items:center; gap:8px; font-size:12.5px;">
                <span style="width:10px;height:10px;border-radius:50%;background:{{ $ct[1] }};flex-shrink:0;"></span>
                <span style="flex:1; color:var(--text-2);">{{ $ct[0] }}</span>
                <span style="font-weight:700; color:var(--text);">{{ $ct[2] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

<div class="analytics-bottom">

    <!-- Revenue Bar Chart -->
    <div class="card card-pad chart-card animate-scale-in" style="animation-delay:0.12s;">
        <div class="flex-between">
            <div>
                <div class="chart-title">Revenue by Course</div>
                <div class="chart-subtitle">Total ₹ earned per course</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Courses -->
    <div class="card card-pad animate-scale-in" style="animation-delay:0.16s;">
        <div class="chart-title" style="margin-bottom:4px;">Top Performing Courses</div>
        <div class="chart-subtitle" style="margin-bottom:14px;">By enrollment count</div>

        @php
        $courses = [
            ['Full Stack Web Dev', 840, 100, '#1565C0', 1],
            ['Advanced React', 620, 74, '#7c3aed', 2],
            ['Laravel Mastery', 520, 62, '#059669', 3],
            ['Python Data Sci.', 390, 46, '#d97706', 4],
            ['UI/UX Fundamentals', 280, 33, '#f97316', 5],
        ];
        @endphp

        @foreach($courses as $c)
        <div class="top-course-row">
            <div class="tc-rank" style="background:{{ $c[3] }}22; color:{{ $c[3] }};">{{ $c[4] }}</div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:600; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $c[0] }}</div>
                <div class="tc-bar" style="width:{{ $c[2] }}%; background:{{ $c[3] }};"></div>
            </div>
            <span style="font-size:13px; font-weight:700; color:var(--text); flex-shrink:0;">{{ $c[1] }}</span>
        </div>
        @endforeach
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const primaryColor = '#1565C0';
    const lightBlue = '#42A5F5';

    // ---- Enrollment Line Chart ----
    const enrollLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    const enrollData   = [42, 68, 55, 90, 78, 115, 98];

    const enrollCtx = document.getElementById('enrollmentChart').getContext('2d');
    const gradient = enrollCtx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(21,101,192,0.18)');
    gradient.addColorStop(1, 'rgba(21,101,192,0)');

    new Chart(enrollCtx, {
        type: 'line',
        data: {
            labels: enrollLabels,
            datasets: [{
                label: 'Enrollments',
                data: enrollData,
                borderColor: primaryColor,
                backgroundColor: gradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: primaryColor,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, border: { display: false }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // ---- Content Donut ----
    new Chart(document.getElementById('contentDonut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Videos', 'Quizzes', 'PDFs', 'Live'],
            datasets: [{
                data: [220, 86, 94, 12],
                backgroundColor: ['#1565C0','#7c3aed','#ec4899','#ef4444'],
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            cutout: '68%',
        }
    });

    // ---- Revenue Bar Chart ----
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Full Stack', 'React', 'Laravel', 'Python', 'UI/UX'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [168000, 124000, 78000, 136000, 28000],
                backgroundColor: ['#1565C0','#7c3aed','#059669','#d97706','#f97316'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    border: { display: false },
                    ticks: {
                        font: { size: 11 },
                        callback: val => '₹' + (val/1000).toFixed(0) + 'k'
                    }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // ---- Period tabs ----
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.period-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-ghost');
            });
            btn.classList.remove('btn-ghost');
            btn.classList.add('btn-primary');
        });
    });
</script>
@endsection
