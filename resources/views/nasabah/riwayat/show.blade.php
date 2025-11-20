<x-layouts.nasabah :title="'Detail Pengajuan'">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-bold">Detail Pengajuan #{{ $application->id }}</h1>
            <a href="{{ route('riwayat.index') }}" class="text-sm text-gray-600 hover:text-gray-900 ps-3">← Kembali</a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Info Utama --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Kredit</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Fasilitas</dt>
                        <dd class="font-medium">{{ $application->creditFacility->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tujuan</dt>
                        <dd class="font-medium">{{ $application->tujuan_pinjaman }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Jumlah Pinjaman</dt>
                        <dd class="font-medium text-lg text-green-600">Rp
                            {{ number_format($application->jumlah_pinjaman, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Jangka Waktu</dt>
                        <dd class="font-medium">{{ $application->jangka_waktu }} Bulan</dd>
                    </div>
                </dl>
            </div>

            {{-- Tampilkan Agunan --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Agunan</h3>
                @if ($application->collateral)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Jenis Sertifikat</dt>
                            <dd class="font-medium">{{ $application->collateral->jenis_agunan }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">No. Sertifikat</dt>
                            <dd class="font-medium">{{ $application->collateral->nomor_sertifikat }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-gray-500 text-sm">Data agunan belum lengkap.</p>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Status --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Status Terkini</h3>

                <div class="text-center py-4">
                    @if ($application->status == 'Disetujui')
                        <div
                            class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-green-700">Disetujui</h4>
                        <p class="text-sm text-gray-500 mt-1">Selamat! Pengajuan Anda diterima.</p>
                    @elseif($application->status == 'Ditolak')
                        <div
                            class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-red-700">Ditolak</h4>
                        <p class="text-sm text-gray-500 mt-1">Mohon maaf, pengajuan belum memenuhi syarat.</p>
                    @elseif($application->status == 'Menunggu Verifikasi')
                        <div
                            class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-blue-700">Dalam Proses</h4>
                        <p class="text-sm text-gray-500 mt-1">Tim kami sedang memverifikasi data Anda.</p>
                    @else
                        <div
                            class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-yellow-700">Draft</h4>
                        <p class="text-sm text-gray-500 mt-1">Pengajuan belum diselesaikan.</p>
                        <a href="{{ route('pengajuan.step1') }}"
                            class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600">Lanjutkan
                            Pengisian</a>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Diajukan pada:</span>
                        <span
                            class="font-medium">{{ $application->submitted_at ? \Carbon\Carbon::parse($application->submitted_at)->format('d M Y') : '-' }}</span>
                    </div>
                    @if ($application->approved_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Disetujui pada:</span>
                            <span
                                class="font-medium">{{ \Carbon\Carbon::parse($application->approved_at)->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.nasabah>
