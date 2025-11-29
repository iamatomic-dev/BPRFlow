<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Models\NasabahProfile;
use App\Models\User;

class NasabahProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = NasabahProfile::firstOrNew(['user_id' => $user->id]);

        return view('nasabah.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = NasabahProfile::where('user_id', $user->id)->first();

        $rules = [
            'email'               => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_hp'               => ['required', 'numeric', 'digits_between:10,15'],
            'alamat_tinggal'      => ['required', 'string'],
            'alamat_ktp'          => ['required', 'string'],
            'status_rumah'        => ['required', 'string'],
            'pendidikan_terakhir' => ['required', 'string'],
            'agama'               => ['required', 'string'],
            'status_perkawinan'   => ['required', 'string'],
            'no_npwp'             => ['nullable', 'numeric', 'digits_between:15,16'],
        ];

        if (!$profile || empty($profile->no_ktp) || empty($profile->nama_lengkap) || empty($profile->nama_ibu_kandung) || empty($profile->jenis_kelamin)) {
            return back()->with('error', 'Harap lakukan pengajuan kredit terlebih dahulu, sebelum bisa mengubah profil');
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Update User (Hanya Email, Nama di-skip jika sudah ada profile agar konsisten)
            $user->update(['email' => $validated['email']]);

            // Jika profile baru, nama user diupdate sesuai input
            if (!$profile) {
                $user->update(['name' => $validated['nama_lengkap']]);
            }

            // Siapkan data update profile
            $profileData = $validated;

            // Hapus data sensitif dari array update jika profile sudah ada
            // Ini mencegah user 'nakal' yang inspect element & enable input
            if ($profile && !empty($profile->no_ktp)) {
                unset($profileData['no_ktp']);
                unset($profileData['nama_lengkap']);
                unset($profileData['nama_ibu_kandung']);
                unset($profileData['jenis_kelamin']);
            }

            NasabahProfile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            DB::commit();
            return back()->with('success', 'Biodata berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Method Khusus Ganti Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
