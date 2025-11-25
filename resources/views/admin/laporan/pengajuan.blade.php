@php
    if (Auth::user()->hasRole('Direktur')) {
        $layout = 'layouts.direktur';
    } elseif (Auth::user()->hasRole('Manager')) {
        $layout = 'layouts.manager';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
<x-dynamic-component :component="$layout" :title="'Laporan Status Pengajuan'">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">Laporan Status Pengajuan</h1>
    </x-slot>
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="text-sm font-bold text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border border-gray-200 rounded-lg text-sm p-2 w-full mt-1">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border border-gray-200 rounded-lg text-sm p-2 w-full mt-1">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700">Status</label>
                <select name="status" class="border border-gray-200 rounded-lg text-sm p-2 w-full mt-1">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ $status == 'Menunggu Verifikasi' ? 'selected' : '' }}>
                        Menunggu Verifikasi</option>
                    <option value="Disetujui" {{ $status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">Filter</button>
            
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold ml-auto flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
        </form>
    </div>

    <div class="mt-6 bg-white p-8 rounded-2xl shadow-sm print:shadow-none">
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-6 hidden print:block">
            <h1 class="text-2xl font-bold uppercase">BPR Parinama Simfoni Indonesia</h1>
            <h2 class="text-xl font-bold mt-2 underline">LAPORAN STATUS PENGAJUAN KREDIT</h2>
        </div>

        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b-2 border-gray-300 print:bg-gray-200">
                    <th class="p-3 border">Tgl Masuk</th>
                    <th class="p-3 border">No. Pengajuan</th>
                    <th class="p-3 border">Nama Nasabah</th>
                    <th class="p-3 border">Fasilitas</th>
                    <th class="p-3 border text-right">Plafond</th>
                    <th class="p-3 border text-center">Tenor</th>
                    <th class="p-3 border text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $app)
                    <tr class="border-b">
                        <td class="p-3 border">{{ $app->submitted_at ? $app->submitted_at->format('d/m/Y') : '-' }}</td>
                        <td class="p-3 border font-mono text-xs">{{ $app->no_pengajuan }}</td>
                        <td class="p-3 border font-bold">{{ $app->nasabahProfile->nama_lengkap }}</td>
                        <td class="p-3 border">{{ $app->creditFacility->nama }}</td>
                        <td class="p-3 border text-right">Rp {{ number_format($app->jumlah_pinjaman, 0, ',', '.') }}
                        </td>
                        <td class="p-3 border text-center">{{ $app->jangka_waktu }} Bln</td>
                        <td class="p-3 border text-center">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold border {{ $app->status == 'Disetujui' ? 'bg-green-100 text-green-700 border-green-200' : ($app->status == 'Ditolak' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-blue-100 text-blue-700 border-blue-200') }}">
                                {{ $app->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dynamic-component>
