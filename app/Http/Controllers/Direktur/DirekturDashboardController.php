<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;
use App\Models\CreditPayment;
use Illuminate\Support\Facades\DB;

class DirekturDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Direktur']);
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

        $totalDisbursed = CreditApplication::where('status', 'Disetujui')->sum('jumlah_pinjaman');
        $outstanding = CreditPayment::where('status_pembayaran', '!=', 'Paid')->sum('tagihan_pokok');
        $profitBunga = CreditPayment::where('status_pembayaran', 'Paid')->sum('tagihan_bunga');

        return view('direktur.index', compact(
            'user',
            'totalPengajuan',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'totalDisbursed',
            'outstanding',
            'profitBunga'
        ));
    }
}
