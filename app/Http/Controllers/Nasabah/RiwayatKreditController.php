<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditApplication;

class RiwayatKreditController extends Controller
{
    /**
     * Menampilkan daftar riwayat pengajuan kredit
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil data aplikasi kredit milik user
        // Kita eager load 'creditFacility' untuk menampilkan nama fasilitas
        $applications = CreditApplication::with('creditFacility')
            ->where('user_id', $userId)
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Gunakan pagination biar rapi kalau datanya banyak

        return view('nasabah.riwayat.index', compact('applications'));
    }

    /**
     * Menampilkan detail status (Opsional, untuk melihat detail lengkap)
     */
    public function show($id)
    {
        $application = CreditApplication::with([
            'nasabahProfile', 
            'creditFacility', 
            'detail', 
            'collateral', 
            'documents'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('nasabah.riwayat.show', compact('application'));
    }
}