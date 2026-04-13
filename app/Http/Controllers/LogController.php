<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee')->where('type', 'absen_finger');

        // Filter by Employee Name
        if ($request->filled('name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        $logs = $query->latest('scanned_at')->paginate(20)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
