<x-layouts.direktur :title="'Keputusan Kredit'">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('direktur.persetujuan.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Keputusan Kredit</h1>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI: SUMMARY & REKOMENDASI MANAGER (Penting) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. REKOMENDASI MANAGER --}}
            <div class="bg-yellow-50 rounded-2xl shadow-sm border border-yellow-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-gavel text-8xl text-yellow-600"></i>
                </div>

                <h3 class="text-lg font-bold text-yellow-800 mb-4 border-b border-yellow-200 pb-2">
                    Analisa & Rekomendasi Manager
                </h3>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Keputusan Manager</p>
                        <p
                            class="text-lg font-bold {{ $application->recommendation_status == 'Rekomendasi Disetujui' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $application->recommendation_status }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Tgl Analisa</p>
                        <p class="text-gray-800 font-medium">
                            {{ $application->managed_at ? $application->managed_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>

                @if ($application->recommendation_status == 'Rekomendasi Disetujui')
                    <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded-xl border border-yellow-100 mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Plafond Direkomendasikan</p>
                            <p class="text-xl font-bold text-gray-800">Rp
                                {{ number_format($application->recommended_amount) }}</p>
                            @if ($application->recommended_amount != $application->jumlah_pinjaman)
                                <span class="text-xs text-red-500">(Diubah dari Rp
                                    {{ number_format($application->jumlah_pinjaman) }})</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tenor Direkomendasikan</p>
                            <p class="text-xl font-bold text-gray-800">{{ $application->recommended_tenor }} Bulan</p>
                        </div>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Catatan Manager (5C Analysis)</p>
                    <p class="text-sm text-gray-800 italic bg-white p-4 rounded-lg border border-yellow-100">
                        "{{ $application->manager_note }}"
                    </p>
                </div>
            </div>

            {{-- 2. DATA SLIK (Summary) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Data SLIK (BI Checking)</h3>
                <div class="flex items-center justify-between bg-blue-50 p-4 rounded-lg">
                    <div>
                        <span class="text-xs text-gray-500 uppercase">Status Kolektibilitas</span>
                        <p class="font-bold text-blue-800 text-lg">{{ $application->slik_status }}</p>
                    </div>
                    <div>
                        <a href="{{ Storage::url($application->slik_path) }}" target="_blank"
                            class="px-4 py-2 bg-white text-blue-600 text-sm font-bold rounded shadow hover:bg-blue-50">
                            Lihat File PDF
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-xs text-gray-500">Catatan Admin:</p>
                    <p class="text-sm text-gray-700">{{ $application->slik_notes ?? '-' }}</p>
                </div>
            </div>

            {{-- 3. DATA NASABAH --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <x-detail-item label="Nama Nasabah" :value="$application->nasabahProfile->nama_lengkap" />
                <x-detail-item label="Sumber Pendapatan" :value="$application->sumber_pendapatan" />
                <x-detail-item label="Tujuan Pinjaman" :value="$application->tujuan_pinjaman" />
            </div>
        </div>

        {{-- KOLOM KANAN: FORM APPROVAL --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 sticky top-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-signature text-2xl text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Keputusan Akhir</h3>
                    <p class="text-sm text-gray-500">Sebagai Direktur, keputusan Anda bersifat final.</p>
                </div>

                <form action="{{ route('direktur.persetujuan.update', $application->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin dengan keputusan ini?');">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 mb-6">
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-green-50 transition has-[:checked]:bg-green-50 has-[:checked]:border-green-500 has-[:checked]:ring-1 has-[:checked]:ring-green-500">
                            <input type="radio" name="decision" value="approve" id="radioApprove" class="w-5 h-5 text-green-600 focus:ring-green-500" onclick="toggleInputs(true)">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">SETUJUI PENGAJUAN</span>
                                <span class="block text-xs text-gray-500">Cairkan dana (Bisa edit nominal).</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-red-50 transition has-[:checked]:bg-red-50 has-[:checked]:border-red-500 has-[:checked]:ring-1 has-[:checked]:ring-red-500">
                            <input type="radio" name="decision" value="reject" id="radioReject" class="w-5 h-5 text-red-600 focus:ring-red-500" onclick="toggleInputs(false)">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">TOLAK PENGAJUAN</span>
                                <span class="block text-xs text-gray-500">Batalkan proses pengajuan ini.</span>
                            </div>
                        </label>
                    </div>

                    {{-- INPUT NOMINAL & TENOR (Hanya muncul jika Setuju) --}}
                    <div id="approvalInputs" class="hidden bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 border-b pb-1">Keputusan Final
                            Direktur</h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plafond Disetujui (Rp)</label>
                            {{-- Logic Value: Prioritas Manager -> Prioritas Nasabah --}}
                            <input type="number" name="final_amount"
                                value="{{ $application->recommended_amount ?? $application->jumlah_pinjaman }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 font-bold text-green-700">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenor Disetujui (Bulan)</label>
                            <input type="number" name="final_tenor"
                                value="{{ $application->recommended_tenor ?? $application->jangka_waktu }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Keputusan (Opsional)</label>
                        <textarea name="note" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-gray-500 text-sm"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold hover:bg-black transition shadow-lg">
                        Simpan Keputusan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function toggleInputs(isApprove) {
                const inputDiv = document.getElementById('approvalInputs');
                if (isApprove) {
                    inputDiv.classList.remove('hidden');
                } else {
                    inputDiv.classList.add('hidden');
                }
            }
        </script>
    @endpush
</x-layouts.direktur>
