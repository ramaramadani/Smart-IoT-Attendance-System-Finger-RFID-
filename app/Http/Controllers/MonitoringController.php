<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // 1. Live Monitoring Logic
        $today = \Carbon\Carbon::today();

        $subQuery = DB::table('attendances')
            ->select('employee_id', DB::raw('MAX(scanned_at) as last_scan'))
            ->whereDate('scanned_at', $today)
            ->groupBy('employee_id');

        $insideEmployees = Employee::joinSub($subQuery, 'latest_scans', function ($join) {
                $join->on('employees.id', '=', 'latest_scans.employee_id');
            })
            ->join('attendances', function ($join) {
                $join->on('employees.id', '=', 'attendances.employee_id')
                     ->on('latest_scans.last_scan', '=', 'attendances.scanned_at');
            })
            ->whereIn('attendances.type', ['tap_in_rfid', 'absen_finger'])
            ->select('employees.*', 'attendances.scanned_at as last_seen', 'attendances.type as scan_type')
            ->get();


        // 2. RFID Logs Logic
        $rfidQuery = Attendance::with('employee')->whereIn('type', ['tap_in_rfid', 'tap_out_rfid']);

        if ($request->filled('name')) {
            $rfidQuery->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('date')) {
            $rfidQuery->whereDate('scanned_at', $request->date);
        }

        $rfidLogs = $rfidQuery->latest('scanned_at')->paginate(20)->withQueryString();

        return view('monitoring.index', compact('insideEmployees', 'rfidLogs'));
    }
}
