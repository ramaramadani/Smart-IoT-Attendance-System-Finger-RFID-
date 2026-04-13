<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmergencyController extends Controller
{
    public function index()
    {
        // Identical to monitoring but styled appropriately
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

        return view('emergency.index', compact('insideEmployees'));
    }
}
