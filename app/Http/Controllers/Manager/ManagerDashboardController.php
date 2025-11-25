<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;

class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Manager']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Hitung Statistik
        // 1. Total: Semua yang sudah disubmit (bukan draft)
        $totalPengajuan = CreditApplication::whereNotNull('submitted_at')->count();

        // 2. Menunggu Verifikasi
        $menungguVerifikasi = CreditApplication::where('status', 'Menunggu Verifikasi')->count();

        // 3. Disetujui
        $disetujui = CreditApplication::where('status', 'Disetujui')->count();

        // 4. Ditolak
        $ditolak = CreditApplication::where('status', 'Ditolak')->count();

        return view('manager.index', compact(
            'user',
            'totalPengajuan',
            'menungguVerifikasi',
            'disetujui',
            'ditolak'
        ));
    }
}
