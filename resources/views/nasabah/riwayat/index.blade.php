<x-layouts.nasabah :title="'Riwayat Pengajuan Kredit'">
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Status & Riwayat Kredit</h1>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        @if ($applications->isEmpty())
            <div class="p-10 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    {{-- Icon Empty --}}
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada pengajuan</h3>
                <p class="text-gray-500 mt-1 mb-6">Anda belum pernah mengajukan kredit sebelumnya.</p>
                <a href="{{ route('pengajuan.step1') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Ajukan Kredit Sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Fasilitas</th>
                            <th class="px-6 py-4">Jumlah Pinjaman</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($applications as $app)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    {{-- Cek submitted_at dulu, kalau null pakai created_at --}}
                                    {{ $app->submitted_at ? $app->submitted_at->format('d M Y') : $app->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-400">
                                        {{ $app->submitted_at ? $app->submitted_at->format('H:i') : $app->created_at->format('H:i') }}
                                        WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{-- KOREKSI: Pakai ->nama --}}
                                    {{ $app->creditFacility->nama ?? '-' }}
                                    <div class="text-xs text-gray-500">Tenor: {{ $app->jangka_waktu }} Bulan</div>
                                </td>
                                <td class="px-6 py-4 font-semibold">
                                    Rp {{ number_format($app->jumlah_pinjaman, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'draft_step1' => 'bg-gray-100 text-gray-600',
                                            'draft_step2' => 'bg-gray-100 text-gray-600',
                                            'draft_step3' => 'bg-yellow-100 text-yellow-700',
                                            'Menunggu Verifikasi' => 'bg-blue-100 text-blue-700',
                                            'Disetujui' => 'bg-green-100 text-green-700',
                                            'Ditolak' => 'bg-red-100 text-red-700',
                                        ];
                                        $class = $statusClasses[$app->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $class }}">
                                        {{ str_replace('_', ' ', ucfirst($app->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if (in_array($app->status, ['draft_step1', 'draft_step2', 'draft_step3']))
                                        <a href="{{ route('pengajuan.step1') }}"
                                            class="text-yellow-600 hover:text-yellow-800 font-medium hover:underline">
                                            Lanjutkan Draft →
                                        </a>
                                    @else
                                        {{-- KOREKSI: Route name sesuai grup 'riwayat.' + 'show' --}}
                                        <a href="{{ route('riwayat.show', $app->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                            Lihat Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</x-layouts.nasabah>
