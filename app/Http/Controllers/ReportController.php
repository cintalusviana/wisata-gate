<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->toDateString()
        );

        $histories = GateLog::with('officer')
            ->whereDate('gate_in', '>=', $startDate)
            ->whereDate('gate_in', '<=', $endDate)
            ->latest('gate_in')
            ->get();

        $totalEntry = $histories->count();

        $totalExit = $histories
            ->where('status', 'Sudah Keluar')
            ->count();

        $totalInside = $histories
            ->where('status', 'Di Dalam')
            ->count();

        $vehicleTypeSummary = $histories
            ->groupBy(function ($item) {
                return $item->vehicle_type ?: 'Lainnya';
            })
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        $periodStart = Carbon::parse($startDate)->startOfDay();
        $periodEnd = Carbon::parse($endDate)->startOfDay();

        $periodEntries = GateLog::query()
            ->whereDate('gate_in', '>=', $startDate)
            ->whereDate('gate_in', '<=', $endDate)
            ->get(['gate_in']);

        $periodExits = GateLog::query()
            ->whereNotNull('gate_out')
            ->whereDate('gate_out', '>=', $startDate)
            ->whereDate('gate_out', '<=', $endDate)
            ->get(['gate_out']);

        $entriesByDay = $periodEntries
            ->groupBy(function ($item) {
                return Carbon::parse($item->gate_in)->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->count();
            });

        $exitsByDay = $periodExits
            ->groupBy(function ($item) {
                return Carbon::parse($item->gate_out)->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->count();
            });

        $chartLabels = [];
        $chartEntries = [];
        $chartExits = [];

        for (
            $date = $periodStart->copy();
            $date->lte($periodEnd);
            $date->addDay()
        ) {
            $dateKey = $date->toDateString();

            $chartLabels[] = $date->format('d/m');
            $chartEntries[] = (int) $entriesByDay->get($dateKey, 0);
            $chartExits[] = (int) $exitsByDay->get($dateKey, 0);
        }

        return view('reports.index', compact(
            'histories',
            'startDate',
            'endDate',
            'totalEntry',
            'totalExit',
            'totalInside',
            'vehicleTypeSummary',
            'chartLabels',
            'chartEntries',
            'chartExits'
        ));
    }

    /**
     * Export laporan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->toDateString()
        );

        $histories = GateLog::with('officer')
            ->whereDate('gate_in', '>=', $startDate)
            ->whereDate('gate_in', '<=', $endDate)
            ->latest('gate_in')
            ->get();

        $totalEntry = $histories->count();

        $totalExit = $histories
            ->where('status', 'Sudah Keluar')
            ->count();

        $totalInside = $histories
            ->where('status', 'Di Dalam')
            ->count();

        $vehicleTypeSummary = $histories
            ->groupBy(function ($item) {
                return $item->vehicle_type ?: 'Lainnya';
            })
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'histories',
            'startDate',
            'endDate',
            'totalEntry',
            'totalExit',
            'totalInside',
            'vehicleTypeSummary'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-wisatagate.pdf');
    }

    /**
     * Export laporan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->toDateString()
        );

        return Excel::download(
            new ReportExport($startDate, $endDate),
            'laporan-wisatagate.xlsx'
        );
    }
}
