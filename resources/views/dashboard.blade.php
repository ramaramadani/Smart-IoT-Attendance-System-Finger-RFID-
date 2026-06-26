@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Stats Cards -->
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 0;">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">Today</div>
        <div style="font-size: 2rem; font-weight: 700; color: #60a5fa;">{{ $dailyCount }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">Attendance</span></div>
    </div>
    
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 0;">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">This Week</div>
        <div style="font-size: 2rem; font-weight: 700; color: #34d399;">{{ $weeklyCount }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">Attendance</span></div>
    </div>
    
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 0;">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">This Month</div>
        <div style="font-size: 2rem; font-weight: 700; color: #a78bfa;">{{ $monthlyCount }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">Attendance</span></div>
    </div>
    
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 0;">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">This Year</div>
        <div style="font-size: 2rem; font-weight: 700; color: #f472b6;">{{ $yearlyCount }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">Attendance</span></div>
    </div>
</div>

<div class="glass-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">Attendance Activity Chart</h3>
        <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
            <select name="filter" onchange="document.getElementById('filterForm').submit();" class="form-control" style="width: auto; padding: 0.5rem 2rem 0.5rem 1rem; margin-bottom: 0;">
                <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Daily (Hourly)</option>
                <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Weekly (7 Days)</option>
                <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly (30 Days)</option>
                <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly (Per Month)</option>
            </select>
        </form>
    </div>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)');   
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Total Attendance',
                data: {!! json_encode($chartData) !!},
                borderColor: '#6366f1',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: 'Outfit', size: 13 },
                    bodyFont: { family: 'Outfit', size: 14, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: 'Outfit' }, stepSize: 1 }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: 'Outfit' } }
                }
            }
        }
    });
</script>
@endpush
