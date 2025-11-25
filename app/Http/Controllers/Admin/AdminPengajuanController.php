<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;

class AdminPengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Mulai Query dari yang sudah disubmit
        $query = CreditApplication::with(['user', 'nasabahProfile', 'creditFacility'])
            ->whereNotNull('submitted_at');

        // 1. Logic Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 2. Logic Search (Nama / No Pengajuan)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('nasabahProfile', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Eksekusi Query
        $applications = $query->latest('submitted_at')
            ->paginate(10)
            ->withQueryString(); // PENTING: Agar filter tidak hilang saat klik halaman 2

        return view('admin.pengajuan.index', compact('applications'));
    }

    public function show($id)
    {
        // Eager load SEMUA relasi biar tidak n+1 problem
        $application = CreditApplication::with([
            'user',
            'nasabahProfile',
            'creditFacility',
            'detail',
            'collateral',
            'documents'
        ])->findOrFail($id);

        return view('admin.pengajuan.show', compact('application'));
    }
}
