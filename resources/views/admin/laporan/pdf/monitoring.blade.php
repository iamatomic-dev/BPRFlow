<!DOCTYPE html>
<html>

<head>
    <title>Laporan Monitoring Kredit</title>
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
        <h1>BPR XYZ</h1>


    </div>

    {{-- JUDUL & PERIODE --}}
    <div class="sub-header">
        <h2>LAPORAN MONITORING ANGSURAN</h2>
        <p>Periode Akad: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Semua Data' }} s/d
            {{ $endDate ? date('d/m/Y', strtotime($endDate)) : '-' }}</p>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. PK</th>
                <th width="10%">Tgl Akad</th>
                <th width="20%">Nasabah</th>
                <th width="12%">Plafond</th>
                <th width="10%">Tenor</th>
                <th width="10%">Progress</th>
                <th width="18%">Sisa Kewajiban</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($credits as $index => $c)
                @php $sisa = $c->total_tagihan - $c->total_terbayar; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $c->no_perjanjian_kredit }}</td>
                    <td class="text-center">{{ $c->approved_at ? $c->approved_at->format('d/m/Y') : '-' }}</td>
                    <td>{{ $c->nasabahProfile->nama_lengkap }}</td>
                    <td class="text-right">Rp {{ number_format($c->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $c->jangka_waktu }} Bln</td>
                    <td class="text-center">{{ $c->sudah_bayar }} / {{ $c->total_angsuran }}</td>
                    <td class="text-right" style="font-weight:bold; color: {{ $sisa > 0 ? 'red' : 'green' }}">
                        Rp {{ number_format($sisa, 0, ',', '.') }}
                    </td>
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
