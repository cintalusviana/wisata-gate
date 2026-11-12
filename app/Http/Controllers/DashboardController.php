<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard utama.
     */
    public function index(): View
    {
        // Jika akun Petugas membuka dashboard utama,
        // tampilkan dashboard Petugas.
        if (auth()->user()?->role === 'petugas') {
            return view('dashboard.petugas', $this->getDashboardData());
        }

        // Admin kembali ke mode Admin.
        session()->forget('mode_petugas');

        $data = $this->getDashboardData();

        return view('dashboard.index', $data);
    }

    /**
     * Mode Petugas.
     *
     * Admin dapat melihat tampilan dashboard petugas
     * tanpa mengubah role akun yang sedang login.
     */
    public function petugas(): View
    {
        $user = auth()->user();

        abort_unless(
            $user && in_array($user->role, ['admin', 'petugas']),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        // Tandai sesi jika Admin sedang menggunakan Mode Petugas.
        if ($user->role === 'admin') {
            session(['mode_petugas' => true]);
        }

        $data = $this->getDashboardData();

        return view('dashboard.petugas', $data);
    }

    /**
     * Mengambil seluruh data yang diperlukan dashboard.
     */
    private function getDashboardData(): array
    {
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        /*
         * KENDARAAN MASUK HARI INI
         *
         * Data kendaraan diambil langsung dari tabel gate_logs.
         */
        $todayEntries = GateLog::with('officer')
            ->whereBetween('gate_in', [$startOfDay, $endOfDay])
            ->latest('gate_in')
            ->get();

        /*
         * KENDARAAN KELUAR HARI INI
         */
        $todayExits = GateLog::with('officer')
            ->whereNotNull('gate_out')
            ->whereBetween('gate_out', [$startOfDay, $endOfDay])
            ->latest('gate_out')
            ->get();

        /*
         * KENDARAAN YANG MASIH BERADA DI AREA WISATA
         */
        $insideVehicles = GateLog::with('officer')
            ->where('status', 'Di Dalam')
            ->whereNull('gate_out')
            ->latest('gate_in')
            ->get();

        /*
         * STATISTIK UTAMA
         */
        $totalEntry = $todayEntries->count();
        $totalExit = $todayExits->count();
        $totalToday = $totalEntry;
        $totalInside = $insideVehicles->count();

        /*
         * AKTIVITAS TERKINI GATE UTAMA
         *
         * Menggunakan updated_at agar aktivitas kendaraan
         * yang baru dicatat keluar ikut muncul sebagai aktivitas terbaru.
         */
        $recentGateLogs = GateLog::with('officer')
            ->orderByRaw(
                'COALESCE(updated_at, created_at) DESC'
            )
            ->orderByDesc('id')
            ->take(7)
            ->get();

        /*
         * KOMPOSISI JENIS KENDARAAN
         *
         * Menggunakan kolom vehicle_type langsung.
         */
        $vehicleTypeCounts = [];

        foreach ($insideVehicles as $log) {
            $typeName = trim((string) $log->vehicle_type);

            if ($typeName === '') {
                $typeName = 'Lainnya';
            }

            $vehicleTypeCounts[$typeName] =
                ($vehicleTypeCounts[$typeName] ?? 0) + 1;
        }

        arsort($vehicleTypeCounts);

        $vehicleComposition = collect($vehicleTypeCounts)
            ->map(function ($total, $name) {
                return (object) [
                    'name' => $name,
                    'total' => $total,
                ];
            })
            ->values();

        /*
         * AKTIVITAS KENDARAAN BERDASARKAN WAKTU
         */
        $chartHours = [
            '07:00',
            '09:00',
            '11:00',
            '13:00',
            '15:00',
            '17:00',
        ];

        $chartEntries = [];
        $chartExits = [];
        $activity = collect();

        foreach ($chartHours as $hour) {
            $hourNumber = (int) substr($hour, 0, 2);
            $nextHour = $hourNumber + 2;

            $entryCount = $todayEntries
                ->filter(function ($log) use ($hourNumber, $nextHour) {
                    if (!$log->gate_in) {
                        return false;
                    }

                    $logHour = $log->gate_in->hour;

                    return $logHour >= $hourNumber
                        && $logHour < $nextHour;
                })
                ->count();

            $exitCount = $todayExits
                ->filter(function ($log) use ($hourNumber, $nextHour) {
                    if (!$log->gate_out) {
                        return false;
                    }

                    $logHour = $log->gate_out->hour;

                    return $logHour >= $hourNumber
                        && $logHour < $nextHour;
                })
                ->count();

            $chartEntries[] = $entryCount;
            $chartExits[] = $exitCount;

            $activity->push([
                'label' => $hour,
                'entry' => $entryCount,
                'exit' => $exitCount,
            ]);
        }

        /*
         * DATA TABEL KENDARAAN AKTIF
         */
        $vehicles = $insideVehicles;

        return [
            // Statistik.
            'totalToday' => $totalToday,
            'totalInside' => $totalInside,
            'totalEntry' => $totalEntry,
            'totalExit' => $totalExit,

            // Data kendaraan.
            'vehicles' => $vehicles,
            'insideVehicles' => $insideVehicles,
            'todayEntries' => $todayEntries,
            'todayExits' => $todayExits,
            'recentGateLogs' => $recentGateLogs,

            // Komposisi kendaraan.
            'vehicleTypeCounts' => $vehicleTypeCounts,
            'vehicleComposition' => $vehicleComposition,

            // Grafik aktivitas.
            'chartHours' => $chartHours,
            'chartEntries' => $chartEntries,
            'chartExits' => $chartExits,
            'activity' => $activity,
        ];
    }
}
