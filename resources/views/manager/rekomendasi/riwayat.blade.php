<x-layouts.manager :title="'Riwayat Rekomendasi'">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">Riwayat Rekomendasi Kredit</h1>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER & PENCARIAN --}}
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Sudah Diproses</h2>

            <form method="GET" action="{{ route('manager.rekomendasi.riwayat') }}" class="flex w-full md:w-auto gap-2">
                <div class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari No Tiket / Nama..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- TABEL RIWAYAT --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">No. Tiket</th>
                        <th class="px-6 py-4">Nasabah</th>
                        <th class="px-6 py-4">Tgl Analisa</th>
                        <th class="px-6 py-4">Keputusan Anda</th>
                        <th class="px-6 py-4">Status Akhir</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-blue-600 font-bold">
                                {{ $app->no_pengajuan }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $app->nasabahProfile->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $app->creditFacility->nama }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $app->managed_at ? $app->managed_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($app->recommendation_status == 'Rekomendasi Disetujui')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                    <div class="text-xs text-green-700 mt-1 font-bold">
                                        Rp {{ number_format($app->recommended_amount) }}
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{-- Status Akhir (Posisi di Direktur) --}}
                                @php
                                    $statusClass = match ($app->status) {
                                        'Disetujui' => 'text-green-600 font-bold',
                                        'Ditolak' => 'text-red-600 font-bold',
                                        default => 'text-orange-500 font-medium',
                                    };
                                @endphp
                                <span class="{{ $statusClass }} text-xs">
                                    {{ $app->status == 'Menunggu Verifikasi' ? 'Menunggu Direktur' : $app->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('manager.rekomendasi.show', $app->id) }}"
                                    class="text-gray-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-lg"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-history text-4xl mb-2 text-gray-300"></i>
                                    <p>Belum ada riwayat rekomendasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $applications->links() }}
        </div>
    </div>
</x-layouts.manager>
