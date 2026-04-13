@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
<div class="glass-panel" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-weight: 600;">Buat Akun Baru</h3>
    <form action="{{ route('users.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        @csrf
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" style="margin-bottom: 0;" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Username Login</label>
            <input type="text" name="username" class="form-control" style="margin-bottom: 0;" required>
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Password</label>
            <input type="password" name="password" class="form-control" style="margin-bottom: 0;" required minlength="6">
        </div>
        <div>
            <label class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Role Hak Akses</label>
            <select name="role" class="form-control" style="margin-bottom: 0;">
                <option value="user">User Biasa</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Buat Akun</button>
        </div>
    </form>
</div>

<div class="glass-panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="font-weight: 500;">{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        @if($user->role == 'admin')
                            <span class="badge badge-info">Admin</span>
                        @else
                            <span class="badge badge-success">User</span>
                        @endif
                    </td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                        </form>
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
