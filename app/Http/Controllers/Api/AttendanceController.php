<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Store a newly created resource in storage or update existing daily log.
     * Expects JSON like: 
     * {
     *    "rfid_uid": "RF1234", 
     *    "finger_id": null,
     *    "type": "tap_in_rfid" // tap_in_rfid, tap_out_rfid, or absen_finger
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:absen_finger,tap_in_rfid,tap_out_rfid',
        ]);

        $employee = null;

        if ($request->filled('rfid_uid')) {
            $employee = Employee::where('id_RFID', $request->rfid_uid)->first();
        } elseif ($request->filled('finger_id')) {
            $employee = Employee::where('id_fingerprint', $request->finger_id)->first();
        }

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if ($employee->Status !== 'aktif') {
            return response()->json(['message' => 'Employee is inactive'], 403);
        }

        $today = Carbon::today()->toDateString();
        $nowTime = Carbon::now();
        $timeString = $nowTime->format('H:i:s');

        // Look for today's daily log
        $attendance = Attendance::where('id_karyawan', $employee->id_Karyawan)
                                ->whereDate('Tanggal', $today)
                                ->first();

        if (!$attendance) {
            // Determine initial status based on entry time (cutoff 07:00:00)
            $shiftStart = Carbon::today()->setTime(7, 0, 0);
            $status = $nowTime->greaterThan($shiftStart) ? 'telat' : 'hadir';

            // 1. Create check-in log
            $attendance = Attendance::create([
                'id_karyawan' => $employee->id_Karyawan,
                'Tanggal' => $today,
                'Jam_masuk' => $timeString,
                'Jam_keluar' => null,
                'Durasi' => 0,
                'Status' => $status,
                'Keterangan' => 'Check-in via ' . $request->type,
            ]);

            return response()->json([
                'message' => 'Checked in successfully',
                'action' => 'check_in',
                'data' => $attendance
            ], 201);
        } else {
            // 2. Already checked in, update check-out (Jam_keluar) and Durasi
            $attendance->Jam_keluar = $timeString;
            
            // Calculate duration
            if ($attendance->Jam_masuk) {
                try {
                    $inTime = Carbon::createFromFormat('H:i:s', $attendance->Jam_masuk);
                    $attendance->Durasi = (int) $nowTime->diffInMinutes($inTime, true);
                } catch (\Exception $e) {
                    $attendance->Durasi = 0;
                }
            }
            
            $attendance->Keterangan = 'Check-out via ' . $request->type;
            $attendance->save();

            return response()->json([
                'message' => 'Checked out successfully',
                'action' => 'check_out',
                'data' => $attendance
            ], 200);
        }
    }
}
