<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use App\Models\ParkingConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GateLogController extends Controller
{
    /**
     * Memeriksa apakah pengguna adalah Admin.
     */
    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Memeriksa apakah pengguna sedang menggunakan Mode Petugas.
     */
    private function isPetugasMode(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->role === 'petugas'
            || (
                Auth::user()->role === 'admin'
                && session('mode_petugas', false) === true
            );
    }

    /**
     * Menentukan prefix petak parkir berdasarkan jenis kendaraan.
     */
    private function getParkingSlotPrefix(string $vehicleType): string
    {
        $type = strtolower(trim($vehicleType));

        $motorKeywords = [
            'motor',
            'sepeda',
            'scooter',
            'skuter',
        ];

        foreach ($motorKeywords as $keyword) {
            if (str_contains($type, $keyword)) {
                return 'M';
            }
        }

        return 'R';
    }

    /**
     * Membuat daftar nomor petak parkir.
     */
    private function generateParkingSlots(string $prefix, int $capacity): array
    {
        $slots = [];

        for ($i = 1; $i <= $capacity; $i++) {
            $slots[] = $prefix . str_pad(
                (string) $i,
                2,
                '0',
                STR_PAD_LEFT
            );
        }

        return $slots;
    }

    /**
     * Menentukan halaman tujuan setelah proses.
     */
    private function getReturnRoute(?string $returnTo = null): string
    {
        if ($returnTo === 'history' && $this->isAdmin()) {
            return 'history.index';
        }

        return $this->isPetugasMode()
            ? 'gate-logs.create'
            : 'monitoring.index';
    }

    /**
     * Menampilkan halaman kendaraan masuk.
     */
    public function create(): View
    {
        $recentGateLogs = GateLog::with('officer')
            ->latest('gate_in')
            ->take(5)
            ->get();

        return view('gate-logs.create', [
            'recentGateLogs' => $recentGateLogs,
        ]);
    }

    /**
     * Menyimpan data kendaraan masuk.
     * Petak parkir diberikan secara otomatis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'max:100'],
            'driver_name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $plateNumber = strtoupper(trim($validated['plate_number']));
        $officerId = Auth::id();

        $result = DB::transaction(function () use (
            $validated,
            $plateNumber,
            $officerId
        ) {
            $configurations = ParkingConfiguration::query()
                ->whereIn('vehicle_type', ['Motor', 'Mobil'])
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('vehicle_type');

            if (
                !$configurations->has('Motor') ||
                !$configurations->has('Mobil')
            ) {
                return [
                    'success' => false,
                    'reason' => 'configuration',
                ];
            }

            $motorCapacity = max(
                0,
                (int) $configurations->get('Motor')->total_slots
            );

            $carCapacity = max(
                0,
                (int) $configurations->get('Mobil')->total_slots
            );

            $motorSlots = $this->generateParkingSlots(
                'M',
                $motorCapacity
            );

            $carSlots = $this->generateParkingSlots(
                'R',
                $carCapacity
            );

            $activeLogs = GateLog::query()
                ->where('status', 'Di Dalam')
                ->whereNull('gate_out')
                ->orderBy('gate_in')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            // Mencegah nomor polisi yang sama tercatat masuk
            // selama kendaraan masih berada di area wisata.
            $duplicatePlate = $activeLogs->contains(
                function ($log) use ($plateNumber) {
                    return strtoupper(
                        trim((string) $log->plate_number)
                    ) === $plateNumber;
                }
            );

            if ($duplicatePlate) {
                return [
                    'success' => false,
                    'reason' => 'duplicate',
                ];
            }

            // Memeriksa dan memperbaiki penempatan petak aktif.
            $occupiedSlots = [];

            foreach ($activeLogs as $activeLog) {
                $prefix = $this->getParkingSlotPrefix(
                    $activeLog->vehicle_type ?? ''
                );

                $availableSlots = $prefix === 'M'
                    ? $motorSlots
                    : $carSlots;

                $currentSlot = $activeLog->parking_slot;

                if (
                    $currentSlot &&
                    in_array($currentSlot, $availableSlots, true) &&
                    !in_array($currentSlot, $occupiedSlots, true)
                ) {
                    $occupiedSlots[] = $currentSlot;
                    continue;
                }

                $freeSlots = array_values(array_diff(
                    $availableSlots,
                    $occupiedSlots
                ));

                if (empty($freeSlots)) {
                    if ($currentSlot !== null) {
                        $activeLog->parking_slot = null;
                        $activeLog->save();
                    }

                    continue;
                }

                $newSlot = $freeSlots[0];

                $activeLog->parking_slot = $newSlot;
                $activeLog->save();

                $occupiedSlots[] = $newSlot;
            }

            // Menentukan area parkir kendaraan baru.
            $prefix = $this->getParkingSlotPrefix(
                $validated['vehicle_type']
            );

            $availableSlots = $prefix === 'M'
                ? $motorSlots
                : $carSlots;

            $capacity = $prefix === 'M'
                ? $motorCapacity
                : $carCapacity;

            $freeSlots = array_values(array_diff(
                $availableSlots,
                $occupiedSlots
            ));

            // Jika area parkir penuh, kendaraan tidak disimpan.
            if (empty($freeSlots)) {
                return [
                    'success' => false,
                    'reason' => 'full',
                    'prefix' => $prefix,
                    'capacity' => $capacity,
                ];
            }

            $parkingSlot = $freeSlots[0];

            // Menyimpan kendaraan dengan petak otomatis.
            GateLog::create([
                'plate_number' => $plateNumber,
                'vehicle_type' => trim($validated['vehicle_type']),
                'driver_name' => trim($validated['driver_name']),
                'purpose' => trim($validated['purpose']),
                'gate_in' => now(),
                'gate_out' => null,
                'status' => 'Di Dalam',
                'officer_id' => $officerId,
                'notes' => $validated['notes'] ?? null,
                'parking_slot' => $parkingSlot,
            ]);

            return [
                'success' => true,
                'slot' => $parkingSlot,
            ];
        }, 3);

        if (($result['reason'] ?? null) === 'configuration') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Konfigurasi petak parkir Motor atau Mobil belum tersedia.'
                );
        }

        if (($result['reason'] ?? null) === 'duplicate') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Nomor polisi tersebut masih tercatat berada di area wisata.'
                );
        }

        if (($result['reason'] ?? null) === 'full') {
            $prefix = $result['prefix'];
            $capacity = $result['capacity'];

            $area = $prefix === 'M'
                ? 'parkir motor'
                : 'parkir mobil';

            $firstSlot = $prefix . '01';

            $lastSlot = $prefix . str_pad(
                (string) $capacity,
                2,
                '0',
                STR_PAD_LEFT
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    "Area {$area} penuh ({$firstSlot}–{$lastSlot}). Kendaraan belum disimpan."
                );
        }

        return redirect()
            ->route($this->getReturnRoute())
            ->with(
                'success',
                "Kendaraan berhasil dicatat masuk dan ditempatkan di petak {$result['slot']}."
            );
    }

    /**
     * Memperbarui data kendaraan.
     */
    public function update(Request $request, string $id)
    {
        $gateLog = GateLog::findOrFail($id);

        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'max:100'],
            'driver_name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $gateLog->update([
            'plate_number' => strtoupper(trim($validated['plate_number'])),
            'vehicle_type' => trim($validated['vehicle_type']),
            'driver_name' => trim($validated['driver_name']),
            'purpose' => trim($validated['purpose']),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route($this->getReturnRoute($request->input('return_to')))
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Menghapus data kendaraan.
     */
    public function destroy(Request $request, string $id)
    {
        $gateLog = GateLog::findOrFail($id);

        $gateLog->delete();

        return redirect()
            ->route($this->getReturnRoute($request->input('return_to')))
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }

    /**
     * Mencatat kendaraan keluar dari area wisata.
     */
    public function exit(string $id)
    {
        $gateLog = GateLog::findOrFail($id);

        if ($gateLog->status === 'Sudah Keluar') {
            return back()->with(
                'error',
                'Kendaraan ini sudah tercatat keluar.'
            );
        }

        if ($gateLog->status !== 'Di Dalam') {
            return back()->with(
                'error',
                'Kendaraan tidak sedang berada di dalam area wisata.'
            );
        }

        // Memperbarui waktu keluar, status, dan petak parkir.
        $gateLog->update([
            'gate_out' => now(),
            'status' => 'Sudah Keluar',
            'parking_slot' => null,
        ]);

        // Petugas tetap berada di halaman Kendaraan di Area.
        if ($this->isPetugasMode()) {
            return redirect('/petugas/kendaraan-area')
                ->with(
                    'success',
                    'Kendaraan berhasil dicatat keluar. Petaknya sekarang tersedia kembali.'
                );
        }

        // Admin diarahkan kembali ke halaman Monitoring.
        return redirect()
            ->route('monitoring.index')
            ->with(
                'success',
                'Kendaraan berhasil dicatat keluar. Petaknya sekarang tersedia kembali.'
            );
    }

    /**
     * Menampilkan daftar kendaraan yang sudah keluar.
     */
    public function petugasKeluar(): View
    {
        $vehicles = GateLog::with('officer')
            ->where('status', 'Sudah Keluar')
            ->whereNotNull('gate_out')
            ->latest('gate_out')
            ->get();

        return view('petugas.kendaraan-keluar.index', [
            'vehicles' => $vehicles,
            'totalExit' => $vehicles->count(),
        ]);
    }

    /**
     * Menampilkan halaman pencarian kendaraan.
     */
    public function petugasPencarian(Request $request): View
    {
        $keyword = trim($request->input('keyword', ''));
        $status = trim($request->input('status', ''));

        $query = GateLog::with('officer')
            ->latest('gate_in');

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('plate_number', 'like', '%' . $keyword . '%')
                    ->orWhere('vehicle_type', 'like', '%' . $keyword . '%')
                    ->orWhere('driver_name', 'like', '%' . $keyword . '%')
                    ->orWhere('purpose', 'like', '%' . $keyword . '%')
                    ->orWhere('notes', 'like', '%' . $keyword . '%');
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $gateLogs = $query->get();

        return view('petugas.pencarian.index', [
            'gateLogs' => $gateLogs,
            'keyword' => $keyword,
            'status' => $status,
            'totalResults' => $gateLogs->count(),
        ]);
    }

    /**
     * Menampilkan riwayat kendaraan petugas.
     *
     * Default menampilkan 10 data terbaru.
     * Parameter ?all=1 menampilkan seluruh data.
     */
    public function petugasRiwayat(Request $request): View
    {
        $keyword = trim($request->input('keyword', ''));
        $date = trim($request->input('date', ''));

        // Query dasar riwayat kendaraan.
        $query = GateLog::with('officer')
            ->where('status', 'Sudah Keluar')
            ->whereNotNull('gate_out')
            ->latest('gate_out');

        // Filter pencarian.
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('plate_number', 'like', '%' . $keyword . '%')
                    ->orWhere('vehicle_type', 'like', '%' . $keyword . '%')
                    ->orWhere('driver_name', 'like', '%' . $keyword . '%')
                    ->orWhere('purpose', 'like', '%' . $keyword . '%');
            });
        }

        // Filter tanggal keluar.
        if ($date !== '') {
            $query->whereDate('gate_out', $date);
        }

        // Menghitung seluruh data setelah filter diterapkan.
        $totalHistory = (clone $query)->count();

        // Memeriksa apakah tombol Lihat Semua diklik.
        $showAll = $request->boolean('all');

        // Default hanya mengambil 10 data terbaru.
        if (!$showAll) {
            $query->take(10);
        }

        // Mengambil data riwayat.
        $gateLogs = $query->get();

        return view('petugas.riwayat.index', [
            'gateLogs' => $gateLogs,
            'keyword' => $keyword,
            'date' => $date,
            'totalHistory' => $totalHistory,
            'showAll' => $showAll,
        ]);
    }
}
