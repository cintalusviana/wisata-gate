<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard utama.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Petugas diarahkan ke dashboard petugas.
        if ($user?->role === 'petugas') {
            return view('dashboard.petugas', $this->getDashboardData());
        }

        // Hapus mode petugas ketika admin kembali ke dashboard.
        session()->forget('mode_petugas');

        return view('dashboard.index', $this->getDashboardData());
    }

    /**
     * Dashboard mode petugas.
     */
    public function petugas(): View
    {
        $user = Auth::user();

        abort_unless(
            $user && in_array($user->role, ['admin', 'petugas'], true),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        // Aktifkan mode petugas untuk admin.
        if ($user->role === 'admin') {
            session(['mode_petugas' => true]);
        }

        return view('dashboard.petugas', $this->getDashboardData());
    }

    /**
     * Mengambil data dashboard.
     */
    private function getDashboardData(): array
    {
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        // Kendaraan masuk hari ini.
        $todayEntries = GateLog::with('officer')
            ->whereBetween('gate_in', [$startOfDay, $endOfDay])
            ->latest('gate_in')
            ->get();

        // Kendaraan keluar hari ini.
        $todayExits = GateLog::with('officer')
            ->whereNotNull('gate_out')
            ->whereBetween('gate_out', [$startOfDay, $endOfDay])
            ->latest('gate_out')
            ->get();

        // Kendaraan yang masih berada di area wisata.
        $insideVehicles = GateLog::with('officer')
            ->where('status', 'Di Dalam')
            ->whereNull('gate_out')
            ->latest('gate_in')
            ->get();

        // Statistik kendaraan.
        $totalEntry = $todayEntries->count();
        $totalExit = $todayExits->count();
        $totalInside = $insideVehicles->count();

        // Aktivitas gate terbaru.
        $recentGateLogs = GateLog::with('officer')
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->orderByDesc('id')
            ->limit(7)
            ->get();

        // Komposisi jenis kendaraan.
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
            ->map(fn ($total, $name) => (object) [
                'name' => $name,
                'total' => $total,
            ])
            ->values();

        // Grafik aktivitas kendaraan.
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
                    return $log->gate_in
                        && $log->gate_in->hour >= $hourNumber
                        && $log->gate_in->hour < $nextHour;
                })
                ->count();

            $exitCount = $todayExits
                ->filter(function ($log) use ($hourNumber, $nextHour) {
                    return $log->gate_out
                        && $log->gate_out->hour >= $hourNumber
                        && $log->gate_out->hour < $nextHour;
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

        return [
            // Statistik.
            'totalToday' => $totalEntry,
            'totalInside' => $totalInside,
            'totalEntry' => $totalEntry,
            'totalExit' => $totalExit,

            // Data kendaraan.
            'vehicles' => $insideVehicles,
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
