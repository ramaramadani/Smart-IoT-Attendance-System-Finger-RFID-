@extends('layouts.app')

@section('title', 'Employee SP Accumulation')

@section('content')

<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Lateness Accumulation Summary (Limit 120 Minutes / 3 Months)</h3>
<p style="color: var(--text-muted); margin-bottom: 1.5rem;">The system automatically monitors the net lateness accumulation of each employee for the last 3 months.</p>

<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" action="{{ route('sp_report') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Search Employee Name</label>
            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Enter name..." style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">SP Status</label>
            <select name="status" class="form-control" style="margin-bottom: 0;">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="sp1" {{ request('status') == 'sp1' ? 'selected' : '' }}>SP 1 Only</option>
                <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Safe Status</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Filter</button>
            <a href="{{ route('sp_report') }}" class="btn btn-danger" style="margin-left: 0.5rem;">Reset</a>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Total Lateness (3 Months)</th>
                    <th>Warning Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spReport as $report)
                <tr>
                    <td style="font-weight: 600; font-size: 1.05rem; color: {{ $report->status === 'SP 1' ? '#fca5a5' : '#f8fafc' }};">{{ $report->employee->Nama }}</td>
                    <td>
                        <span style="font-size: 1.1rem; font-weight: bold; color: {{ $report->total_late >= 120 ? '#ef4444' : '#34d399' }};">{{ $report->total_late }} Minutes</span>
                    </td>
                    <td>
                        @if($report->status === 'SP 1')
                            <span class="badge badge-error" style="animation: pulse 2s infinite;">SP 1 ISSUED</span>
                        @else
                            <span class="badge badge-success">Safe</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        No lateness accumulation data available.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>

@endsection
