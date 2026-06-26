@extends('layouts.app')

@section('title', 'Department Data')

@section('content')
<div class="glass-panel" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-weight: 600;">Add New Department</h3>
    <form action="{{ route('departments.store') }}" method="POST" style="display: grid; grid-template-columns: 1fr 2fr auto; gap: 1rem; align-items: end;">
        @csrf
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Department Name</label>
            <input type="text" name="Nama_departemen" class="form-control" style="margin-bottom: 0;" placeholder="e.g. IT Department" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Description / Keterangan</label>
            <input type="text" name="Keterangan" class="form-control" style="margin-bottom: 0;" placeholder="e.g. Handles technical support and system updates">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">Add Department</button>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td style="font-weight: 500; color: var(--text-muted);">#{{ $dept->id_departemen }}</td>
                    <td style="font-weight: 600;">{{ $dept->Nama_departemen }}</td>
                    <td>{{ $dept->Keterangan ?? '-' }}</td>
                    <td>{{ $dept->cread_at ? \Carbon\Carbon::parse($dept->cread_at)->format('d M Y, H:i') : '-' }}</td>
                    <td>
                        <form action="{{ route('departments.destroy', $dept) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Delete this department? This will delete all employees in it!')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">No department data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $departments->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
