<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Pengajuan</title>
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
        <p>Telp: (62) 812-5000-5066 | Web: bprparinama.co.id</p>
    </div>

    {{-- JUDUL & PERIODE --}}
    <div class="sub-header">
        <h2>LAPORAN DATA PENGAJUAN</h2>
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
                <th style="width: 5%">No</th>
                <th style="width: 10%">Tanggal Masuk</th>
                <th style="width: 15%">No Pengajuan</th>
                <th style="width: 15%">Nama Nasabah</th>
                <th style="width: 25%">Fasilitas</th>
                <th style="width: 15% text-right">Plafond</th>
                <th style="width: 10%">Tenor</th>
                <th style="width: 10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $index => $n)
                <tr>
                    <td class="p-3 border text-center">{{ $index + 1 }}</td>
                    <td class="p-3 border font-bold">
                        {{ $n->submitted_at ? $n->submitted_at->format('d/m/Y') : '-' }}</td>
                    <td class="p-3 border">{{ $n->no_pengajuan }}</td>
                    <td class="p-3 border font-bold">{{ $n->nasabahProfile->nama_lengkap }}</td>
                    <td class="p-3 border">{{ $n->creditFacility->nama }}</td>
                    <td class="p-3 border text-right">Rp {{ number_format($n->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td class="p-3 border text-center">{{ $n->jangka_waktu }} Bln</td>
                    <td class="p-3 border text-center">{{ $n->status }}</td>
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
