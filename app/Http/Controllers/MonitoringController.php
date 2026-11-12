<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    /**
     * Monitoring kendaraan yang masih berada di area wisata.
     */
    public function index(): View
    {
        $today = now()->toDateString();

        $vehicles = GateLog::with('officer')
            ->where('status', 'Di Dalam')
            ->latest('gate_in')
            ->get();

        $officers = User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $totalInside = GateLog::where('status', 'Di Dalam')->count();

        $totalEntry = GateLog::whereDate('gate_in', $today)->count();

        $totalExit = GateLog::whereDate('gate_out', $today)->count();

        return view('monitoring.index', compact(
            'vehicles',
            'officers',
            'totalInside',
            'totalEntry',
            'totalExit'
        ));
    }


    /**
     * Export seluruh data monitoring ke CSV.
     */
    public function export()
    {
        $gateLogs = GateLog::with('officer')
            ->latest('gate_in')
            ->get();

        $filename = 'monitoring-kendaraan-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($gateLogs) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No',
                'Nomor Plat',
                'Jenis Kendaraan',
                'Pengemudi',
                'Tujuan',
                'Waktu Masuk',
                'Waktu Keluar',
                'Status',
                'Petugas',
                'Catatan',
            ]);

            foreach ($gateLogs as $index => $gateLog) {

                fputcsv($file, [
                    $index + 1,

                    $gateLog->plate_number ?? '-',

                    $gateLog->vehicle_type ?? '-',

                    $gateLog->driver_name ?? '-',

                    $gateLog->purpose ?? '-',

                    $gateLog->gate_in
                        ? $gateLog->gate_in->format('d-m-Y H:i')
                        : '-',

                    $gateLog->gate_out
                        ? $gateLog->gate_out->format('d-m-Y H:i')
                        : '-',

                    $gateLog->status ?? '-',

                    $gateLog->officer->name ?? '-',

                    $gateLog->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            Response::HTTP_OK,
            $headers
        );
    }


    /**
     * Menampilkan kendaraan yang masih berada di area
     * untuk halaman petugas.
     *
     * Jika URL memiliki ?edit=id,
     * maka form edit akan ditampilkan di atas tabel.
     */
    public function petugasArea(Request $request): View
    {
        $vehicles = GateLog::with('officer')
            ->where('status', 'Di Dalam')
            ->latest('gate_in')
            ->get();

        $totalInside = $vehicles->count();

        $editingGateLog = null;

        if ($request->filled('edit')) {

            $editingGateLog = GateLog::where('status', 'Di Dalam')
                ->findOrFail($request->input('edit'));
        }

        return view(
            'petugas.kendaraan-area.index',
            compact(
                'vehicles',
                'totalInside',
                'editingGateLog'
            )
        );
    }
}