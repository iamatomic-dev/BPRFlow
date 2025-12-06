<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi Kredit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            color: #555;
        }

        .sub-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .sub-header h2 {
            font-size: 14px;
            text-decoration: underline;
            margin: 0 0 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right !important;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        .font-bold {
            font-weight: bold !important;
        }
    </style>
</head>

<body>

    {{-- 1. KOP SURAT --}}
    <div class="header">
        <h1>BPR XYZ</h1>


    </div>

    {{-- 2. JUDUL --}}
    <div class="report-title">
        <h2>LAPORAN REKAPITULASI</h2>
        <p>Tahun Buku: {{ $year }}</p>
    </div>

    {{-- 3. RINGKASAN (Pengganti Grid System) --}}
    <table>
        <thead>
            {{-- Baris Header 1 --}}
            <tr>
                <th rowspan="2" style="width: 15%">JENIS PINJAMAN</th>
                <th rowspan="2" style="width: 12%">JUMLAH</th>
                <th colspan="2">ANGSURAN</th>
                <th rowspan="2" style="width: 12%">TOTAL ANGSURAN</th>
                <th colspan="3">TUNGGAKAN</th>
                <th rowspan="2" style="width: 12%">TOTAL TUNGGAKAN</th>
            </tr>
            {{-- Baris Header 2 --}}
            <tr>
                <th>POKOK</th>
                <th>BUNGA</th>
                <th>POKOK</th>
                <th>BUNGA</th>
                <th>DENDA</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Inisialisasi Grand Total
                $t_jumlah = 0;
                $t_ang_pokok = 0;
                $t_ang_bunga = 0;
                $t_ang_total = 0;
                $t_tung_pokok = 0;
                $t_tung_bunga = 0;
                $t_tung_denda = 0;
                $t_tung_total = 0;
            @endphp

            @foreach ($data as $row)
                @php
                    // Akumulasi Grand Total
                    $t_jumlah += $row->rekap->jumlah;
                    $t_ang_pokok += $row->rekap->angsuran_pokok;
                    $t_ang_bunga += $row->rekap->angsuran_bunga;
                    $t_ang_total += $row->rekap->total_angsuran;
                    $t_tung_pokok += $row->rekap->tunggakan_pokok;
                    $t_tung_bunga += $row->rekap->tunggakan_bunga;
                    $t_tung_denda += $row->rekap->tunggakan_denda;
                    $t_tung_total += $row->rekap->total_tunggakan;
                @endphp
                <tr>
                    <td class="col-left">{{ strtoupper($row->nama) }}</td>
                    <td class="text-right">{{ number_format($row->rekap->jumlah, 0, ',', '.') }}</td>

                    {{-- Angsuran --}}
                    <td class="text-right">{{ number_format($row->rekap->angsuran_pokok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->rekap->angsuran_bunga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->rekap->total_angsuran, 0, ',', '.') }}</td>

                    {{-- Tunggakan --}}
                    <td class="text-right">{{ number_format($row->rekap->tunggakan_pokok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->rekap->tunggakan_bunga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->rekap->tunggakan_denda, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->rekap->total_tunggakan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-yellow">
                <td class="col-left font-bold">TOTAL</td>
                <td class="text-right font-bold">{{ number_format($t_jumlah, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_ang_pokok, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_ang_bunga, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_ang_total, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_tung_pokok, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_tung_bunga, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_tung_denda, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($t_tung_total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER TANDA TANGAN --}}
    <div class="footer">
        {{-- Tanggal Cetak --}}
        <p>Bandung, {{ date('d F Y') }}</p>

        <p>Dicetak oleh,</p>

        {{-- Space untuk Tanda Tangan --}}
        <br><br><br>

        {{-- Nama User Login --}}
        <p style="text-decoration: underline; font-weight: bold;">
            {{ Auth::user()->name }}
        </p>

        {{-- Role User (Opsional: Biar tau jabatannya) --}}
        <p>
            ( {{ Auth::user()->getRoleNames()->first() ?? 'Admin Operasional' }} )
        </p>
    </div>

</body>

</html>
