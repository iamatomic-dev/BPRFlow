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

        /* Header Style */
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

        /* Sub-header (Judul Laporan) */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h2 {
            font-size: 14px;
            text-decoration: underline;
            margin: 0 0 5px 0;
        }

        /* Styling Tabel Ringkasan (Pengganti Grid Card) */
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
            border-spacing: 10px;
            /* Memberi jarak antar sel */
            border-collapse: separate;
        }

        .summary-cell {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            background-color: #f9fafb;
            border-radius: 5px;
            width: 33%;
            /* Bagi rata 3 kolom */
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        /* Styling Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #eee;
            font-weight: bold;
        }

        /* Helper Classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- 1. KOP SURAT --}}
    <div class="header">
        <h1>BPR Parinama Simfoni Indonesia</h1>
        <p>Jalan Terusan Buah Batu No.25, Bandung 40266, Jawa Barat</p>
        <p>Telp: (62) 812-5000-5066 | Web: bprparinama.co.id</p>
    </div>

    {{-- 2. JUDUL --}}
    <div class="report-title">
        <h2>LAPORAN REKAPITULASI PORTOFOLIO KREDIT</h2>
        <p>Tahun Buku: {{ $year }}</p>
    </div>

    {{-- 3. RINGKASAN (Pengganti Grid System) --}}
    <table class="summary-table">
        <tr>
            {{-- Total Penyaluran --}}
            <td class="summary-cell">
                <span class="summary-label">Total Penyaluran (Disbursed)</span>
                <div class="summary-value">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
            </td>

            {{-- Outstanding --}}
            <td class="summary-cell">
                <span class="summary-label">Outstanding (Sisa Pokok)</span>
                <div class="summary-value">Rp {{ number_format($outstanding, 0, ',', '.') }}</div>
            </td>

            {{-- Pendapatan Bunga --}}
            <td class="summary-cell">
                <span class="summary-label">Pendapatan Bunga</span>
                <div class="summary-value">Rp {{ number_format($profitBunga, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- 4. RINCIAN TABEL --}}
    <h3 style="font-size: 12px; margin-bottom: 10px;">Rincian Per Fasilitas Kredit</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%">Jenis Fasilitas</th>
                <th style="width: 25%; text-align: center;">Jumlah Nasabah</th>
                <th style="width: 35%; text-align: right;">Total Plafond Disetujui</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($byFacility as $fac)
                @php $grandTotal += $fac->total_plafond; @endphp
                <tr>
                    <td>{{ $fac->nama }}</td>
                    <td class="text-center">{{ $fac->total_nasabah }} Orang</td>
                    <td class="text-right font-bold">Rp {{ number_format($fac->total_plafond, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td class="text-right font-bold">TOTAL</td>
                <td class="text-center font-bold">{{ $byFacility->sum('total_nasabah') }} Orang</td>
                <td class="text-right font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
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
