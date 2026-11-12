<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingConfiguration extends Model
{
    protected $fillable = [
        'vehicle_type',
        'total_slots',
    ];

    protected function casts(): array
    {
        return [
            'total_slots' => 'integer',
        ];
    }
}
