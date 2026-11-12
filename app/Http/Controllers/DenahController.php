<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use App\Models\ParkingConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DenahController extends Controller
{
    /**
     * Menampilkan denah area wisata dan petak parkir.
     */
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Jumlah Petak Parkir
        |--------------------------------------------------------------------------
        */

        $parkingConfigurations = ParkingConfiguration::query()
            ->get()
            ->keyBy('vehicle_type');

        $motorTotalSlots = max(
            0,
            (int) ($parkingConfigurations->get('Motor')?->total_slots ?? 24)
        );

        $mobilTotalSlots = max(
            0,
            (int) ($parkingConfigurations->get('Mobil')?->total_slots ?? 16)
        );

        /*
        |--------------------------------------------------------------------------
        | Membuat Nomor Petak Secara Dinamis
        |--------------------------------------------------------------------------
        */

        $motorSlots = $motorTotalSlots > 0
            ? array_map(
                fn ($number) => sprintf('M%02d', $number),
                range(1, $motorTotalSlots)
            )
            : [];

        $carSlots = $mobilTotalSlots > 0
            ? array_map(
                fn ($number) => sprintf('R%02d', $number),
                range(1, $mobilTotalSlots)
            )
            : [];

        $parkingSlots = array_merge($motorSlots, $carSlots);

        /*
        |--------------------------------------------------------------------------
        | Mengambil Kendaraan yang Masih Berada di Area Wisata
        |--------------------------------------------------------------------------
        */

        $activeGateLogs = GateLog::query()
            ->where('status', 'Di Dalam')
            ->whereNull('gate_out')
            ->orderBy('gate_in')
            ->orderBy('id')
            ->get();

        $totalInside = $activeGateLogs->count();

        /*
        |--------------------------------------------------------------------------
        | Mengelompokkan Kendaraan Berdasarkan Jenis
        |--------------------------------------------------------------------------
        */

        $vehicleTypeCounts = $activeGateLogs
            ->groupBy(function ($log) {
                return $log->vehicle_type ?: 'Jenis tidak diketahui';
            })
            ->map(function ($logs) {
                return $logs->count();
            })
            ->sortDesc();

        /*
        |--------------------------------------------------------------------------
        | Menyelaraskan Petak Kendaraan Aktif
        |--------------------------------------------------------------------------
        |
        | Kendaraan aktif yang masih menggunakan kode petak lama atau
        | belum memiliki petak akan diberi petak kosong sesuai jenisnya.
        |
        */

        $parkingLogsBySlot = collect();

        DB::transaction(function () use (
            $motorSlots,
            $carSlots,
            $parkingSlots,
            &$parkingLogsBySlot
        ) {
            $activeLogs = GateLog::query()
                ->where('status', 'Di Dalam')
                ->whereNull('gate_out')
                ->orderBy('gate_in')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $occupiedSlots = $activeLogs
                ->pluck('parking_slot')
                ->filter(fn ($slot) => in_array(
                    $slot,
                    $parkingSlots,
                    true
                ))
                ->unique()
                ->values()
                ->all();

            foreach ($activeLogs as $log) {
                // Petak yang masih valid tidak perlu dipindahkan.
                if (in_array($log->parking_slot, $parkingSlots, true)) {
                    continue;
                }

                $type = strtolower(trim($log->vehicle_type ?? ''));

                $isMotor = str_contains($type, 'motor')
                    || str_contains($type, 'sepeda')
                    || str_contains($type, 'scooter')
                    || str_contains($type, 'skuter');

                $availableSlots = $isMotor
                    ? $motorSlots
                    : $carSlots;

                $freeSlots = array_values(array_diff(
                    $availableSlots,
                    $occupiedSlots
                ));

                // Jangan menghapus atau mengganti data jika kelompok penuh.
                if (empty($freeSlots)) {
                    continue;
                }

                $newSlot = $freeSlots[0];

                $log->parking_slot = $newSlot;
                $log->save();

                $occupiedSlots[] = $newSlot;
            }

            /*
             * Ambil ulang data setelah penempatan petak selesai.
             * Hanya petak yang ada pada konfigurasi yang ditampilkan.
             */
            $updatedActiveLogs = GateLog::query()
                ->where('status', 'Di Dalam')
                ->whereNull('gate_out')
                ->whereIn('parking_slot', $parkingSlots)
                ->orderBy('gate_in')
                ->orderBy('id')
                ->get();

            $parkingLogsBySlot = $updatedActiveLogs
                ->groupBy('parking_slot')
                ->map(function ($logs) {
                    return $logs->first();
                });
        });

        /*
        |--------------------------------------------------------------------------
        | Ringkasan Petak Parkir
        |--------------------------------------------------------------------------
        */

        $occupiedSlots = $parkingLogsBySlot->count();

        $totalSlots = count($parkingSlots);

        $emptySlots = max(
            $totalSlots - $occupiedSlots,
            0
        );

        $motorTerisi = $parkingLogsBySlot
            ->filter(function ($log, $slot) {
                return str_starts_with($slot, 'M');
            })
            ->count();

        $mobilTerisi = $parkingLogsBySlot
            ->filter(function ($log, $slot) {
                return str_starts_with($slot, 'R');
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Kirim Data ke Halaman Denah
        |--------------------------------------------------------------------------
        */

        return view('denah.index', [
            'activeGateLogs' => $activeGateLogs,
            'totalInside' => $totalInside,
            'vehicleTypeCounts' => $vehicleTypeCounts,

            'parkingConfigurations' => $parkingConfigurations,
            'motorTotalSlots' => $motorTotalSlots,
            'mobilTotalSlots' => $mobilTotalSlots,

            'motorSlots' => $motorSlots,
            'carSlots' => $carSlots,
            'parkingSlots' => $parkingSlots,

            'parkingLogsBySlot' => $parkingLogsBySlot,
            'occupiedSlots' => $occupiedSlots,
            'totalSlots' => $totalSlots,
            'emptySlots' => $emptySlots,

            'motorTerisi' => $motorTerisi,
            'mobilTerisi' => $mobilTerisi,
        ]);
    }

    /**
     * Menambahkan jumlah petak parkir.
     */
    public function storeSlots(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:Motor,Mobil',
            'additional_slots' => 'required|integer|min:1|max:1000',
        ], [
            'vehicle_type.required' => 'Silakan pilih jenis kendaraan.',
            'vehicle_type.in' => 'Jenis kendaraan tidak valid.',
            'additional_slots.required' => 'Jumlah petak wajib diisi.',
            'additional_slots.integer' => 'Jumlah petak harus berupa angka bulat.',
            'additional_slots.min' => 'Jumlah petak minimal 1.',
            'additional_slots.max' => 'Jumlah petak maksimal 1000 dalam sekali penambahan.',
        ]);

        $configuration = ParkingConfiguration::firstOrCreate(
            ['vehicle_type' => $validated['vehicle_type']],
            ['total_slots' => 0]
        );

        $configuration->increment(
            'total_slots',
            (int) $validated['additional_slots']
        );

        $configuration->refresh();

        return redirect()
            ->route('denah.index')
            ->with(
                'success',
                'Berhasil menambahkan '
                    . $validated['additional_slots']
                    . ' petak '
                    . strtolower($validated['vehicle_type'])
                    . '. Total petak sekarang: '
                    . $configuration->total_slots
                    . '.'
            );
    }
}
