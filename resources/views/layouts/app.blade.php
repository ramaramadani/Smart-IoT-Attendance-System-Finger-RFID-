<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Auto Refresh for Monitoring/Emergency pages -->
    @yield('meta')
    <title>Abscent IoT Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #0f172a;
            --sidebar-bg: rgba(15, 23, 42, 0.8);
            --sidebar-width: 260px;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --error: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            /* Optimization: Replace heavy fixed multi-radial gradient with a simple linear gradient */
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }

        .guest-layout {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            /* Optimization: Reduced blur to avoid GPU overhead */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-right: 1px solid var(--glass-border);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding: 2rem 1rem;
            z-index: 100;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2rem;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            transition: background-color 0.2s, color 0.2s; /* Optimization: Target specific properties, avoid 'all' */
            font-weight: 500;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .nav-item.active {
            background: rgba(99, 102, 241, 0.15); /* Optimization: Use plain rgba, no linear-gradient */
            border-left: 3px solid var(--primary);
        }

        .nav-item.emergency {
            color: #fca5a5;
        }
        .nav-item.emergency:hover, .nav-item.emergency.active {
            background: rgba(239, 68, 68, 0.15);
            color: var(--error);
            border-left: 3px solid var(--error);
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--glass-border);
            padding-top: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
        }

        .glass-panel {
            background: var(--glass-bg);
            /* Main Optimization: Remove duplicate backdrop-filter on hundreds of panel elements. This causes 90% of jank */
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4); /* Optimization: Reduced shadow intensity */
            padding: 2rem;
            margin-bottom: 2rem;
            /* Optimization: Animations minimized */
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        /* Forms & Buttons */
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }

        .btn-primary { background: linear-gradient(135deg, var(--primary), #818cf8); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        
        .btn-danger { background: rgba(239, 68, 68, 0.1); color: var(--error); border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.2); }

        .btn-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); }
        .btn-success:hover { background: rgba(16, 185, 129, 0.2); }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }
        .table th {
            color: var(--text-muted);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 0.5rem;
            color: var(--text-main);
            margin-bottom: 1rem;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background: rgba(16, 185, 129, 0.2); color: #4ade80; }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .badge-error { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge-info { background: rgba(56, 187, 248, 0.2); color: #38bdf8; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    @guest
        <div class="guest-layout">
            @yield('content')
        </div>
    @else
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand" style="display: flex; align-items: center; gap: 0.75rem;">
                <img src="{{ asset('img/abscent.png') }}" alt="Abscent Logo" style="height: 45px; object-fit: contain;">
                <span style="font-family: 'Righteous', sans-serif; font-size: 1.7rem; background: linear-gradient(135deg, #fff, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: 1px; text-transform: uppercase;">Abscent</span>
            </div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('logs') }}" class="nav-item {{ request()->routeIs('logs') ? 'active' : '' }}">Attendance Logs</a>
            <a href="{{ route('sp_report') }}" class="nav-item {{ request()->routeIs('sp_report') ? 'active' : '' }}">SP 1 Report</a>
            <a href="{{ route('monitoring') }}" class="nav-item {{ request()->routeIs('monitoring') ? 'active' : '' }}">Monitoring</a>
            <a href="{{ route('emergency') }}" class="nav-item emergency {{ request()->routeIs('emergency') ? 'active' : '' }}">Emergency</a>
            
            @if(auth()->user()->Role === 'admin')
                <div style="margin-top: 1.5rem; margin-bottom: 0.5rem; padding-left: 1rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Admin</div>
                <a href="{{ route('departments.index') }}" class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">Department Data</a>
                <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">Employee Data</a>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">Account Management</a>
            @endif

            <div class="sidebar-footer">
                <div style="padding: 0 1rem; margin-bottom: 1rem;">
                    <div style="font-size: 0.875rem; color: var(--text-main);">{{ auth()->user()->Nama }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst(auth()->user()->Role) }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">Log Out</button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="topbar">
                <h1 style="font-size: 1.75rem; font-weight: 700;">@yield('title', 'Dashboard')</h1>
            </div>
            
            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--success); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--error); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <ul style="margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    @endguest

    @stack('scripts')
</body>
</html>
