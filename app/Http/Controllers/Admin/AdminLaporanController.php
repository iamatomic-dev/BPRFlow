<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CreditApplication;
use App\Models\CreditPayment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLaporanController extends Controller
{
    public function nasabah(Request $request)
    {
        $query = User::role('Nasabah')->with('nasabahProfile');

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $nasabahs = $query->latest()->get();

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.nasabah', compact('nasabahs', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('Laporan_Nasabah_' . date('Y-m-d') . '.pdf');
        }

        return view('admin.laporan.nasabah', compact('nasabahs', 'startDate', 'endDate'));
    }

    public function pengajuan(Request $request)
    {
        $query = CreditApplication::with(['nasabahProfile', 'creditFacility']);

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

        $byFacility = CreditApplication::where('status', 'Disetujui')
            ->whereYear('approved_at', $year)
            ->join('credit_facilities', 'credit_applications.credit_facility_id', '=', 'credit_facilities.id')
            ->select('credit_facilities.nama', DB::raw('count(*) as total_nasabah'), DB::raw('sum(jumlah_pinjaman) as total_plafond'))
            ->groupBy('credit_facilities.nama')
            ->get();

        $totalDisbursed = CreditApplication::where('status', 'Disetujui')->sum('jumlah_pinjaman');
        $outstanding = CreditPayment::where('status_pembayaran', '!=', 'Paid')->sum('tagihan_pokok');
        $profitBunga = CreditPayment::where('status_pembayaran', 'Paid')->sum('tagihan_bunga');

        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf.rekapitulasi', compact(
                'byFacility',
                'totalDisbursed',
                'outstanding',
                'profitBunga',
                'year'
            ))->setPaper('a4', 'portrait');

            return $pdf->download('Laporan_Rekapitulasi_' . $year . '.pdf');
        }

        return view('admin.laporan.rekapitulasi', compact('byFacility', 'totalDisbursed', 'outstanding', 'profitBunga', 'year'));
    }
}
