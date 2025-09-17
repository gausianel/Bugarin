<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use PDF; // kalau pakai dompdf/barryvdh
use Maatwebsite\Excel\Facades\Excel; // kalau pakai maatwebsite/excel
use App\Exports\AttendanceExport;

class ReportController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['user', 'class'])
            ->orderByDesc('checked_in_at')
            ->paginate(10);

        return view('admin.reports.index', compact('attendances'));
    }

    public function export($format)
    {
        if ($format === 'pdf') {
            $attendances = Attendance::with(['member','class'])->get();
            $pdf = PDF::loadView('admin.reports.pdf', compact('attendances'));
            return $pdf->download('laporan_absensi.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new Attendance, 'laporan_absensi.xlsx');
        }

        abort(404);
    }
}
