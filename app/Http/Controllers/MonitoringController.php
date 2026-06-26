<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // 1. Live Monitoring Logic (Employees with Jam_masuk but no Jam_keluar today)
        $insideEmployees = Employee::whereHas('attendances', function ($join) use ($today) {
                $join->whereDate('Tanggal', $today)
                     ->whereNotNull('Jam_masuk')
                     ->whereNull('Jam_keluar');
            })
            ->with(['attendances' => function ($q) use ($today) {
                $q->whereDate('Tanggal', $today);
            }])
            ->get();

        // 2. RFID logs (we display all daily attendance entries)
        $rfidQuery = Attendance::with('employee.department')->where(function ($q) {
            $q->whereNotNull('Jam_masuk')->orWhereNotNull('Jam_keluar');
        });

        if ($request->filled('name')) {
            $rfidQuery->whereHas('employee', function ($q) use ($request) {
                $q->where('Nama', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('date')) {
            $rfidQuery->whereDate('Tanggal', $request->date);
        }

        $rfidLogs = $rfidQuery->orderBy('Tanggal', 'desc')->orderBy('cread_at', 'desc')->paginate(20)->withQueryString();

        return view('monitoring.index', compact('insideEmployees', 'rfidLogs'));
    }
}
