@extends('layouts.app')

@section('title', 'Employee Data (Karyawan)')

@section('content')
<div class="glass-panel" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Add New Employee</h3>
    <form action="{{ route('employees.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; align-items: end;">
        @csrf
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">NIP</label>
            <input type="text" name="Nip" class="form-control" style="margin-bottom: 0;" placeholder="e.g. NIP10023" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Full Name</label>
            <input type="text" name="Nama" class="form-control" style="margin-bottom: 0;" placeholder="e.g. Budi Santoso" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Gender (Jenis Kelamin)</label>
            <select name="Jenis_Kelamin" class="form-control" style="margin-bottom: 0;" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Position (Jabatan)</label>
            <input type="text" name="Jabatan" class="form-control" style="margin-bottom: 0;" placeholder="e.g. Staff IT" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Department</label>
            <select name="id_departemen" class="form-control" style="margin-bottom: 0;" required>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id_departemen }}">{{ $dept->Nama_departemen }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">RFID UID (Card)</label>
            <input type="text" name="id_RFID" class="form-control" style="margin-bottom: 0;" placeholder="e.g. RF9983">
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Fingerprint ID (Number)</label>
            <input type="text" name="id_fingerprint" class="form-control" style="margin-bottom: 0;" placeholder="e.g. 12">
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Joining Date</label>
            <input type="date" name="Tanggal_bergabung" class="form-control" style="margin-bottom: 0;" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Status</label>
            <select name="Status" class="form-control" style="margin-bottom: 0;" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px;">Add Employee</button>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>RFID UID</th>
                    <th>Finger ID</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td style="font-weight: bold; color: var(--text-muted);">{{ $emp->Nip }}</td>
                    <td style="font-weight: 600;">{{ $emp->Nama }}</td>
                    <td>{{ $emp->Jenis_Kelamin }}</td>
                    <td>{{ $emp->Jabatan }}</td>
                    <td>{{ $emp->department->Nama_departemen ?? '-' }}</td>
                    <td>{{ $emp->id_RFID ?? '-' }}</td>
                    <td>{{ $emp->id_fingerprint ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($emp->Tanggal_bergabung)->format('d M Y') }}</td>
                    <td>
                        @if($emp->Status === 'aktif')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-error">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('employees.update', $emp) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <!-- Keep old values, just toggle status -->
                            <input type="hidden" name="Nip" value="{{ $emp->Nip }}">
                            <input type="hidden" name="Nama" value="{{ $emp->Nama }}">
                            <input type="hidden" name="Jenis_Kelamin" value="{{ $emp->Jenis_Kelamin }}">
                            <input type="hidden" name="Jabatan" value="{{ $emp->Jabatan }}">
                            <input type="hidden" name="id_departemen" value="{{ $emp->id_departemen }}">
                            <input type="hidden" name="id_RFID" value="{{ $emp->id_RFID }}">
                            <input type="hidden" name="id_fingerprint" value="{{ $emp->id_fingerprint }}">
                            <input type="hidden" name="Tanggal_bergabung" value="{{ $emp->Tanggal_bergabung }}">
                            
                            @if($emp->Status === 'nonaktif')
                                <input type="hidden" name="Status" value="aktif">
                                <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Activate</button>
                            @else
                                <input type="hidden" name="Status" value="nonaktif">
                                <button type="submit" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.4);">Deactivate</button>
                            @endif
                        </form>
                        <form action="{{ route('employees.destroy', $emp) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Delete employee {{ $emp->Nama }}?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 3rem;">No employees data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $employees->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
