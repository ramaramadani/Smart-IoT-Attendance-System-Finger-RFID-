@extends('layouts.app')

@section('meta')
<meta http-equiv="refresh" content="30">
@endsection

@section('title', 'Access Monitoring')

@section('content')

<!-- Section 1: Live Monitoring -->
<div class="glass-panel" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">Live Monitoring</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">Employees currently inside the premises.</p>
        </div>
        <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.5rem 1rem; border-radius: 999px; font-weight: 600; font-size: 0.875rem;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success); box-shadow: 0 0 8px var(--success); animation: pulse 2s infinite;"></span>
            LIVE UPDATES
        </span>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
        @forelse($insideEmployees as $employee)
            <div style="padding: 1rem; display: flex; align-items: center; gap: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #a855f7); display: flex; justify-content: center; align-items: center; font-size: 1.2rem; font-weight: 700; color: white;">
                    {{ substr($employee->Nama, 0, 1) }}
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 1rem;">{{ $employee->Nama }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Checked in: {{ $employee->attendances->first() && $employee->attendances->first()->Jam_masuk ? \Carbon\Carbon::parse($employee->attendances->first()->Jam_masuk)->format('H:i') : '-' }}
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 2rem; border-radius: 1rem; border: 1px dashed var(--glass-border);">
                No employees detected inside the premises at this time.
            </div>
        @endforelse
    </div>
</div>

<!-- Section 2: RFID Logs Filter & Table -->
<h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; margin-top: 3rem;">Daily Access Data logs</h3>

<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" action="{{ route('monitoring') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Search Employee Name</label>
            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Enter name..." style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Date</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="margin-bottom: 0;">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Search</button>
            <a href="{{ route('monitoring') }}" class="btn btn-danger" style="margin-left: 0.5rem;">Reset</a>
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
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                    <th>Status</th>
                    <th>Detail Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rfidLogs as $log)
                <tr>
                    <td>{{ $log->Tanggal ? $log->Tanggal->format('d M Y') : '-' }}</td>
                    <td style="font-weight: 500;">{{ $log->employee->Nama ?? 'Unknown' }}</td>
                    <td>{{ $log->employee->department->Nama_departemen ?? '-' }}</td>
                    <td>{{ $log->Jam_masuk ?? '-' }}</td>
                    <td>{{ $log->Jam_keluar ?? '-' }}</td>
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
                            <span class="badge">{{ $log->Status }}</span>
                        @endif
                    </td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $log->Keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No access data found for this search.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $rfidLogs->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
