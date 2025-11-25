<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Nasabah</title>
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
        <h2>LAPORAN DATA NASABAH</h2>
        <p>Periode Register: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Awal' }} s/d
            {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Sekarang' }}</p>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Lengkap</th>
                <th style="width: 15%">NIK / KTP</th>
                <th style="width: 15%">No. HP</th>
                <th style="width: 15%">Email</th>
                <th style="width: 20%">Alamat Domisili</th>
                <th style="width: 10%">Tgl Join</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nasabahs as $index => $n)
                <tr>
                    <td class="p-3 border text-center">{{ $index + 1 }}</td>
                    <td class="p-3 border font-bold">{{ $n->nasabahProfile->nama_lengkap ?? $n->name }}</td>
                    <td class="p-3 border">{{ $n->nasabahProfile->no_ktp ?? '-' }}</td>
                    <td class="p-3 border">{{ $n->nasabahProfile->no_hp ?? '-' }}</td>
                    <td class="p-3 border">{{ $n->nasabahProfile->email ?? '-' }}</td>
                    <td class="p-3 border">{{ $n->nasabahProfile->alamat_tinggal ?? '-' }}</td>
                    <td class="p-3 border">{{ $n->created_at->format('d/m/Y') }}</td>
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
