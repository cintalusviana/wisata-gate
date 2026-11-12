<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GateLog extends Model
{
    /**
     * Kolom yang dapat diisi secara mass assignment.
     */
    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'driver_name',
        'purpose',
        'gate_in',
        'gate_out',
        'status',
        'officer_id',
        'notes',
        'parking_slot',
    ];

    /**
     * Konversi tipe data tanggal dan waktu.
     */
    protected $casts = [
        'gate_in' => 'datetime',
        'gate_out' => 'datetime',
    ];

    /**
     * Relasi ke petugas yang mencatat kendaraan.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Relasi ke data kendaraan berdasarkan nomor polisi.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            Vehicle::class,
            'plate_number',
            'plate_number'
        );
    }
}
