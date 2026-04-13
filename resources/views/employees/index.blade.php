@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="glass-panel" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-weight: 600;">Tambah Karyawan Baru</h3>
    <form action="{{ route('employees.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        @csrf
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" style="margin-bottom: 0;" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">RFID UID (Kartu)</label>
            <input type="text" name="rfid_uid" class="form-control" style="margin-bottom: 0;">
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Fingerprint ID (Angka)</label>
            <input type="text" name="finger_id" class="form-control" style="margin-bottom: 0;">
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; padding-bottom: 0.75rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="width: 1.25rem; height: 1.25rem;">
            <label for="is_active">Aktif</label>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Tambah Karyawan</button>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>RFID UID</th>
                    <th>Finger ID</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                <tr>
                    <td style="font-weight: 500;">{{ $emp->name }}</td>
                    <td>{{ $emp->rfid_uid ?? '-' }}</td>
                    <td>{{ $emp->finger_id ?? '-' }}</td>
                    <td>
                        @if($emp->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-error">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <!-- Very simple toggle form for demonstration -->
                        <form action="{{ route('employees.update', $emp) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $emp->name }}">
                            <input type="hidden" name="rfid_uid" value="{{ $emp->rfid_uid }}">
                            <input type="hidden" name="finger_id" value="{{ $emp->finger_id }}">
                            @if(!$emp->is_active)
                                <input type="hidden" name="is_active" value="1">
                                <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Aktifkan</button>
                            @else
                                <button type="submit" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.4);">Nonaktifkan</button>
                            @endif
                        </form>
                        <form action="{{ route('employees.destroy', $emp) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Hapus karyawan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $employees->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
