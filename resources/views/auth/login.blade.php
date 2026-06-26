@extends('layouts.app')

@section('content')
<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh; width: 100%;">
    <div class="glass-panel" style="width: 100%; max-width: 450px;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 1rem;">Sign in to your account to continue</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="Username" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Username</label>
                <input id="Username" type="text" class="form-control" name="Username" value="{{ old('Username') }}" required autofocus autocomplete="username" placeholder="Enter your username">
                @error('Username')
                    <span class="text-error" style="color: var(--error); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="Password" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Password</label>
                <input id="Password" type="password" class="form-control" name="Password" required autocomplete="current-password" placeholder="Enter your password">
                @error('Password')
                    <span class="text-error" style="color: var(--error); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection
