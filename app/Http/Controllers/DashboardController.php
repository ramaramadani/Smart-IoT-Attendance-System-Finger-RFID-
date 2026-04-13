<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $dailyCount = Attendance::whereDate('scanned_at', $today)->count();
        $weeklyCount = Attendance::where('scanned_at', '>=', $startOfWeek)->count();
        $monthlyCount = Attendance::where('scanned_at', '>=', $startOfMonth)->count();
        $yearlyCount = Attendance::where('scanned_at', '>=', $startOfYear)->count();

        // Chart Filtering Logic
        $filter = $request->input('filter', 'weekly');
        $chartLabels = [];
        $chartData = [];

        if ($filter == 'daily') {
            // Data per hour for today
            for ($i = 6; $i <= 18; $i++) {
                $chartLabels[] = sprintf('%02d:00', $i);
                $chartData[] = Attendance::whereDate('scanned_at', $today)
                                ->whereTime('scanned_at', '>=', sprintf('%02d:00:00', $i))
                                ->whereTime('scanned_at', '<', sprintf('%02d:59:59', $i))
                                ->count();
            }
        } elseif ($filter == 'monthly') {
            // Data per day for the last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartLabels[] = $date->format('d M');
                $chartData[] = Attendance::whereDate('scanned_at', $date)->count();
            }
        } elseif ($filter == 'yearly') {
            // Data per month for this year
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::today()->startOfYear()->addMonths($i - 1);
                $chartLabels[] = collect(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'])->get($i - 1);
                
                if ($monthStart->isFuture()) {
                    $chartData[] = 0;
                } else {
                    $chartData[] = Attendance::whereMonth('scanned_at', $i)
                                    ->whereYear('scanned_at', Carbon::now()->year)
                                    ->count();
                }
            }
        } else {
            // Default: Weekly (last 7 days)
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartLabels[] = $date->format('d M');
                $chartData[] = Attendance::whereDate('scanned_at', $date)->count();
            }
        }

        return view('dashboard', compact(
            'dailyCount', 'weeklyCount', 'monthlyCount', 'yearlyCount', 
            'chartLabels', 'chartData', 'filter'
        ));
    }
}
