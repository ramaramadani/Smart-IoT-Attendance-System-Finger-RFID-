@extends('layouts.app')

@section('title', 'Attendance Logs & Summaries')

@section('content')

<!-- Section: Filter & Export Actions -->
<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" action="{{ route('logs') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Search Employee Name</label>
            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Enter name..." style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Date</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="margin-bottom: 0;">
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">Search</button>
            <a href="{{ route('logs') }}" class="btn" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">Reset</a>
        </div>
        
        <!-- Export Action Buttons -->
        <div style="flex-basis: 100%; display: flex; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--glass-border);">
            <div style="margin-right: auto; color: var(--text-muted); font-size: 0.875rem; align-self: center;">Export Search Results:</div>
            
            <a href="{{ route('logs.export.pdf', request()->all()) }}" target="_blank" class="btn btn-danger" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                PDF
            </a>
            <a href="{{ route('logs.export.excel', request()->all()) }}" class="btn btn-success" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Excel (CSV)
            </a>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Work Duration</th>
                    <th>Status</th>
                    <th>Lateness (Limit 07:00)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->Tanggal ? $log->Tanggal->format('d M Y') : '-' }}</td>
                    <td style="font-weight: 600;">{{ $log->employee->Nama ?? 'Unknown' }}</td>
                    <td>{{ $log->employee->department->Nama_departemen ?? '-' }}</td>
                    <td>{{ $log->Jam_masuk ?? '-' }}</td>
                    <td>{{ $log->Jam_keluar ?? '-' }}</td>
                    <td>{{ $log->Jam_keluar ? $log->Durasi . ' Mins' : '-' }}</td>
                    <td>
                        @if($log->Status === 'hadir')
                            <span class="badge badge-success">Hadir</span>
                        @elseif($log->Status === 'telat')
                            <span class="badge badge-error">Terlambat</span>
                        @elseif($log->Status === 'izin')
                            <span class="badge badge-warning">Izin</span>
                        @elseif($log->Status === 'sakit')
                            <span class="badge badge-info">Sakit</span>
                        @else
                            <span class="badge" style="background: rgba(255,255,255,0.1); color: #fff;">{{ ucfirst($log->Status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->Status === 'telat' && $log->late_minutes > 0)
                            <span style="color: var(--error); font-weight: bold;">Late {{ $log->late_minutes }} Minutes</span>
                        @elseif($log->Status === 'hadir')
                            <span style="color: #4ade80;">On Time</span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $log->Keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 3rem;">No attendance data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $logs->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
