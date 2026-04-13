@extends('layouts.app')

@section('content')
<div class="container">
    <div class="glass-panel" style="width: 100%; max-width: 450px;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 1rem;">Sign in to your account to continue</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Enter your username">
                @error('username')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Enter your password">
                @error('password')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection
