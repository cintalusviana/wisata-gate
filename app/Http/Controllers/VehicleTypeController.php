<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::latest()->get();

        return view('vehicle-types.index', compact('vehicleTypes'));
    }

    public function create()
    {
        return view('vehicle-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:vehicle_types,name'],
        ]);

        VehicleType::create($validated);

        return redirect()
            ->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        return view('vehicle-types.show', compact('vehicleType'));
    }

    public function edit(string $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        return view('vehicle-types.edit', compact('vehicleType'));
    }

    public function update(Request $request, string $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:vehicle_types,name,' . $vehicleType->id,
            ],
        ]);

        $vehicleType->update($validated);

        return redirect()
            ->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        if ($vehicleType->vehicles()->exists()) {
            return redirect()
                ->route('vehicle-types.index')
                ->with('error', 'Jenis kendaraan tidak dapat dihapus karena masih digunakan oleh data kendaraan.');
        }

        $vehicleType->delete();

        return redirect()
            ->route('vehicle-types.index')
            ->with('success', 'Jenis kendaraan berhasil dihapus.');
    }
}