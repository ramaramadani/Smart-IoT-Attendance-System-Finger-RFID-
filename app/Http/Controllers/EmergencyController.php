<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmergencyController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Evacuation Status: Employees with Jam_masuk but no Jam_keluar today
        $insideEmployees = Employee::whereHas('attendances', function ($join) use ($today) {
                $join->whereDate('Tanggal', $today)
                     ->whereNotNull('Jam_masuk')
                     ->whereNull('Jam_keluar');
            })
            ->with(['attendances' => function ($q) use ($today) {
                $q->whereDate('Tanggal', $today);
            }])
            ->get();

        return view('emergency.index', compact('insideEmployees'));
    }
}
