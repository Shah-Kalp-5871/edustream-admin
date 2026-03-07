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
                <div class="chart-subtitle">Daily enrollments over the last 30 days</div>
            </div>
            <div>
                <span class="badge badge-success">+{{ rand(5, 20) }}% growth</span>
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
        <div class="chart-container" style="height:180px; display:flex; align-items:center; justify-content:center; margin-top:20px;">
            <canvas id="contentDonut"></canvas>
        </div>
        <div style="display:flex; flex-direction:column; gap:6px; margin-top:20px;">
            @php
                $colors = ['#1565C0','#7c3aed','#ec4899','#ef4444'];
                $i = 0;
            @endphp
            @foreach($contentDistribution as $type => $count)
            <div style="display:flex; align-items:center; gap:8px; font-size:12.5px;">
                <span style="width:10px;height:10px;border-radius:50%;background:{{ $colors[$i % 4] }};flex-shrink:0;"></span>
                <span style="flex:1; color:var(--text-2);">{{ $type }}</span>
                <span style="font-weight:700; color:var(--text);">{{ number_format($count) }}</span>
            </div>
            @php $i++; @endphp
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
                <div class="chart-subtitle">Total ₹ earned per course (Paid)</div>
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

        @forelse($topCourses as $index => $course)
        <div class="top-course-row">
            @php
                $rowColors = ['#1565C0','#7c3aed','#059669','#d97706','#f97316'];
                $currColor = $rowColors[$index % 5];
            @endphp
            <div class="tc-rank" style="background:{{ $currColor }}22; color:{{ $currColor }};">{{ $index + 1 }}</div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:600; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $course->name }}</div>
                <div class="tc-bar" style="width:{{ $course->percentage }}%; background:{{ $currColor }};"></div>
            </div>
            <span style="font-size:13px; font-weight:700; color:var(--text); flex-shrink:0;">{{ number_format($course->enrollments_count) }}</span>
        </div>
        @empty
        <div style="padding: 40px; text-align: center; color: var(--text-muted); font-size: 13px;">
            No courses found.
        </div>
        @endforelse
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const primaryColor = '#1565C0';

    // ---- Enrollment Line Chart ----
    const enrollLabels = @json($enrollmentLabels);
    const enrollData   = @json($enrollmentValues);

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
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    border: { display: false },
                    ticks: { font: { size: 10 }, precision: 0 }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        autoSkip: true,
                        maxRotation: 0,
                        maxTicksLimit: 7
                    }
                }
            }
        }
    });

    // ---- Content Donut ----
    new Chart(document.getElementById('contentDonut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($contentDistribution)),
            datasets: [{
                data: @json(array_values($contentDistribution)),
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
            cutout: '72%',
        }
    });

    // ---- Revenue Bar Chart ----
    @php
        $revLabels = $revenueByCourse->pluck('name');
        $revData = $revenueByCourse->pluck('revenue');
    @endphp

    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($revLabels),
            datasets: [{
                label: 'Revenue (₹)',
                data: @json($revData),
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
                        font: { size: 10 },
                        callback: val => val >= 1000 ? '₹' + (val/1000).toFixed(0) + 'k' : '₹' + val
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        autoSkip: true,
                        maxTicksLimit: 5
                    }
                }
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
            // In a real app, this would trigger an AJAX reload
            Toast.fire({
                icon: 'info',
                title: 'Filtering by ' + btn.textContent.trim() + '...'
            });
        });
    });
</script>
@endsection
