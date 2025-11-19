<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditFacility;
use App\Models\CreditFacilityTier;

class SimulasiKreditController extends Controller
{
    public function index()
    {
        // Ambil hanya fasilitas yang aktif
        $facilities = CreditFacility::where('aktif', 1)->get();
        return view('nasabah.simulasi.index', compact('facilities'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:credit_facilities,id',
            'amount'      => 'required|numeric|min:1000000',
            'tenor'       => 'required|numeric|min:1',
        ]);

        $facility = CreditFacility::find($request->facility_id);
        $amount   = $request->amount;
        $tenor    = $request->tenor;

        // 1. Validasi Max Tenor sesuai Fasilitas
        if ($tenor > $facility->max_jangka_waktu) {
            return response()->json([
                'status' => 'error',
                'message' => "Maksimal jangka waktu untuk " . $facility->nama . " adalah " . $facility->max_jangka_waktu . " bulan."
            ], 422);
        }

        // 2. Cari Tier Bunga berdasarkan Plafond (Amount)
        // Logika: Cari yang min_plafond <= amount DAN (max_plafond >= amount ATAU max_plafond NULL)
        $tier = CreditFacilityTier::where('credit_facility_id', $facility->id)
            ->where('min_plafond', '<=', $amount)
            ->where(function ($query) use ($amount) {
                $query->where('max_plafond', '>=', $amount)
                      ->orWhereNull('max_plafond');
            })
            ->first();

        if (!$tier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nominal pengajuan tidak masuk dalam range skema kredit kami.'
            ], 422);
        }

        // 3. Rumus Perhitungan (Asumsi: Bunga Flat per Bulan)
        // Bunga (%) dari database, misal 2.50
        $bungaPersen = $tier->bunga; 
        
        // Pokok per bulan
        $angsuranPokok = $amount / $tenor;
        
        // Bunga per bulan = Plafond * (Bunga% / 100)
        $angsuranBunga = $amount * ($bungaPersen / 100);
        
        // Total Angsuran
        $totalAngsuran = $angsuranPokok + $angsuranBunga;

        return response()->json([
            'status' => 'success',
            'data' => [
                'facility_name' => $facility->nama,
                'plafond'       => number_format($amount, 0, ',', '.'),
                'tenor'         => $tenor,
                'bunga_persen'  => $bungaPersen, // Tampilkan bunga yang didapat
                'angsuran_pokok'=> number_format($angsuranPokok, 0, ',', '.'),
                'angsuran_bunga'=> number_format($angsuranBunga, 0, ',', '.'),
                'total_angsuran'=> number_format($totalAngsuran, 0, ',', '.'),
                'total_bayar'   => number_format($totalAngsuran * $tenor, 0, ',', '.')
            ]
        ]);
    }
}