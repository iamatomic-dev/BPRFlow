<x-layouts.nasabah :title="'Pengajuan Kredit'">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Form Pengajuan Kredit</h1>
            <span class="text-sm text-gray-500 ms-4 pt-1">Step 3 dari 3</span>
        </div>
    </x-slot>

    <section id="dokumen-agunan" class="bg-white rounded-2xl shadow-md p-8 mx-auto" x-data="{
        statusPerkawinan: '{{ $application->nasabahProfile->status_perkawinan ?? '' }}',
        statusRumah: '{{ $application->nasabahProfile->status_rumah ?? '' }}',
        sumberPendapatan: '{{ $application->sumber_pendapatan ?? '' }}',
        jumlahPinjaman: {{ $application->jumlah_pinjaman ?? 0 }},
        jenisAgunan: '{{ old('jenis_agunan') }}'
    }">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <h3 class="text-red-700 font-semibold mb-2">Ada kesalahan dalam pengisian:</h3>
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('pengajuan.step3.post') }}" enctype="multipart/form-data">
            @csrf

            {{-- ========== BAGIAN DOKUMEN IDENTITAS ========== --}}
            <div class="mb-6 pb-4">
                <h3 class="text-lg font-semibold mb-6 pb-2 text-gray-800 border-b">Dokumen Identitas & Tempat Tinggal
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                    <x-input-file label="KTP Pemohon" name="dokumen[ktp_pemohon_path]" accept="image/*,.pdf"
                        required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    <x-input-file label="Kartu Keluarga" name="dokumen[kartu_keluarga_path]" accept="image/*,.pdf"
                        required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                </div>

                {{-- KTP Pasangan (jika menikah) --}}
                <div x-show="statusPerkawinan === 'Menikah'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <x-input-file label="KTP Pasangan" name="dokumen[ktp_pasangan_path]" accept="image/*,.pdf"
                            required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />

                        <x-input-file label="Surat Nikah" name="dokumen[surat_nikah_path]" accept="image/*,.pdf"
                            required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    </div>
                </div>

                {{-- Status Rumah --}}
                <div x-show="statusRumah === 'Milik Sendiri'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <x-input-file label="PBB (Pajak Bumi & Bangunan)" name="dokumen[pbb_path]" accept="image/*,.pdf"
                            required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                        <x-input-file label="Rek Listrik / Telp / Pam" name="dokumen[rek_listrik_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />

                    </div>
                </div>

                <div x-show="statusRumah === 'Sewa'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <x-input-file label="Surat Perjanjian Sewa Ruwah" name="dokumen[surat_sewa_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    </div>
                </div>

                {{-- NPWP (jika pinjaman > 50 juta) --}}
                <div x-show="jumlahPinjaman > 50000000">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input-file label="NPWP" name="dokumen[npwp_path]" accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    </div>
                </div>
            </div>

            {{-- ========== BAGIAN DOKUMEN KEUANGAN ========== --}}
            <div class="mb-6 pb-4">
                <h3 class="text-lg font-semibold mb-6 pb-2 text-gray-800 border-b">Dokumen Pendukung Keuangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                    <x-input-file label="Rekening Koran (3 Bulan Terakhir)" name="dokumen[rekening_koran_path]"
                        accept="image/*,.pdf" required="true" note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                </div>

                {{-- Jika Karyawan --}}
                <div x-show="sumberPendapatan === 'Karyawan'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input-file label="Surat Keterangan Bekerja" name="dokumen[surat_keterangan_bekerja_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                        <x-input-file label="Slip Gaji (3 Bulan Terakhir)" name="dokumen[slip_gaji_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    </div>
                </div>

                {{-- Jika Wirausaha --}}
                <div x-show="sumberPendapatan === 'Wirausaha'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input-file label="Surat Keterangan Usaha (SKU)" name="dokumen[sku_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                        <x-input-file label="Nota/Bon/Laporan Keuangan Usaha" name="dokumen[nota_path]"
                            accept="image/*,.pdf" required="true"
                            note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    </div>
                </div>
            </div>

            {{-- ========== BAGIAN AGUNAN ========== --}}
            <div class="mb-6 pb-4">
                <h3 class="text-lg font-semibold mb-6 pb-2 text-gray-800 border-b">Agunan / Jaminan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Sertifikat Agunan <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_agunan" x-model="jenisAgunan"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jenis Sertifikat Agunan --</option>
                            <option value="SHM">SHM</option>
                            <option value="SHGB">SHGB</option>
                        </select>
                        @error('jenis_agunan')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <x-text-input name="nomor_sertifikat" label="Nomor Sertifikat" :value="old('nomor_sertifikat')" required />
                    <x-text-input name="atas_nama" label="Atas Nama" :value="old('atas_nama')" required />
                    <!-- Masa Berlaku (Hanya jika SHGB) -->
                    <div x-show="jenisAgunan === 'SHGB'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Masa Berlaku <span class="text-red-500">*</span>
                        </label>

                        <input type="date" name="masa_berlaku" x-bind:disabled="jenisAgunan !== 'SHGB'"
                            x-bind:required="jenisAgunan === 'SHGB'"
                            class="w-full rounded-lg shadow-sm border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('masa_berlaku') }}">


                        @error('masa_berlaku')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input-file label="Foto Agunan" name="foto_agunan_path" accept="image/*,.pdf" required="true"
                        note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                    <x-input-file label="Sertifikat" name="sertifikat_path" accept="image/*,.pdf" required="true"
                        note="Format yang diizinkan: PDF, JPG, PNG (maks. 1MB)" />
                </div>
            </div>

            {{-- ========== AKSI NAVIGASI ========== --}}
            <div class="pt-8 mt-8 border-t text-right">
                <a href="{{ route('pengajuan.step2') }}"
                    class="bg-gray-200 text-gray-800 px-6 py-3 rounded-xl hover:bg-gray-300 transition">
                    ← Kembali
                </a>
                <button type="submit"
                    class="bg-green-600 text-white font-semibold px-6 py-3 ms-2 rounded-xl hover:bg-green-700 transition">
                    Selesai
                </button>
            </div>
        </form>
    </section>

</x-layouts.nasabah>
