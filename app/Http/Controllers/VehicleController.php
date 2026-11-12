<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Menampilkan data kendaraan.
     */
    public function index()
    {
        $vehicles = Vehicle::with([
            'vehicleType',
            'gateLogs' => function ($query) {
                $query->latest('gate_in');
            },
        ])->latest()->get();

        $vehicleTypes = VehicleType::orderBy('name')->get();

        return view('vehicles.index', compact(
            'vehicles',
            'vehicleTypes'
        ));
    }

    /**
     * Menyimpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                'unique:vehicles,plate_number',
            ],
            'vehicle_type_id' => [
                'required',
                'exists:vehicle_types,id',
            ],
            'driver_name' => [
                'required',
                'string',
                'max:100',
            ],
        ], [
            'plate_number.required' => 'Plat nomor wajib diisi.',
            'plate_number.unique' => 'Plat nomor sudah terdaftar.',
            'vehicle_type_id.required' => 'Jenis kendaraan wajib dipilih.',
            'vehicle_type_id.exists' => 'Jenis kendaraan tidak valid.',
            'driver_name.required' => 'Nama pengemudi wajib diisi.',
        ]);

        Vehicle::create($validated);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Edit dilakukan melalui form pada halaman index.
     * Route ini dipertahankan agar URL edit lama tidak menyebabkan error.
     */
    public function edit(string $id)
    {
        return redirect()
            ->route('vehicles.index')
            ->with('info', 'Silakan pilih tombol Edit pada tabel kendaraan.');
    }

    /**
     * Memperbarui data kendaraan.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicle->id),
            ],
            'vehicle_type_id' => [
                'required',
                'exists:vehicle_types,id',
            ],
            'driver_name' => [
                'required',
                'string',
                'max:100',
            ],
        ], [
            'plate_number.required' => 'Plat nomor wajib diisi.',
            'plate_number.unique' => 'Plat nomor sudah digunakan kendaraan lain.',
            'vehicle_type_id.required' => 'Jenis kendaraan wajib dipilih.',
            'vehicle_type_id.exists' => 'Jenis kendaraan tidak valid.',
            'driver_name.required' => 'Nama pengemudi wajib diisi.',
        ]);

        $vehicle->update($validated);

        /*
         * Sinkronkan data kendaraan pada riwayat gate_logs.
         * Tabel gate_logs menggunakan kolom plate_number,
         * vehicle_type, dan driver_name, bukan vehicle_id.
         */
        $vehicle->gateLogs()->update([
            'plate_number' => $validated['plate_number'],
            'vehicle_type' => $vehicle->vehicleType()->value('name'),
            'driver_name' => $validated['driver_name'],
        ]);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Menghapus kendaraan.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->gateLogs()->exists()) {
            return redirect()
                ->route('vehicles.index')
                ->with(
                    'error',
                    'Kendaraan tidak dapat dihapus karena sudah memiliki riwayat monitoring.'
                );
        }

        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }

    /**
     * Export CSV sederhana.
     */
    public function export()
    {
        $vehicles = Vehicle::with('vehicleType')
            ->orderBy('plate_number')
            ->get();

        $filename = 'data-kendaraan-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($vehicles) {
            $handle = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca dengan baik di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Plat Nomor',
                'Jenis Kendaraan',
                'Nama Pengemudi',
                'Tanggal Terdaftar',
            ], ';');

            foreach ($vehicles as $index => $vehicle) {
                fputcsv($handle, [
                    $index + 1,
                    $vehicle->plate_number,
                    $vehicle->vehicleType->name ?? '-',
                    $vehicle->driver_name,
                    $vehicle->created_at?->format('d-m-Y H:i') ?? '-',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
