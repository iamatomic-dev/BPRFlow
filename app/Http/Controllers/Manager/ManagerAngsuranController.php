<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditPayment;
use Illuminate\Support\Facades\Auth;

class ManagerAngsuranController extends Controller
{
    /**
     * Membatalkan transaksi pembayaran yang sudah diinput Admin
     */
    public function reverse(Request $request, $paymentId)
    {
        $payment = CreditPayment::findOrFail($paymentId);

        // Pastikan payment sudah dibayar sebelum dibatalkan
        if ($payment->status_pembayaran != 'Paid' && $payment->status_pembayaran != 'Partial') {
            return back()->with('error', 'Pembayaran belum lunas, tidak bisa dibatalkan.');
        }

        $request->validate([
            'reversal_date' => 'required|date',
            'reversal_note' => 'required|string|min:10',
        ]);

        // Reset status dan simpan jejak reversal
        $payment->update([
            'reversal_date'     => $request->reversal_date,
            'reversal_note'     => $request->reversal_note,
            'reversal_user_id'  => Auth::id(),
            'jumlah_bayar'      => 0, // Nolkan jumlah bayar
            'denda'             => 0, // Nolkan denda
            'status_pembayaran' => 'Unpaid', // Kembalikan ke status awal
        ]);

        // Cek apakah aplikasi induk (credit_application) statusnya jadi tidak lunas
        // Jika statusnya tadinya 'Lunas', kembalikan ke 'Disetujui'
        if ($payment->application->status === 'Lunas') {
            $payment->application->update(['status' => 'Disetujui']);
        }


        return back()->with('success', 'Transaksi angsuran ke-' . $payment->angsuran_ke . ' berhasil dibatalkan (Reversal).');
    }
}
