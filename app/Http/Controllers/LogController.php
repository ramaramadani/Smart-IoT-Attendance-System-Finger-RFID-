<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    private function getFilteredQuery(Request $request)
    {
        $query = Attendance::with('employee.department');

        if ($request->filled('name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('Nama', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('Tanggal', $request->date);
        }

        return $query->orderBy('Tanggal', 'desc')->orderBy('cread_at', 'desc');
    }

    public function index(Request $request)
    {
        $logs = $this->getFilteredQuery($request)->paginate(20)->withQueryString();
        return view('logs.index', compact('logs'));
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->getFilteredQuery($request)->get();
        $pdf = Pdf::loadView('logs.pdf', compact('logs'));
        return $pdf->download('laporan-log-absensi.pdf');
    }

    public function exportExcel(Request $request)
    {
        $logs = $this->getFilteredQuery($request)->get();

        $filename = "laporan-log-absensi-" . date('Y-m-d') . ".csv";
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Tanggal', 'Nama Karyawan', 'Departemen', 'Jam Masuk', 'Jam Keluar', 'Durasi Kerja (Menit)', 'Status', 'Keterangan'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['Tanggal']    = $log->Tanggal ? $log->Tanggal->format('Y-m-d') : '-';
                $row['Nama']       = $log->employee->Nama ?? 'Unknown';
                $row['Departemen'] = $log->employee->department->Nama_departemen ?? '-';
                $row['Masuk']      = $log->Jam_masuk ?? '-';
                $row['Keluar']     = $log->Jam_keluar ?? '-';
                $row['Durasi']     = $log->Durasi . ' Menit';
                $row['Status']     = ucfirst($log->Status);
                $row['Ket']        = $log->Keterangan ?? '-';

                fputcsv($file, array(
                    $row['Tanggal'], 
                    $row['Nama'], 
                    $row['Departemen'], 
                    $row['Masuk'], 
                    $row['Keluar'], 
                    $row['Durasi'], 
                    $row['Status'], 
                    $row['Ket']
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
