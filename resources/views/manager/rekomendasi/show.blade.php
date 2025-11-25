<x-layouts.manager :title="'Analisa Kredit'">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('manager.rekomendasi.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Analisa & Rekomendasi</h1>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-blue-50 rounded-2xl shadow-sm border border-blue-100 p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <i class="fa-solid fa-magnifying-glass-chart mr-2"></i> Hasil Pengecekan Admin (SLIK)
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Status Kolektibilitas</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $application->slik_status ?? 'Belum Ada Data' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">File SLIK</p>
                        @if ($application->slik_path)
                            <a href="{{ Storage::url($application->slik_path) }}" target="_blank"
                                class="text-blue-600 underline text-sm font-semibold hover:text-blue-800">
                                <i class="fa-solid fa-file-pdf mr-1"></i> Lihat PDF SLIK
                            </a>
                        @else
                            <span class="text-red-500 text-sm">File belum diupload</span>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 uppercase">Catatan Admin</p>
                        <p class="text-sm text-gray-700 italic bg-white p-3 rounded border border-blue-100 mt-1">
                            "{{ $application->slik_notes ?? 'Tidak ada catatan' }}"
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">
                    <i class="fa-solid fa-user-tie text-gray-600 mr-2"></i> Data Pengajuan & Pemohon
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <x-detail-item label="Fasilitas Kredit" :value="$application->creditFacility->nama" />
                    <x-detail-item label="Tujuan Penggunaan" :value="$application->tujuan_pinjaman" />
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Plafond Pengajuan</p>
                        <p class="text-lg font-bold text-green-600">Rp
                            {{ number_format($application->jumlah_pinjaman, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Jangka Waktu</p>
                        <p class="text-lg font-bold text-gray-800">{{ $application->jangka_waktu }} Bulan</p>
                    </div>

                    <div class="md:col-span-2 border-t border-gray-100 my-2"></div>

                    <x-detail-item label="Nama Lengkap" :value="$application->nasabahProfile->nama_lengkap" />
                    <x-detail-item label="NIK / KTP" :value="$application->nasabahProfile->no_ktp" />
                    <x-detail-item label="Pekerjaan/Sumber Dana" :value="$application->sumber_pendapatan" />
                    <x-detail-item label="No. HP / WA" :value="$application->nasabahProfile->no_hp" />
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Alamat Domisili</p>
                        <p class="text-sm font-medium text-gray-900">{{ $application->nasabahProfile->alamat_tinggal }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">
                    <i class="fa-solid fa-house-lock text-gray-600 mr-2"></i> Data Agunan
                </h3>
                @if ($application->collateral)
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/3">
                            <div class="rounded-lg overflow-hidden border border-gray-200 group relative">
                                <img src="{{ Storage::url($application->collateral->foto_agunan) }}"
                                    class="w-full h-32 object-cover">
                                <a href="{{ Storage::url($application->collateral->foto_agunan) }}" target="_blank"
                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-white text-xs font-bold">Lihat Foto</span>
                                </a>
                            </div>
                        </div>

                        <div class="w-full md:w-2/3 grid grid-cols-2 gap-4">
                            <x-detail-item label="Jenis Sertifikat" :value="$application->collateral->jenis_agunan" />
                            <x-detail-item label="Nomor Sertifikat" :value="$application->collateral->nomor_sertifikat" />
                            <x-detail-item label="Atas Nama" :value="$application->collateral->atas_nama" />
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">File Sertifikat</p>
                                <a href="{{ Storage::url($application->collateral->file_sertifikat) }}" target="_blank"
                                    class="text-blue-600 text-xs font-bold bg-blue-50 px-3 py-1 rounded border border-blue-200 hover:bg-blue-100">
                                    Buka PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm">Tidak ada data agunan.</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">
                    <i class="fa-solid fa-folder-open text-gray-600 mr-2"></i> Dokumen Pendukung
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($application->documents as $doc)
                        <a href="{{ Storage::url($doc->path) }}" target="_blank"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
                            <div class="p-2 bg-red-100 text-red-600 rounded-lg mr-3 group-hover:bg-red-200">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-gray-700 truncate capitalize">
                                    {{ str_replace('_', ' ', str_replace('_path', '', $doc->jenis_dokumen)) }}
                                </p>
                                <p class="text-xs text-blue-500 group-hover:underline">Klik untuk melihat</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">
                    <i class="fa-solid fa-gavel text-gray-600 mr-2"></i> Keputusan Manager
                </h3>

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

                <form action="{{ route('manager.rekomendasi.update', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rekomendasi</label>
                        <select name="recommendation_status" id="recStatus"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
                            <option value="">-- Pilih --</option>
                            <option value="Rekomendasi Disetujui">Rekomendasikan SETUJU</option>
                            <option value="Rekomendasi Ditolak">Rekomendasikan TOLAK</option>
                        </select>
                    </div>

                    <div id="approvalFields" style="display:none;">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plafond Direkomendasikan
                                (Rp)</label>
                            <input type="number" name="recommended_amount" value="{{ $application->jumlah_pinjaman }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 font-semibold text-green-700">
                            <p class="text-xs text-gray-500 mt-1">Bisa diubah jika ingin menurunkan plafond.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenor Direkomendasikan
                                (Bulan)</label>
                            <input type="number" name="recommended_tenor" value="{{ $application->jangka_waktu }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Analisa (5C)</label>
                        <textarea name="manager_note" rows="5" required
                            placeholder="Jelaskan alasan rekomendasi Anda. Analisa Character, Capacity, dll..."
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 text-sm"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-800 text-white py-3 rounded-xl font-bold hover:bg-gray-900 transition shadow-lg">
                        Kirim ke Direktur
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const statusSelect = document.getElementById('recStatus');
            const approvalFields = document.getElementById('approvalFields');

            statusSelect.addEventListener('change', function() {
                if (this.value === 'Rekomendasi Disetujui') {
                    approvalFields.style.display = 'block';
                } else {
                    approvalFields.style.display = 'none';
                }
            });
        </script>
    @endpush
</x-layouts.manager>
