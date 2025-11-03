<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanKreditController extends Controller
{
    /**
     * Tampilkan daftar pengajuan user yang sedang login
     */
    public function index()
    {
        $pengajuans = PengajuanKredit::where('user_id', Auth::id())->get();
        return view('user.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Simpan pengajuan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_fasilitas' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:1000000',
            'jangka_waktu' => 'required|integer|min:1',
            'nama_lengkap' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'required|string',
            'alamat_ktp' => 'required|string',
            'status_perkawinan' => 'required|string',
            'no_npwp' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'required|string',
            'agama' => 'required|string',
            'nama_ibu_kandung' => 'required|string',
            'status_rumah' => 'required|string',
            'email' => 'required|email',

            // pasangan
            'pasangan_nama' => 'nullable|string',
            'pasangan_no_ktp' => 'nullable|string',
            'pasangan_alamat_tinggal' => 'nullable|string',
            'pasangan_alamat_ktp' => 'nullable|string',
            'pasangan_pekerjaan' => 'nullable|string',
            'pasangan_email' => 'nullable|email',

            // penjamin
            'penjamin_nama' => 'nullable|string',
            'penjamin_no_ktp' => 'nullable|string',
            'penjamin_hubungan' => 'nullable|string',
            'penjamin_alamat' => 'nullable|string',
            'penjamin_email' => 'nullable|email',
        ]);

        $validated['user_id'] = Auth::id();

        PengajuanKredit::create($validated);

        return redirect()->back()->with('success', 'Pengajuan kredit berhasil dikirim!');
    }

    /**
     * (Opsional) Untuk admin/manajer melihat semua pengajuan
     */
    public function all()
    {
        $pengajuans = PengajuanKredit::with('user')->get();
        return view('admin.pengajuan.index', compact('pengajuans'));
    }
}
