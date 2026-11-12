<?php

namespace App\Http\Controllers;

use App\Models\GateLog;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Menampilkan seluruh riwayat kendaraan.
     */
    public function index(): View
    {
        $histories = GateLog::with('officer')
            ->latest('gate_in')
            ->get();

        return view('history.index', compact('histories'));
    }
}
