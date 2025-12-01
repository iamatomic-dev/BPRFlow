<!DOCTYPE html>
<html>

<head>
    <title>Laporan Realisasi Pinjaman</title>
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
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th>TANGGAL REALISASI</th>
                <th>NO PK</th>
                <th>NAMA NASABAH</th>
                <th>JANGKA WAKTU</th>
                <th>PLAFOND</th>
                <th>STATUS KREDIT</th>
                <th>PELUNASAN</th> {{-- Diisi 0 jika baru cair, atau data angsuran jika ada --}}
                <th>NETTING CAIR</th>
                <th>NETTING CAIR NASABAH</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Inisialisasi Grand Total
                $grandTotalPlafond = 0;
                $grandTotalPelunasan = 0;
                $grandTotalNetting = 0;
                $grandTotalNettingNasabah = 0;
            @endphp

            @foreach ($facilities as $facility)
                {{-- Hanya tampilkan fasilitas yang ada datanya --}}
                @if ($facility->creditApplications->count() > 0)
                    {{-- HEADER GROUP (NAMA FASILITAS) --}}
                    <tr class="group-header">
                        <td colspan="9">{{ strtoupper($facility->nama) }}</td>
                    </tr>

                    @php
                        // Inisialisasi Sub Total per Fasilitas
                        $subPlafond = 0;
                        $subPelunasan = 0;
                        $subNetting = 0;
                        $subNettingNasabah = 0;
                    @endphp

                    @foreach ($facility->creditApplications as $app)
                        @php
                            // PERHITUNGAN RUMUS
                            $plafond = $app->jumlah_pinjaman;

                            // Netting Cair = Plafond (Sesuai request)
                            $nettingCair = $plafond;

                            // Biaya Admin & Provisi 1.5%
                            $biaya = $plafond * 0.015;

                            // Netting Cair Nasabah = Plafond - 1.5%
                            $nettingNasabah = $plafond - $biaya;

                            // Data Pelunasan (Opsional: Ambil dari total yang sudah dibayar nasabah)
                            // Jika ini laporan pencairan murni, biasanya pelunasan 0.
                            // Tapi saya ambilkan dari data pembayaran agar informatif.
                            $pelunasan = $app->payments()->where('status_pembayaran', 'Paid')->sum('jumlah_bayar');

                            // Akumulasi Sub Total
                            $subPlafond += $plafond;
                            $subPelunasan += $pelunasan;
                            $subNetting += $nettingCair;
                            $subNettingNasabah += $nettingNasabah;
                        @endphp

                        <tr>
                            <td class="text-center">{{ $app->tgl_akad ? $app->tgl_akad->format('d/m/Y') : '-' }}</td>
                            <td>{{ $app->no_perjanjian_kredit }}</td>
                            <td>{{ $app->nasabahProfile->nama_lengkap }}</td>
                            <td class="text-center">{{ $app->jangka_waktu }} Bln</td>
                            <td class="text-right">{{ number_format($plafond, 0, ',', '.') }}</td>
                            <td class="text-center">{{ strtoupper($app->status) }}</td>
                            <td class="text-right">{{ number_format($pelunasan, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($nettingCair, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($nettingNasabah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                    {{-- BARIS SUB TOTAL --}}
                    <tr class="bg-subtotal">
                        <td colspan="4">SUB TOTAL {{ strtoupper($facility->nama) }}</td>
                        <td class="text-right">{{ number_format($subPlafond, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="text-right">{{ number_format($subPelunasan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($subNetting, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($subNettingNasabah, 0, ',', '.') }}</td>
                    </tr>

                    @php
                        // Akumulasi ke Grand Total
                        $grandTotalPlafond += $subPlafond;
                        $grandTotalPelunasan += $subPelunasan;
                        $grandTotalNetting += $subNetting;
                        $grandTotalNettingNasabah += $subNettingNasabah;
                    @endphp
                @endif
            @endforeach
        </tbody>

        {{-- FOOTER GRAND TOTAL --}}
        <tfoot>
            <tr class="bg-total">
                <td colspan="4" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ number_format($grandTotalPlafond, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">{{ number_format($grandTotalPelunasan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($grandTotalNetting, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($grandTotalNettingNasabah, 0, ',', '.') }}</td>
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
