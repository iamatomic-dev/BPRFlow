<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CreditApplication;
use App\Models\CreditPayment;
use App\Models\CreditFacility;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLaporanController extends Controller
{
    public function pengajuan(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility', 'collateral']);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;

        if ($startDate && $endDate) {
            $query->whereBetween('submitted_at', [$startDate, $endDate . ' 23:59:59']);
        }

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereNotNull('submitted_at');
        }

        $applications = $query->orderBy('submitted_at', 'desc')->get();

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.pengajuan', compact('applications', 'startDate', 'endDate', 'status'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('Laporan_Pengajuan_' . $status . date('Y-m-d') . '.pdf');
        }

        return view('admin.laporan.pengajuan', compact('applications', 'startDate', 'endDate', 'status'));
    }

    public function analisis(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility', 'manager'])
            ->whereNotNull('submitted_at');

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($startDate && $endDate) {
            $query->whereBetween('submitted_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $applications = $query->latest('submitted_at')->get();

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.analisis', compact('applications', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Analisis' . date('Y-m-d') . '.pdf');
        }

        return view('admin.laporan.analisis', compact('applications', 'startDate', 'endDate'));
    }

    public function monitoring(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility'])
            ->where('status', 'Disetujui');

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($startDate && $endDate) {
            $query->whereBetween('approved_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $credits = $query
            ->withCount(['payments as total_angsuran'])
            ->withCount(['payments as sudah_bayar' => function ($q) {
                $q->where('status_pembayaran', 'Paid');
            }])
            ->withSum(['payments as total_tagihan' => function ($q) {
                $q->select(DB::raw('SUM(jumlah_angsuran)'));
            }], 'jumlah_angsuran')
            ->withSum(['payments as total_terbayar' => function ($q) {
                $q->select(DB::raw('SUM(jumlah_bayar)'));
            }], 'jumlah_bayar')
            ->latest('approved_at')
            ->get();

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.monitoring', compact('credits', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Monitoring.pdf');
        }

        return view('admin.laporan.monitoring', compact('credits', 'startDate', 'endDate'));
    }

    public function rekapitulasi(Request $request)
    {
        $year = $request->year ?? date('Y');

        // Ambil Semua Fasilitas Kredit
        $data = CreditFacility::with(['creditApplications.payments'])
            ->get()
            ->map(function ($facility) use ($year) {
                
                // Filter aplikasi: Hanya yang Disetujui/Lunas di tahun tersebut (atau semua aktif)
                // Disini kita ambil yang disetujui pada tahun tersebut
                $apps = $facility->creditApplications->filter(function ($app) use ($year) {
                    return in_array($app->status, ['Disetujui', 'Lunas']) && 
                           $app->approved_at->format('Y') == $year;
                });

                // Inisialisasi Variable Hitungan
                $jumlahPlafond = 0;
                
                $angsuranPokok = 0;
                $angsuranBunga = 0;
                
                $tunggakanPokok = 0;
                $tunggakanBunga = 0;
                $tunggakanDenda = 0;

                foreach ($apps as $app) {
                    $jumlahPlafond += $app->jumlah_pinjaman;

                    // 1. Hitung ANGSURAN (Yang SUDAH DIBAYAR / PAID)
                    // ambil dari payments yang statusnya 'Paid'
                    $paidPayments = $app->payments->where('status_pembayaran', 'Paid');
                    $angsuranPokok += $paidPayments->sum('tagihan_pokok');
                    $angsuranBunga += $paidPayments->sum('tagihan_bunga');

                    // 2. Hitung TUNGGAKAN (Jatuh Tempo < Sekarang & Belum Lunas)
                    // ambil dari payments yang status != Paid DAN tanggal < hari ini
                    $latePayments = $app->payments
                        ->where('status_pembayaran', '!=', 'Paid')
                        ->where('jatuh_tempo', '<', now());
                    
                    $tunggakanPokok += $latePayments->sum('tagihan_pokok');
                    $tunggakanBunga += $latePayments->sum('tagihan_bunga');
                    $tunggakanDenda += $latePayments->sum('denda');
                }

                // Masukkan hasil hitungan ke object facility
                $facility->rekap = (object) [
                    'jumlah' => $jumlahPlafond,
                    'angsuran_pokok' => $angsuranPokok,
                    'angsuran_bunga' => $angsuranBunga,
                    'total_angsuran' => $angsuranPokok + $angsuranBunga,
                    'tunggakan_pokok' => $tunggakanPokok,
                    'tunggakan_bunga' => $tunggakanBunga,
                    'tunggakan_denda' => $tunggakanDenda,
                    'total_tunggakan' => $tunggakanPokok + $tunggakanBunga + $tunggakanDenda,
                ];

                return $facility;
            });

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.rekapitulasi', compact('data', 'year'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Rekapitulasi_' . $year . '.pdf');
        }

        return view('admin.laporan.rekapitulasi', compact('data', 'year'));
    }

    public function realisasi(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Ambil Fasilitas beserta Aplikasi Kreditnya yang sudah cair (Disetujui/Lunas/Macet)
        // Filter berdasarkan tanggal akad (realisasi)
        $facilities = CreditFacility::with(['creditApplications' => function($q) use ($startDate, $endDate) {
                // Filter Status: Yang sudah cair (bukan draft/menunggu/ditolak)
                $q->whereIn('status', ['Disetujui', 'Lunas', 'Macet']);
                
                // Filter Tanggal Realisasi (Akad)
                if ($startDate && $endDate) {
                    $q->whereBetween('tgl_akad', [$startDate, $endDate . ' 23:59:59']);
                }
                
                $q->with('nasabahProfile'); // Load profil nasabah
            }])
            ->get();

        // Logic Export PDF
        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.realisasi', compact('facilities', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Realisasi.pdf');
        }

        return view('admin.laporan.realisasi', compact('facilities', 'startDate', 'endDate'));
    }
}
