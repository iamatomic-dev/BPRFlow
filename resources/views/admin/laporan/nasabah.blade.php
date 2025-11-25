@php
    if (Auth::user()->hasRole('Direktur')) {
        $layout = 'layouts.direktur';
    } elseif (Auth::user()->hasRole('Manager')) {
        $layout = 'layouts.manager';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
<x-dynamic-component :component="$layout" :title="'Laporan Data Nasabah'">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">Laporan Data Nasabah</h1>
    </x-slot>
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="text-sm font-bold text-gray-700">Dari Tanggal Register</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="border border-gray-200 rounded-lg text-sm p-2 w-full mt-1">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="border border-gray-200 rounded-lg text-sm p-2 w-full mt-1">
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">Filter</button>

            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold ml-auto flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
        </form>
    </div>

    <div class="mt-6 bg-white p-8 rounded-2xl shadow-sm print:shadow-none">
        {{-- KOP SURAT --}}
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-6 hidden print:block">
            <h1 class="text-2xl font-bold uppercase">BPR Parinama Simfoni Indonesia</h1>
            <p>Jl. Contoh No. 123, Kota Tangerang, Banten</p>
            <h2 class="text-xl font-bold mt-4 underline">LAPORAN DATA NASABAH</h2>
            <p class="text-sm">Periode: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Sekarang' }}</p>
        </div>

        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b-2 border-gray-300 print:bg-gray-200">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama Lengkap</th>
                    <th class="p-3 border">NIK / KTP</th>
                    <th class="p-3 border">No. HP</th>
                    <th class="p-3 border">Alamat Domisili</th>
                    <th class="p-3 border">Tgl Register</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nasabahs as $index => $n)
                    <tr class="border-b">
                        <td class="p-3 border text-center">{{ $index + 1 }}</td>
                        <td class="p-3 border font-bold">{{ $n->nasabahProfile->nama_lengkap ?? $n->name }}</td>
                        <td class="p-3 border">{{ $n->nasabahProfile->no_ktp ?? '-' }}</td>
                        <td class="p-3 border">{{ $n->nasabahProfile->no_hp ?? '-' }}</td>
                        <td class="p-3 border">{{ $n->nasabahProfile->alamat_tinggal ?? '-' }}</td>
                        <td class="p-3 border">{{ $n->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dynamic-component>
