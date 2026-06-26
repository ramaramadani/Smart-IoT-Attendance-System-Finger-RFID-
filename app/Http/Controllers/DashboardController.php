<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $startOfYear = Carbon::now()->startOfYear()->toDateString();

        $dailyCount = Attendance::whereDate('Tanggal', $today)->whereNotNull('Jam_masuk')->count();
        $weeklyCount = Attendance::where('Tanggal', '>=', $startOfWeek)->whereNotNull('Jam_masuk')->count();
        $monthlyCount = Attendance::where('Tanggal', '>=', $startOfMonth)->whereNotNull('Jam_masuk')->count();
        $yearlyCount = Attendance::where('Tanggal', '>=', $startOfYear)->whereNotNull('Jam_masuk')->count();

        // Chart Filtering Logic
        $filter = $request->input('filter', 'weekly');
        $chartLabels = [];
        $chartData = [];

        if ($filter == 'daily') {
            // Data per hour for today
            for ($i = 6; $i <= 18; $i++) {
                $chartLabels[] = sprintf('%02d:00', $i);
                $chartData[] = Attendance::whereDate('Tanggal', $today)
                                ->whereTime('Jam_masuk', '>=', sprintf('%02d:00:00', $i))
                                ->whereTime('Jam_masuk', '<', sprintf('%02d:59:59', $i))
                                ->count();
            }
        } elseif ($filter == 'monthly') {
            // Data per day for the last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartLabels[] = $date->format('d M');
                $chartData[] = Attendance::whereDate('Tanggal', $date->toDateString())->whereNotNull('Jam_masuk')->count();
            }
        } elseif ($filter == 'yearly') {
            // Data per month for this year
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::today()->startOfYear()->addMonths($i - 1);
                $chartLabels[] = collect(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'])->get($i - 1);
                
                if ($monthStart->isFuture()) {
                    $chartData[] = 0;
                } else {
                    $chartData[] = Attendance::whereMonth('Tanggal', $i)
                                    ->whereYear('Tanggal', Carbon::now()->year)
                                    ->whereNotNull('Jam_masuk')
                                    ->count();
                }
            }
        } else {
            // Default: Weekly (last 7 days)
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartLabels[] = $date->format('d M');
                $chartData[] = Attendance::whereDate('Tanggal', $date->toDateString())->whereNotNull('Jam_masuk')->count();
            }
        }

        return view('dashboard', compact(
            'dailyCount', 'weeklyCount', 'monthlyCount', 'yearlyCount', 
            'chartLabels', 'chartData', 'filter'
        ));
    }
}
