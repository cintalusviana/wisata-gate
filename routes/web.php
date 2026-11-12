<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DenahController;
use App\Http\Controllers\GateLogController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard Admin dan Petugas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,petugas'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/mode-petugas', [DashboardController::class, 'petugas'])
        ->name('mode-petugas');

});


/*
|--------------------------------------------------------------------------
| Route Khusus Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Monitoring Kendaraan
    |--------------------------------------------------------------------------
    */

    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index');

    Route::get('/monitoring/export', [MonitoringController::class, 'export'])
        ->name('monitoring.export');


    /*
    |--------------------------------------------------------------------------
    | Data Kendaraan
    |--------------------------------------------------------------------------
    */

    Route::get('/vehicles/export', [VehicleController::class, 'export'])
        ->name('vehicles.export');

    Route::resource('vehicles', VehicleController::class);


    /*
    |--------------------------------------------------------------------------
    | Jenis Kendaraan
    |--------------------------------------------------------------------------
    */

    Route::resource('vehicle-types', VehicleTypeController::class);


    /*
    |--------------------------------------------------------------------------
    | Denah Area Wisata
    |--------------------------------------------------------------------------
    */

    Route::get('/denah', [DenahController::class, 'index'])
        ->name('denah.index');

    Route::post('/denah/petak', [DenahController::class, 'storeSlots'])
        ->name('denah.slots.store');


    /*
    |--------------------------------------------------------------------------
    | Riwayat Kendaraan Admin
    |--------------------------------------------------------------------------
    | URL: /history
    */

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');


    /*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
*/

Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
    ->name('reports.export.pdf');

Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])
    ->name('reports.export.excel');

});


/*
|--------------------------------------------------------------------------
| Pencatatan Kendaraan Masuk dan Keluar
|--------------------------------------------------------------------------
| Dapat digunakan Admin dan Petugas.
*/

Route::middleware(['auth', 'role:admin,petugas'])->group(function () {

    Route::get('/gate-logs/create', [GateLogController::class, 'create'])
        ->name('gate-logs.create');

    Route::post('/gate-logs', [GateLogController::class, 'store'])
        ->name('gate-logs.store');

    Route::put('/gate-logs/{id}', [GateLogController::class, 'update'])
        ->name('gate-logs.update');

    Route::post('/gate-logs/{id}/exit', [GateLogController::class, 'exit'])
        ->name('gate-logs.exit');

    Route::delete('/gate-logs/{id}', [GateLogController::class, 'destroy'])
        ->name('gate-logs.destroy');

});


/*
|--------------------------------------------------------------------------
| Route Khusus Petugas Gate
|--------------------------------------------------------------------------
| Semua halaman menggunakan prefix /petugas.
*/

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Petugas
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'petugas'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Kendaraan di Area
        |--------------------------------------------------------------------------
        */

        Route::get('/kendaraan-area', [MonitoringController::class, 'petugasArea'])
            ->name('kendaraan-area');


        /*
        |--------------------------------------------------------------------------
        | Kendaraan Keluar
        |--------------------------------------------------------------------------
        */

        Route::get('/kendaraan-keluar', [GateLogController::class, 'petugasKeluar'])
            ->name('kendaraan-keluar');


        /*
        |--------------------------------------------------------------------------
        | Pencarian Kendaraan
        |--------------------------------------------------------------------------
        */

        Route::get('/pencarian', [GateLogController::class, 'petugasPencarian'])
            ->name('pencarian');


        /*
        |--------------------------------------------------------------------------
        | Riwayat Kendaraan Petugas
        |--------------------------------------------------------------------------
        | URL: /petugas/riwayat
        | Nama route: petugas.riwayat
        */

        Route::get('/riwayat', [GateLogController::class, 'petugasRiwayat'])
            ->name('riwayat');

    });


/*
|--------------------------------------------------------------------------
| Data Petugas
|--------------------------------------------------------------------------
| Khusus Admin.
*/

Route::resource('officers', OfficerController::class)
    ->middleware(['auth', 'role:admin']);
