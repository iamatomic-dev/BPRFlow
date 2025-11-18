<x-layouts.nasabah :title="'Pengajuan Kredit'">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Form Pengajuan Kredit</h1>
            <span class="text-sm text-gray-500 ms-4 pt-1">Step 1 dari 3</span>
        </div>
    </x-slot>

    @if (session('warning'))
        <x-alert type="warning">
            <strong>Peringatan:</strong> {{ session('warning') }}
        </x-alert>
    @endif

    @if (session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    <section id="pengajuan" class="bg-white rounded-2xl shadow-md p-8 mx-auto">
        <h2 class="text-xl font-semibold mb-6 text-gray-800 border-b pb-2">Data Pemohon</h2>

        <form method="POST" action="{{ route('pengajuan.step1.post') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-text-input name="nama_lengkap" label="Nama Lengkap"
                    value="{{ old('nama_lengkap', $profile->nama_lengkap ?? Auth::user()->name) }}" required readonly />
                <x-text-input name="email" label="Email" type="email"
                    value="{{ old('email', $profile->email ?? Auth::user()->email) }}" required readonly />
                <x-select-input name="jenis_kelamin" label="Jenis Kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" :value="$profile->jenis_kelamin"
                    required />
                <x-text-input name="no_ktp" label="Nomor KTP" value="{{ old('no_ktp', $profile->no_ktp) }}" required />
                <x-text-input name="no_hp" label="Nomor HP" value="{{ old('no_hp', $profile->no_hp) }}" required />
                <x-select-input name="pendidikan_terakhir" label="Pendidikan Terakhir" :options="[
                    'SD' => 'SD',
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'D3' => 'D3',
                    'S1' => 'S1',
                    'S2' => 'S2',
                    'S4' => 'S4',
                ]"
                    :value="$profile->pendidikan_terakhir" required />
                <x-textarea-input name="alamat_tinggal" label="Alamat Tempat Tinggal" :value="$profile->alamat_tinggal" required />
                <x-textarea-input name="alamat_ktp" label="Alamat Sesuai KTP" :value="$profile->alamat_ktp" required />
                <x-text-input name="nama_ibu_kandung" label="Nama Ibu Kandung"
                    value="{{ old('nama_ibu_kandung', $profile->nama_ibu_kandung) }}" required />
                <x-text-input name="agama" label="Agama" value="{{ old('agama', $profile->agama) }}" required />
                <x-select-input name="status_perkawinan" label="Status Perkawinan" :options="['Belum Menikah' => 'Belum Menikah', 'Menikah' => 'Menikah', 'Cerai' => 'Cerai']" :value="$profile->status_perkawinan"
                    required />
                <x-select-input name="status_rumah" label="Status Rumah" :options="['Milik Sendiri' => 'Milik Sendiri', 'Sewa' => 'Sewa']" :value="$profile->status_rumah"
                    required />
                <x-text-input name="no_npwp" label="No. NPWP (wajib untuk pinjaman lebih dari 50 juta)"
                    value="{{ old('no_npwp', $profile->no_npwp) }}" />
            </div>

            <div class="pt-8 mt-8 border-t text-right">
                <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-6 py-3 ms-2 rounded-xl hover:bg-blue-700 transition">
                    Simpan & Lanjut →
                </button>
            </div>
        </form>
    </section>

</x-layouts.nasabah>
