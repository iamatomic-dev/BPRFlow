@php
    if (Auth::user()->hasRole('Direktur')) {
        $layout = 'layouts.direktur';
    } elseif (Auth::user()->hasRole('Manager')) {
        $layout = 'layouts.manager';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
<x-dynamic-component :component="$layout" :title="'Laporan Rekapitulasi'">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">Laporan Rekapitulasi</h1>
    </x-slot>
    <div class="bg-white p-6 rounded-2xl shadow-sm no-print flex gap-4 items-center">
        <form method="GET" class="flex gap-4 items-center">
            <label class="text-sm font-bold">Tahun:</label>
            <select name="year" onchange="this.form.submit()"
                class="appearance-none pl-4 pr-10 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-600 cursor-pointer w-full md:w-48">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
            class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold ml-auto flex items-center gap-2">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <div class="mt-6 p-8 bg-white rounded-2xl shadow-sm print:shadow-none">
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-6 hidden print:block">
            <h1 class="text-2xl font-bold uppercase">BPR Parinama Simfoni Indonesia</h1>
            <h2 class="text-xl font-bold mt-2 underline">LAPORAN REKAPITULASI PORTOFOLIO KREDIT</h2>
            <p>Tahun Buku: {{ $year }}</p>
        </div>

        {{-- SECTION 1: KARTU RINGKASAN --}}
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="border p-4 rounded-xl bg-blue-50">
                <h3 class="text-sm font-bold text-blue-800 uppercase">Total Penyaluran (Disbursed)</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}
                </p>
            </div>
            <div class="border p-4 rounded-xl bg-red-50">
                <h3 class="text-sm font-bold text-red-800 uppercase">Outstanding (Sisa Pokok)</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($outstanding, 0, ',', '.') }}</p>
            </div>
            <div class="border p-4 rounded-xl bg-green-50">
                <h3 class="text-sm font-bold text-green-800 uppercase">Pendapatan Bunga</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($profitBunga, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- SECTION 2: TABEL PER FASILITAS --}}
        <h3 class="font-bold text-lg mb-4">Rincian Per Fasilitas Kredit (Tahun {{ $year }})</h3>
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b-2 border-gray-300">
                    <th class="p-3 border">Jenis Fasilitas</th>
                    <th class="p-3 border text-center">Jumlah Nasabah</th>
                    <th class="p-3 border text-right">Total Plafond Disetujui</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($byFacility as $fac)
                    <tr class="border-b">
                        <td class="p-3 border">{{ $fac->nama }}</td>
                        <td class="p-3 border text-center">{{ $fac->total_nasabah }} Orang</td>
                        <td class="p-3 border text-right font-bold">Rp
                            {{ number_format($fac->total_plafond, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot name="style">
        <style>
            @media print {
                .no-print {
                    display: none;
                }

                body {
                    background: white;
                }

                #sidebar,
                header {
                    display: none;
                }

                main {
                    margin: 0;
                    padding: 0;
                }
            }
        </style>
    </x-slot>
</x-dynamic-component>
