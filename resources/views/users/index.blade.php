@extends('layouts.app')

@section('title', 'Account Management')

@section('content')
<div class="glass-panel" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-weight: 600;">Create New Account</h3>
    <form action="{{ route('users.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        @csrf
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Full Name</label>
            <input type="text" name="Nama" class="form-control" style="margin-bottom: 0;" placeholder="e.g. John Doe" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Username</label>
            <input type="text" name="Username" class="form-control" style="margin-bottom: 0;" placeholder="e.g. johndoe" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Email Address</label>
            <input type="email" name="Email" class="form-control" style="margin-bottom: 0;" placeholder="e.g. john@example.com" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Password</label>
            <input type="password" name="Password" class="form-control" style="margin-bottom: 0;" required minlength="6" placeholder="Min 6 characters">
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Access Role</label>
            <select name="Role" class="form-control" style="margin-bottom: 0;">
                <option value="user">Regular User</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Status</label>
            <select name="Status" class="form-control" style="margin-bottom: 0;">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px;">Create Account</button>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $usr)
                <tr>
                    <td style="font-weight: 500;">{{ $usr->Nama }}</td>
                    <td>{{ $usr->Username }}</td>
                    <td>{{ $usr->Email }}</td>
                    <td>
                        @if($usr->Role == 'admin')
                            <span class="badge badge-info">Admin</span>
                        @else
                            <span class="badge badge-success">User</span>
                        @endif
                    </td>
                    <td>
                        @if($usr->Status == 'Aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-error">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        @if($usr->id_User !== auth()->user()->id_User)
                        <form action="{{ route('users.destroy', $usr) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Delete this account?')">Delete</button>
                        </form>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.875rem;">(Logged In)</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
