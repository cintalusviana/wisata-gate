<?php

namespace Database\Seeders;

use App\Models\ParkingConfiguration;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed konfigurasi awal aplikasi WisataGate.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Petak Parkir Motor
        |--------------------------------------------------------------------------
        |
        | Jika belum tersedia, buat 24 petak awal:
        | M01 sampai M24.
        |
        */

        ParkingConfiguration::firstOrCreate(
            [
                'vehicle_type' => 'Motor',
            ],
            [
                'total_slots' => 24,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Petak Parkir Mobil
        |--------------------------------------------------------------------------
        |
        | Jika belum tersedia, buat 16 petak awal:
        | R01 sampai R16.
        |
        */

        ParkingConfiguration::firstOrCreate(
            [
                'vehicle_type' => 'Mobil',
            ],
            [
                'total_slots' => 16,
            ]
        );
    }
}
