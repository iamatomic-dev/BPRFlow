<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditApplication;
use App\Models\CreditPayment;
use App\Models\CreditFacilityTier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirekturPersetujuanController extends Controller
{
    public function index()
    {
        $applications = CreditApplication::with(['nasabahProfile', 'user', 'creditFacility'])
            ->where('status', 'Menunggu Verifikasi') 
            ->whereNull('approved_at')
            ->latest('submitted_at')
            ->paginate(10);

        return view('direktur.persetujuan.index', compact('applications'));
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

        return view('direktur.persetujuan.show', compact('application'));
    }

    private function generateNoPK($facilityCode)
    {
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $formatTengah = "/PK-{$facilityCode}/{$bulanRomawi}/{$tahun}";

        $lastApp = CreditApplication::where('no_perjanjian_kredit', 'like', '%' . $formatTengah)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastApp) {
            $lastNo = (int) explode('/', $lastApp->no_perjanjian_kredit)[0];
            $nextNo = $lastNo + 1;
        } else {
            $nextNo = 1;
        }

        return str_pad($nextNo, 3, '0', STR_PAD_LEFT) . $formatTengah;
    }

    private function getRomawi($bulan)
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $map[$bulan];
    }

    public function update(Request $request, $id)
    {
        $application = CreditApplication::findOrFail($id);

        $request->validate([
            'decision' => 'required|in:approve,reject',
            'note'     => 'nullable|string',
            'final_amount' => 'required_if:decision,approve|numeric|min:1000000',
            'final_tenor'  => 'required_if:decision,approve|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            if ($request->decision === 'reject') {
                $application->update([
                    'status'      => 'Ditolak',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

            } else {
                $amount = $request->final_amount;
                $tenor  = $request->final_tenor;
                
                $facilityId = $application->credit_facility_id;

                $tier = CreditFacilityTier::where('credit_facility_id', $facilityId)
                    ->where('min_plafond', '<=', $amount)
                    ->where(function ($q) use ($amount) {
                        $q->where('max_plafond', '>=', $amount)
                          ->orWhereNull('max_plafond');
                    })
                    ->first();
                
                $bungaPersen = $tier ? $tier->bunga : 1.5; 

                $angsuranPokok = $amount / $tenor;
                $angsuranBunga = $amount * ($bungaPersen / 100);
                $totalAngsuran = $angsuranPokok + $angsuranBunga;

                $jatuhTempo = now()->addMonth();
                
                for ($i = 1; $i <= $tenor; $i++) {
                    CreditPayment::create([
                        'credit_application_id' => $application->id,
                        'angsuran_ke'       => $i,
                        'jatuh_tempo'       => $jatuhTempo->copy()->addMonths($i - 1),
                        'tagihan_pokok'     => $angsuranPokok,
                        'tagihan_bunga'     => $angsuranBunga,
                        'jumlah_angsuran'   => $totalAngsuran,
                        'status_pembayaran' => 'Unpaid'
                    ]);
                }

                $kodeFasilitas = $application->creditFacility->kode ?? 'GEN'; 
                $noPK = $this->generateNoPK($kodeFasilitas);

                $application->update([
                    'status'               => 'Disetujui',
                    'approved_by'          => Auth::id(),
                    'approved_at'          => now(),
                    'no_perjanjian_kredit' => $noPK,
                    'tgl_akad'             => now(),
                    'recommended_amount'   => $amount,
                    'recommended_tenor'    => $tenor,
                ]);
            }

            DB::commit();

            $statusMsg = $request->decision === 'approve' ? 'disetujui' : 'ditolak';
            return redirect()->route('direktur.persetujuan.index')
                ->with('success', "Pengajuan berhasil $statusMsg dengan plafond Rp " . number_format($request->final_amount ?? 0));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
