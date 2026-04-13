<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * Expects JSON like: 
     * {
     *    "rfid_uid": "A1B2", 
     *    "finger_id": null,
     *    "type": "tap_in_rfid"
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:absen_finger,tap_in_rfid,tap_out_rfid',
        ]);

        $employee = null;

        if ($request->filled('rfid_uid')) {
            $employee = Employee::where('rfid_uid', $request->rfid_uid)->first();
        } elseif ($request->filled('finger_id')) {
            $employee = Employee::where('finger_id', $request->finger_id)->first();
        }

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if (!$employee->is_active) {
            return response()->json(['message' => 'Employee is inactive'], 403);
        }

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'type' => $request->type,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'message' => 'Attendance logged successfully',
            'data' => $attendance
        ], 201);
    }
}
