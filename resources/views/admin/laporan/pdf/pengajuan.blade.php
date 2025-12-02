<!DOCTYPE html>
<html>

<head>
    <title>Laporan Persetujuan Kredit</title>
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
    </style>
</head>

<body>

    {{-- KOP LAPORAN --}}
    <div class="header">
        <h1>BPR Parinama Simfoni Indonesia</h1>
        <p>Jalan Terusan Buah Batu No.25, Bandung 40266, Jawa Barat</p>
    </div>

    {{-- JUDUL & PERIODE --}}
    <div class="sub-header">
        <h2>LAPORAN PERSETUJUAN KREDIT</h2>
        <p>Periode pengajuan: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Awal' }} s/d
            {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Sekarang' }}</p>
        @if (!empty($variabel))
            <p>Status Pengajuan: {{ $status }}</p>
        @endif
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 13%">NO PENGAJUAN</th>
                <th style="width: 15%">NAMA NASABAH</th>
                <th style="width: 12%">JENIS KREDIT</th>
                <th style="width: 5%">TENOR</th>
                <th style="width: 8%">JENIS SERTIFIKAT</th>
                <th style="width: 12%">PLAFOND DIAJUKAN</th>
                <th style="width: 13%">PLAFOND DISETUJUI</th>
                <th style="width: 8%">TANGGAL PENGAJUAN</th>
                <th style="width: 8%">TANGGAL REALISASI</th>
                <th style="width: 5%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
            <tr>
                <td>{{ $app->no_pengajuan }}</td>
                <td>{{ $app->nasabahProfile->nama_lengkap ?? '-' }}</td>
                <td>{{ $app->creditFacility->nama ?? '-' }}</td>
                <td class="text-center">
                    {{ $app->recommended_tenor ?? $app->jangka_waktu }} Bln
                </td>
                <td class="text-center">{{ $app->collateral->jenis_agunan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($app->jumlah_pinjaman, 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($app->status == 'Disetujui' || $app->status == 'Lunas')
                        Rp {{ number_format($app->recommended_amount ?? $app->jumlah_pinjaman, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $app->submitted_at ? $app->submitted_at->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $app->tgl_akad ? $app->tgl_akad->format('d/m/Y') : '-' }}</td>
                <td class="text-center font-bold">{{ strtoupper($app->status) }}</td>
            </tr>
            @endforeach
        </tbody>
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
