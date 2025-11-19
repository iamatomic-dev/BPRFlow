<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NasabahProfile;
use App\Models\CreditApplication;
use App\Models\CreditCollateral;
use App\Models\CreditDocument;
use App\Models\CreditFacility;
use App\Models\CreditApplicationDetail;
use Illuminate\Support\Facades\Storage;

class PengajuanKreditController extends Controller
{
    /**
     * Helper untuk memulihkan session ID jika hilang tapi ada draft di DB
     */
    private function ensureApplicationSession()
    {
        if (!session()->has('application_id')) {
            $draft = CreditApplication::where('user_id', Auth::id())
                ->whereIn('status', ['draft_step1', 'draft_step2', 'draft_step3'])
                ->latest()
                ->first();

            if ($draft) {
                session(['application_id' => $draft->id]);
                return $draft;
            }
        }
        return null;
    }

    // ================= STEP 1: DATA DIRI =================
    public function createStep1()
    {
        $userId = Auth::id();

        // 1. CEK APAKAH ADA PENGAJUAN YANG SEDANG DIPROSES / AKTIF
        $activeApplication = CreditApplication::where('user_id', $userId)
            ->whereIn('status', ['Menunggu Verifikasi', 'Disetujui'])
            ->exists();

        if ($activeApplication) {
            return redirect()->route('nasabah.dashboard')
                ->with('warning', 'Anda masih memiliki pengajuan yang sedang diproses atau pinjaman aktif.');
        }

        // 2. CEK APAKAH ADA DRAFT (Mode Lanjutkan/Edit Paksa)
        $draft = CreditApplication::where('user_id', $userId)
            ->whereIn('status', ['draft_step1', 'draft_step2', 'draft_step3'])
            ->latest()
            ->first();

        if ($draft) {
            // Set session ulang untuk jaga-jaga
            session(['application_id' => $draft->id]);

            // Redirect sesuai progres terakhir
            if ($draft->status === 'draft_step2') {
                return redirect()->route('pengajuan.step2');
            } elseif ($draft->status === 'draft_step3') {
                // Jika draft_step3, cek apakah sudah lengkap atau belum.
                // Amannya ke step3 dulu, nanti step3 yang lempar ke review jika user mau
                return redirect()->route('pengajuan.step3');
            }
            // Jika draft_step1, biarkan lanjut di bawah (render view step 1)
        }

        $profile = NasabahProfile::firstOrNew(['user_id' => $userId]);

        return view('nasabah.pengajuan.step1', compact('profile'));
    }

    public function postStep1(Request $request)
    {
        $userId = Auth::id();

        // Validasi Input
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'no_ktp' => 'required|digits:16|unique:nasabah_profiles,no_ktp,' . $userId . ',user_id',
            'no_hp' => 'required|numeric|max_digits:15|unique:nasabah_profiles,no_hp,' . $userId . ',user_id',
            'email' => 'required|email',
            'alamat_tinggal' => 'required|string',
            'alamat_ktp' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'agama' => 'required|string',
            'nama_ibu_kandung' => 'required|string',
            'status_perkawinan' => 'required|string',
            'no_npwp' => 'nullable|numeric|min_digits:15|max_digits:16',
            'status_rumah' => 'required|string',
        ]);

        // Simpan Profil
        NasabahProfile::updateOrCreate(
            ['user_id' => $userId],
            $validated
        );

        // LOGIKA PENTING: Create vs Update Application
        // Cari draft yang sudah ada
        $application = CreditApplication::where('user_id', $userId)
            ->whereIn('status', ['draft_step1', 'draft_step2', 'draft_step3'])
            ->first();

        if (!$application) {
            // Jika TIDAK ADA draft sama sekali, barulah kita buat baru.
            // Ini terjadi jika nasabah baru pertama kali daftar, atau pengajuan sebelumnya sudah selesai/ditolak.
            $application = CreditApplication::create([
                'user_id' => $userId,
                'status' => 'draft_step1'
            ]);
        } else {
            // Jika SUDAH ADA, kita update 'updated_at' nya saja biar sistem tahu nasabah baru aktif.
            // Kita TIDAK mengubah statusnya. Misal dia sudah di draft_step2, biarkan tetap di situ.
            $application->touch();
        }

        session(['application_id' => $application->id]);

        // Redirect logic
        if ($application->status === 'draft_step2') {
            return redirect()->route('pengajuan.step2')->with('success', 'Data profil diperbarui.');
        } elseif ($application->status === 'draft_step3') {
            return redirect()->route('pengajuan.step3')->with('success', 'Data profil diperbarui.');
        }

        return redirect()->route('pengajuan.step2')->with('success', 'Data profil disimpan.');
    }

    // ================= STEP 2: FASILITAS & DETAIL =================

    public function createStep2()
    {
        $this->ensureApplicationSession(); // Recover session jika hilang
        $applicationId = session('application_id');

        if (!$applicationId) {
            return redirect()->route('pengajuan.step1');
        }

        $application = CreditApplication::with('nasabahProfile')->find($applicationId);

        // Validasi urutan step
        if (!$application) {
            return redirect()->route('pengajuan.step1');
        }

        // Ambil data detail existing
        $applicationDetail = CreditApplicationDetail::firstOrNew([
            'credit_application_id' => $applicationId
        ]);

        $facilities = CreditFacility::where('aktif', 1)->get();

        return view('nasabah.pengajuan.step2', compact('facilities', 'application', 'applicationDetail'));
    }

    public function postStep2(Request $request)
    {
        $applicationId = session('application_id');
        $application = CreditApplication::findOrFail($applicationId);
        $profile = NasabahProfile::where('user_id', Auth::id())->first();

        $facility = CreditFacility::find($request->credit_facility_id);

        if (!$facility) {
            return back()->withErrors(['credit_facility_id' => 'Fasilitas kredit tidak valid.']);
        }

        $maxJangkaWaktu = $facility->max_jangka_waktu ?? 60;

        $rules = [
            'credit_facility_id' => 'required|exists:credit_facilities,id',
            'tujuan_pinjaman' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:10000000|max:5000000000',
            'jangka_waktu' => 'required|integer|min:1|max:' . $maxJangkaWaktu,
            'sumber_pendapatan' => 'required|string',
        ];

        // Conditional Rules Pasangan
        if ($profile && $profile->status_perkawinan === 'Menikah') {
            $rules = array_merge($rules, [
                'nama_pasangan' => 'required|string|max:255',
                'no_ktp_pasangan' => 'required|string|max:20',
                'alamat_tinggal_pasangan' => 'required|string|max:255',
                'alamat_ktp_pasangan' => 'required|string|max:255',
                'pekerjaan_pasangan' => 'required|string|max:100',
                'email_pasangan' => 'required|email|max:255',
                'no_hp_pasangan' => 'required|numeric|max_digits:15',
            ]);
        }

        // Rules Penjamin (Selalu wajib)
        $rules = array_merge($rules, [
            'nama_penjamin' => 'required|string|max:255',
            'no_ktp_penjamin' => 'required|string|max:20',
            'hubungan_penjamin' => 'required|string|max:50',
            'alamat_penjamin' => 'required|string|max:255',
            'email_penjamin' => 'required|email|max:255',
            'no_hp_penjamin' => 'required|numeric|max_digits:15',
        ]);

        $validated = $request->validate($rules);

        // Validasi NPWP > 50 Juta
        if ($validated['jumlah_pinjaman'] > 50000000 && empty($profile->no_npwp)) {
            return redirect()->route('pengajuan.step1')
                ->with('warning', 'NPWP wajib diisi untuk pengajuan lebih dari 50 juta.')
                ->withInput();
        }

        $requiresNpwp = ($validated['jumlah_pinjaman'] > 50000000) ? 1 : 0;

        // Update Application Main Data
        // Kita update status jadi 'draft_step2' HANYA jika status sekarang masih 'draft_step1'
        // Jika status sudah 'draft_step3', jangan dimundurkan (kecuali logikamu mengharuskan demikian)
        $newStatus = ($application->status == 'draft_step1') ? 'draft_step2' : $application->status;

        $application->update([
            'credit_facility_id' => $validated['credit_facility_id'],
            'tujuan_pinjaman' => $validated['tujuan_pinjaman'],
            'jumlah_pinjaman' => $validated['jumlah_pinjaman'],
            'jangka_waktu' => $validated['jangka_waktu'],
            'sumber_pendapatan' => $validated['sumber_pendapatan'],
            'status' => $newStatus,
            'requires_npwp' => $requiresNpwp,
        ]);

        // Update Detail (Pasangan & Penjamin)
        CreditApplicationDetail::updateOrCreate(
            ['credit_application_id' => $application->id],
            [
                'nama_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['nama_pasangan'] ?? null) : null,
                'no_ktp_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['no_ktp_pasangan'] ?? null) : null,
                'alamat_tinggal_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['alamat_tinggal_pasangan'] ?? null) : null,
                'alamat_ktp_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['alamat_ktp_pasangan'] ?? null) : null,
                'pekerjaan_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['pekerjaan_pasangan'] ?? null) : null,
                'email_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['email_pasangan'] ?? null) : null,
                'no_hp_pasangan' => $profile->status_perkawinan === 'Menikah' ? ($validated['no_hp_pasangan'] ?? null) : null,

                'nama_penjamin' => $validated['nama_penjamin'] ?? null,
                'no_ktp_penjamin' => $validated['no_ktp_penjamin'] ?? null,
                'hubungan_penjamin' => $validated['hubungan_penjamin'] ?? null,
                'alamat_penjamin' => $validated['alamat_penjamin'] ?? null,
                'email_penjamin' => $validated['email_penjamin'] ?? null,
                'no_hp_penjamin' => $validated['no_hp_penjamin'] ?? null,
            ]
        );

        return redirect()->route('pengajuan.step3')
            ->with('success', 'Data detail pinjaman disimpan.');
    }

    public function backToStep1()
    {
        return redirect()->route('pengajuan.step1');
    }

    // ================= STEP 3: DOKUMEN & AGUNAN =================

    public function createStep3()
    {
        $this->ensureApplicationSession(); // Recover session
        $applicationId = session('application_id');

        if (!$applicationId) return redirect()->route('pengajuan.step1');

        $application = CreditApplication::with('nasabahProfile')->find($applicationId);

        // Validasi Flow
        if (!$application) return redirect()->route('pengajuan.step1');

        // Cek apakah detail sudah diisi (mencegah loncat dari step 1 ke 3)
        $applicationDetail = CreditApplicationDetail::where('credit_application_id', $applicationId)->first();
        if (!$applicationDetail) {
            return redirect()->route('pengajuan.step2')
                ->with('warning', 'Silakan lengkapi data pengajuan kredit terlebih dahulu.');
        }

        // Ambil data agunan existing (Non-Destruktif)
        $collateral = CreditCollateral::firstOrNew([
            'credit_application_id' => $applicationId
        ]);

        // Ambil dokumen existing (Non-Destruktif)
        $dokumenMap = CreditDocument::where('credit_application_id', $applicationId)
            ->get()
            ->keyBy('jenis_dokumen');

        return view('nasabah.pengajuan.step3', compact('application', 'collateral', 'dokumenMap'));
    }

    public function postStep3(Request $request)
    {
        $applicationId = session('application_id');
        $application = CreditApplication::find($applicationId);
        $profile = NasabahProfile::where('user_id', Auth::id())->firstOrFail();

        if (!$application) return redirect()->route('pengajuan.step1');

        // --- VALIDASI ---
        $rules = [
            'jenis_agunan'      => 'required|in:SHM,SHGB',
            'nomor_sertifikat'  => 'required|string|max:255',
            'atas_nama'         => 'required|string|max:255',
            'foto_agunan_path'  => 'required|string',
            'sertifikat_path'   => 'required|string',
            'dokumen.ktp_pemohon_path'     => 'required|string',
            'dokumen.kartu_keluarga_path'  => 'required|string',
            'dokumen.rekening_koran_path'  => 'required|string',
        ];

        if ($profile->status_perkawinan === 'Menikah') {
            $rules['dokumen.ktp_pasangan_path'] = 'required|string';
            $rules['dokumen.surat_nikah_path']  = 'required|string';
        }
        if ($profile->status_rumah === 'Milik Sendiri') {
            $rules['dokumen.pbb_path']         = 'required|string';
            $rules['dokumen.rek_listrik_path'] = 'required|string';
        }
        if ($profile->status_rumah === 'Sewa') {
            $rules['dokumen.surat_sewa_path'] = 'required|string';
        }
        if ($application->requires_npwp == 1) {
            $rules['dokumen.npwp_path'] = 'required|string';
        }
        if ($application->sumber_pendapatan === 'Karyawan') {
            $rules['dokumen.surat_keterangan_bekerja_path'] = 'required|string';
            $rules['dokumen.slip_gaji_path']                = 'required|string';
        }
        if ($application->sumber_pendapatan === 'Wirausaha') {
            $rules['dokumen.sku_path']  = 'required|string';
            $rules['dokumen.nota_path'] = 'required|string';
        }
        if ($request->jenis_agunan === "SHGB") {
            $rules['masa_berlaku'] = ['required', 'date', function ($attribute, $value, $fail) {
                if ($value < now()->addYears(10)->toDateString()) {
                    $fail("Masa berlaku SHGB minimal 10 tahun dari sekarang.");
                }
            }];
        }

        $validated = $request->validate($rules);

        // --- LOGIKA SIMPAN FILE (NON-DESTRUKTIF) ---
        // Saya masukkan function helper di dalam sini agar tetap rapi sesuai style Anda
        $saveFile = function ($path, $folder) {
            if (!$path) return null;

            // Jika file baru (dari temp)
            if (str_starts_with($path, 'temp/')) {
                $basename = basename($path);
                $newPath = $folder . '/' . $basename;
                Storage::disk('public')->makeDirectory($folder);

                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('public')->put($newPath, Storage::disk('local')->get($path));
                    Storage::disk('local')->delete($path);
                    return $newPath;
                }
            }
            // Jika file lama, return path as is
            return $path;
        };

        // Simpan Dokumen
        foreach ($validated['dokumen'] ?? [] as $key => $tempPath) {
            $newPath = $saveFile($tempPath, "dokumen/{$application->id}");
            if ($newPath) {
                CreditDocument::updateOrCreate(
                    ['credit_application_id' => $application->id, 'jenis_dokumen' => $key],
                    ['nama_file' => $key . ".file", 'path' => $newPath, 'status_verifikasi' => 'Belum Diverifikasi']
                );
            }
        }

        // Simpan Agunan
        $fotoAgunan = $saveFile($validated['foto_agunan_path'], "agunan/{$application->id}");
        $sertifikat = $saveFile($validated['sertifikat_path'], "agunan/{$application->id}");

        CreditCollateral::updateOrCreate(
            ['credit_application_id' => $application->id],
            [
                'jenis_agunan'     => $validated['jenis_agunan'],
                'nomor_sertifikat' => $validated['nomor_sertifikat'],
                'atas_nama'        => $validated['atas_nama'],
                'masa_berlaku'     => $validated['masa_berlaku'] ?? null,
                'foto_agunan'      => $fotoAgunan,
                'file_sertifikat'  => $sertifikat,
            ]
        );

        // Update Status ke draft_step3
        $application->update(['status' => 'draft_step3']);

        return redirect()->route('pengajuan.review')
            ->with('success', 'Dokumen tersimpan. Silakan review data Anda.');
    }

    public function uploadTemp(Request $request)
    {
        $request->validate(['file' => 'nullable|max:1024', 'field' => 'required|string']);
        $folder = 'temp/' . (session('application_id') ?? Auth::id()); // Fallback ke Auth ID jika session null
        $path = $request->file('file')->store($folder);
        return response()->json(['status' => 'success', 'path' => $path]);
    }

    public function backToStep2()
    {
        return redirect()->route('pengajuan.step2');
    }

    // ================= REVIEW & SUBMIT =================

    public function createReview()
    {
        $this->ensureApplicationSession();
        $applicationId = session('application_id');

        if (!$applicationId) return redirect()->route('pengajuan.step1');

        $application = CreditApplication::with([
            'nasabahProfile',
            'creditFacility',
            'detail',
            'collateral',
            'documents'
        ])->find($applicationId);

        if (!$application) return redirect()->route('pengajuan.step1');

        // Pastikan status sudah draft_step3
        if ($application->status !== 'draft_step3') {
            return redirect()->route('pengajuan.step3');
        }

        $dokumenMap = $application->documents->keyBy('jenis_dokumen');

        return view('nasabah.pengajuan.review', compact('application', 'dokumenMap'));
    }

    public function postReview(Request $request)
    {
        $applicationId = session('application_id');
        $application = CreditApplication::find($applicationId);

        // Final Security Check
        if (!$application || $application->status !== 'draft_step3') {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Gagal mengirim pengajuan. Status tidak valid.');
        }

        // SUBMIT FINAL
        $application->update([
            'status'        => 'Menunggu Verifikasi',
            'submitted_at'  => now(),
        ]);

        // Hapus session agar user bersih saat kembali ke dashboard
        session()->forget('application_id');

        return redirect()->route('nasabah.dashboard')
            ->with('success', 'Pengajuan kredit berhasil dikirim! Kami akan segera memverifikasi data Anda.');
    }

    public function backToStep3()
    {
        return redirect()->route('pengajuan.step3');
    }
}
