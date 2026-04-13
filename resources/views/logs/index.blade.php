@extends('layouts.app')

@section('title', 'Log Presensi Fingerprint')

@section('content')
<!-- Filter Form -->
<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" action="{{ route('logs') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Cari Nama Karyawan</label>
            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Masukkan nama..." style="margin-bottom: 0;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="margin-bottom: 0;">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Cari</button>
            <a href="{{ route('logs') }}" class="btn btn-danger" style="margin-left: 0.5rem;">Reset</a>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu (WIB)</th>
                    <th>Nama Karyawan</th>
                    <th>Tipe Akses</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->scanned_at->format('d M Y, H:i:s') }}</td>
                    <td style="font-weight: 500;">{{ $log->employee->name ?? 'Unknown' }}</td>
                    <td>
                        <span class="badge badge-info">Fingerprint</span>
                    </td>
                    <td><span style="color: #4ade80;">Success</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 3rem;">Data fingerprint tidak ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $logs->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
