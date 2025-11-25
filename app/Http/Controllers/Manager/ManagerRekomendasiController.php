<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;
use Illuminate\Support\Facades\Auth;

class ManagerRekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility', 'user'])
            ->where('status', 'Menunggu Verifikasi')
            ->whereNotNull('slik_path')
            ->whereNull('recommendation_status');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('nasabahProfile', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $applications = $query->latest('submitted_at')->paginate(10);

        return view('manager.rekomendasi.index', compact('applications'));
    }

    public function show($id)
    {
        $application = CreditApplication::with([
            'nasabahProfile',
            'creditFacility',
            'detail',
            'collateral',
            'documents'
        ])->findOrFail($id);

        return view('manager.rekomendasi.show', compact('application'));
    }

    public function update(Request $request, $id)
    {
        $application = CreditApplication::findOrFail($id);

        $request->validate([
            'recommendation_status' => 'required|in:Rekomendasi Disetujui,Rekomendasi Ditolak',
            'recommended_amount'    => 'required_if:recommendation_status,Rekomendasi Disetujui|numeric|min:0',
            'recommended_tenor'     => 'required_if:recommendation_status,Rekomendasi Disetujui|numeric|min:1',
            'manager_note'          => 'required|string|min:10',
        ]);

        $application->update([
            'manager_id'            => Auth::id(),
            'managed_at'            => now(),
            'recommendation_status' => $request->recommendation_status,
            'recommended_amount'    => $request->recommended_amount,
            'recommended_tenor'     => $request->recommended_tenor,
            'manager_note'          => $request->manager_note,
        ]);

        return redirect()->route('manager.rekomendasi.index')
            ->with('success', 'Rekomendasi berhasil dikirim ke Direktur.');
    }

    public function riwayat(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility'])
            ->where('manager_id', Auth::id())
            ->whereNotNull('recommendation_status');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('nasabahProfile', function($subQ) use ($search) {
                      $subQ->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->latest('managed_at')->paginate(10);
        return view('manager.rekomendasi.riwayat', compact('applications'));
    }
}
