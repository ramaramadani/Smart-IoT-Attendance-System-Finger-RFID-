@extends('layouts.app')

@section('meta')
<meta http-equiv="refresh" content="5">
@endsection

@section('title', 'Emergency Dashboard')

@section('content')
<style>
    /* Override primary styles for emergency mode */
    :root {
        --primary: #ef4444; 
        --primary-hover: #dc2626;
        --sidebar-bg: rgba(69, 10, 10, 0.9);
        --glass-bg: rgba(69, 10, 10, 0.5);
    }
    body {
        background-image: radial-gradient(at 0% 0%, rgba(127, 29, 29, 0.8) 0, transparent 50%), radial-gradient(at 100% 100%, rgba(153, 27, 27, 0.6) 0, transparent 50%);
        background-color: #450a0a;
    }
    .emergency-pulse {
        animation: emergencybg 2s infinite alternate;
    }
    @keyframes emergencybg {
        from { box-shadow: 0 0 20px rgba(239, 68, 68, 0.2); }
        to { box-shadow: 0 0 40px rgba(239, 68, 68, 0.6); }
    }
</style>

<div class="glass-panel emergency-pulse" style="border-color: rgba(239, 68, 68, 0.5); text-align: center; padding: 3rem;">
    <h1 style="font-size: 3rem; color: #fca5a5; margin-bottom: 1rem; text-transform: uppercase;">Evacuation Status</h1>
    <p style="font-size: 1.25rem; color: #fecaca; margin-bottom: 2rem;">Total Personil Tersisa di Dalam Area: <strong>{{ count($insideEmployees) }}</strong></p>
    
    @if(count($insideEmployees) > 0)
        <div style="background: rgba(0,0,0,0.3); border-radius: 1rem; padding: 1.5rem; text-align: left;">
            <h3 style="color: #f87171; border-bottom: 1px solid rgba(239, 68, 68, 0.3); padding-bottom: 0.5rem; margin-bottom: 1rem;">Daftar Menunggu Evakuasi (Berdasarkan Sistem)</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @foreach($insideEmployees as $employee)
                    <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); padding: 1rem; border-radius: 0.5rem;">
                        <strong>{{ $employee->name }}</strong><br>
                        <span style="font-size: 0.8rem; color: #fecaca;">Check-in: {{ \Carbon\Carbon::parse($employee->last_seen)->format('H:i') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #4ade80; padding: 2rem; border-radius: 1rem; font-size: 1.5rem; font-weight: bold;">
            AREA CLEAR - SEMUA PERSONIL AMAN
        </div>
    @endif
</div>
@endsection
