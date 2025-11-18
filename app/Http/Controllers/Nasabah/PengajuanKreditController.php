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
    public function createStep1()
    {
        $userId = Auth::id();
        $profile = NasabahProfile::firstOrNew(['user_id' => $userId]);

        $draft = CreditApplication::where('user_id', $userId)
            ->whereIn('status', ['draft_step1', 'draft_step2', 'draft_step3'])
            ->latest()->first();

        if ($draft && $draft->status !== 'draft_step1') {
            session(['application_id' => $draft->id]);

            if ($draft->status === 'draft_step2') {
                return redirect()->route('pengajuan.step3');
            } elseif ($draft->status === 'draft_step3') {
                return redirect()->route('pengajuan.review');
            } else {
                return redirect()->route('pengajuan.step2');
            }
        }

        return view('nasabah.pengajuan.step1', compact('profile'));
    }

    public function postStep1(Request $request)
    {
        $userId = Auth::id();

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

        NasabahProfile::updateOrCreate(
            ['user_id' => $userId],
            $validated
        );

        $application = CreditApplication::updateOrCreate(
            ['user_id' => $userId],
            ['status' => 'draft_step1']
        );

        session(['application_id' => $application->id]);
        return redirect()->route('pengajuan.step2')->with('success', 'Data profil disimpan.');
    }

    public function createStep2()
    {
        $applicationId = session('application_id');
        $application = CreditApplication::with('nasabahProfile')->find($applicationId);

        if (!$application) {
            return redirect()->route('pengajuan.step1')
                ->with('warning', 'Silakan isi data pemohon terlebih dahulu.');
        }

        $facilities = CreditFacility::where('aktif', 1)->get();
        return view('nasabah.pengajuan.step2', compact('facilities', 'application'));
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

        $rules = array_merge($rules, [
            'nama_penjamin' => 'required|string|max:255',
            'no_ktp_penjamin' => 'required|string|max:20',
            'hubungan_penjamin' => 'required|string|max:50',
            'alamat_penjamin' => 'required|string|max:255',
            'email_penjamin' => 'required|email|max:255',
            'no_hp_penjamin' => 'required|numeric|max_digits:15',
        ]);

        $validated = $request->validate($rules);

        if ($validated['jumlah_pinjaman'] > 50000000 && empty($profile->no_npwp)) {
            return redirect()
                ->route('pengajuan.step1')
                ->with('warning', 'NPWP wajib diisi untuk pengajuan lebih dari 50 juta.')
                ->withInput();
        }

        if ($validated['jumlah_pinjaman'] > 50000000) {
            $requiresNpwp = 1;
        } else {
            $requiresNpwp = 0;
        }

        $application->update([
            'credit_facility_id' => $validated['credit_facility_id'],
            'tujuan_pinjaman' => $validated['tujuan_pinjaman'],
            'jumlah_pinjaman' => $validated['jumlah_pinjaman'],
            'jangka_waktu' => $validated['jangka_waktu'],
            'sumber_pendapatan' => $validated['sumber_pendapatan'],
            'status' => 'draft_step2',
            'requires_npwp' => $requiresNpwp,
        ]);

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
            ->with('success', 'Data fasilitas kredit disimpan.');
    }

    public function backToStep1()
    {
        return redirect()->route('pengajuan.step1');
    }

    public function createStep3()
    {
        $applicationId = session('application_id');
        $application = CreditApplication::with('nasabahProfile')->find($applicationId);
        $applicationDetail = CreditApplicationDetail::with('CreditApplication')->find($applicationId);

        if (!$application) {
            return redirect()
                ->route('pengajuan.step1')
                ->with('warning', 'Silakan isi data pemohon terlebih dahulu.');
        }

        if (!$applicationDetail) {
            return redirect()
                ->route('pengajuan.step2')
                ->with('warning', 'Silakan isi data pengajuan kredit terlebih dahulu.');
        }

        return view('nasabah.pengajuan.step3', compact('application'));
    }

    public function postStep3(Request $request)
    {
        $applicationId = session('application_id');
        $application = CreditApplication::find($applicationId);
        $profile = NasabahProfile::where('user_id', Auth::id())->firstOrFail();

        if (!$application) {
            return redirect()->route('pengajuan.step1')
                ->with('warning', 'Silakan isi data pemohon terlebih dahulu.');
        }

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
            $rules['masa_berlaku'] = [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $min = now()->addYears(10)->toDateString();
                    if ($value < $min) {
                        $fail("Masa berlaku SHGB minimal 10 tahun dari sekarang.");
                    }
                }
            ];
        }

        $validated = $request->validate($rules);

        function moveTempFile($tempPath, $folder)
        {
            if (!$tempPath) return null;

            $basename = basename($tempPath);
            $newPath = $folder . '/' . $basename;
            Storage::disk('public')->makeDirectory($folder);
            if (!Storage::disk('local')->exists($tempPath)) {
                return null;
            }
            $fileContent = Storage::disk('local')->get($tempPath);
            Storage::disk('public')->put($newPath, $fileContent);
            Storage::disk('local')->delete($tempPath);
            return $newPath;
        }

        foreach ($validated['dokumen'] ?? [] as $key => $tempPath) {
            $newPath = moveTempFile($tempPath, "dokumen/{$application->id}");

            CreditDocument::create([
                'credit_application_id' => $application->id,
                'nama_file'             => $key . ".file",
                'path'                  => $newPath,
                'jenis_dokumen'         => $key,
                'status_verifikasi'     => 'Belum Diverifikasi'
            ]);
        }

        $fotoAgunan   = moveTempFile($validated['foto_agunan_path'], "agunan/{$application->id}");
        $sertifikat   = moveTempFile($validated['sertifikat_path'], "agunan/{$application->id}");

        CreditCollateral::create([
            'credit_application_id' => $application->id,
            'jenis_agunan'          => $validated['jenis_agunan'],
            'nomor_sertifikat'      => $validated['nomor_sertifikat'],
            'atas_nama'             => $validated['atas_nama'],
            'masa_berlaku'          => $validated['masa_berlaku'] ?? null,
            'foto_agunan'           => $fotoAgunan,
            'file_sertifikat'       => $sertifikat,
        ]);

        $application->update([
            'status'        => 'Menunggu Verifikasi',
            'submitted_at'  => now(),
        ]);

        return redirect()->route('nasabah.dashboard')
            ->with('success', 'Pengajuan kredit berhasil dikirim!');
    }


    public function uploadTemp(Request $request)
    {
        $request->validate([
            'file' => 'nullable|max:1024',
            'field' => 'required|string'
        ]);

        $folder = 'temp/' . session('application_id');

        $path = $request->file('file')->store($folder);

        return response()->json([
            'status' => 'success',
            'path' => $path,
            'filename' => basename($path)
        ]);
    }

    public function backToStep2()
    {
        return redirect()->route('pengajuan.step2');
    }
}
