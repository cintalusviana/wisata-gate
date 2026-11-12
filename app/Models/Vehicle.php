<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'plate_number',
        'vehicle_type_id',
        'driver_name',
    ];

    /**
     * Relasi ke jenis kendaraan.
     *
     * Contoh: Mobil, Motor, Bus.
     */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(
            VehicleType::class,
            'vehicle_type_id'
        );
    }

    /**
     * Relasi ke riwayat kendaraan masuk dan keluar.
     *
     * Pencocokan dilakukan menggunakan nomor plat.
     */
    public function gateLogs(): HasMany
    {
        return $this->hasMany(
            GateLog::class,
            'plate_number',
            'plate_number'
        );
    }
}
