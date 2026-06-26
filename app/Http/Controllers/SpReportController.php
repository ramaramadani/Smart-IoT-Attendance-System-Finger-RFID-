<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SpReportController extends Controller
{
    public function index(Request $request)
    {
        // Rekapitulasi SP1 (Sistem 3 Bulan: Terlambat >= 120 Menit)
        $threeMonthsAgo = Carbon::now()->subMonths(3)->toDateString();
        
        $query = Employee::with(['attendances' => function($q) use ($threeMonthsAgo) {
            $q->where('Status', 'telat')
              ->where('Tanggal', '>=', $threeMonthsAgo);
        }]);

        if ($request->filled('name')) {
            $query->where('Nama', 'like', '%' . $request->name . '%');
        }

        $employees = $query->get();

        $spReport = $employees->map(function ($emp) {
            $totalLate = 0;
            foreach ($emp->attendances as $att) {
                $totalLate += $att->late_minutes;
            }
            return (object)[
                'employee' => $emp,
                'total_late' => $totalLate,
                'status' => $totalLate >= 120 ? 'SP 1' : 'Aman',
            ];
        });

        // Filter by status after calculating total lateness
        if ($request->filled('status') && $request->status !== 'all') {
            $statusFilter = $request->status === 'sp1' ? 'SP 1' : 'Aman';
            $spReport = $spReport->where('status', $statusFilter);
        }

        $spReport = $spReport->sortByDesc('total_late')->values();

        return view('sp_report.index', compact('spReport'));
    }
}
