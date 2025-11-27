<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin | BPR Parinama</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-[#0d1b2a] text-white flex-shrink-0">
            <div class="p-6 text-center border-b border-gray-700 flex items-center justify-center gap-2">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div class="text-left">
                    <h2 class="text-lg font-bold leading-tight">BPR Parinama</h2>
                    <p class="text-xs">Simfoni Indonesia</p>
                </div>
            </div>
            <nav class="mt-6">
                <a href="#home" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-house mr-2"></i>Beranda</a>
                <a href="#pengajuan" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-file-lines mr-2"></i>Data Pengajuan</a>
                <a href="#slik" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-upload mr-2"></i>Upload Hasil SLIK</a>
                <a href="#angsuran" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-money-bill-transfer mr-2"></i>Monitoring Angsuran</a>
                <a href="#status" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-chart-line mr-2"></i>Status Pengajuan</a>
                <a href="#jadwal" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-calendar-days mr-2"></i>Jadwal Angsuran</a>
                <a href="#laporan" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i
                        class="fa-solid fa-print mr-2"></i>Cetak Laporan</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 relative">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>

                <!-- Dropdown Profile -->
                <div class="relative">
                    <button id="dropdownButton"
                        class="flex items-center gap-2 px-4 py-2 bg-white border rounded-lg shadow hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ auth()->user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg overflow-hidden z-10">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Edit Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section: Beranda -->
            <section id="home" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                        <div class="flex items-center justify-center bg-blue-100 w-16 h-16 rounded-full mb-4">
                            <i class="fa-solid fa-file-invoice text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Total Pengajuan</h3>
                        <p class="text-3xl font-bold text-gray-700">1</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                        <div class="flex items-center justify-center bg-yellow-100 w-16 h-16 rounded-full mb-4">
                            <i class="fa-solid fa-hourglass-half text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-3xl font-bold text-gray-700">0</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                        <div class="flex items-center justify-center bg-green-100 w-16 h-16 rounded-full mb-4">
                            <i class="fa-solid fa-circle-check text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Disetujui</h3>
                        <p class="text-3xl font-bold text-gray-700">1</p>
                    </div>
                </div>
            </section>

            <!-- Section: Data Pengajuan (Admin view) -->
            <section id="pengajuan" class="hidden">
                <!-- List -->
                <div id="pengajuan-list" class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Data Pengajuan</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Daftar pengajuan nasabah. Klik "Lihat Detail" untuk melihat data
                        lengkap.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-4 py-2 border">No. Pengajuan</th>
                                    <th class="px-4 py-2 border">Nama Debitur</th>
                                    <th class="px-4 py-2 border">Jenis Kredit</th>
                                    <th class="px-4 py-2 border">Plafond</th>
                                    <th class="px-4 py-2 border">Tanggal</th>
                                    <th class="px-4 py-2 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 border">PGJ-1001</td>
                                    <td class="px-4 py-2 border">Budi Santoso</td>
                                    <td class="px-4 py-2 border">Kredit Mikro</td>
                                    <td class="px-4 py-2 border text-right">Rp 30.000.000</td>
                                    <td class="px-4 py-2 border text-center">01-10-2025</td>
                                    <td class="px-4 py-2 border text-center">
                                        <button
                                            class="lihat-detail-pengajuan bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-3 py-1 rounded font-semibold transition"
                                            data-id="PGJ-1001">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail -->
                <div id="pengajuan-detail" class="bg-white p-6 rounded-lg shadow hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="pengajuanDetailTitle" class="text-2xl font-semibold text-gray-800">Detail Pengajuan -
                            PGJ-1001</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                        <p><strong>No. Pengajuan:</strong> <span id="pd-no">PGJ-1001</span></p>
                        <p><strong>Nama Debitur:</strong> <span id="pd-nama">Budi Santoso</span></p>
                        <p><strong>Jenis Kredit:</strong> <span id="pd-jenis">Kredit Mikro</span></p>
                        <p><strong>Plafond:</strong> <span id="pd-plafond">Rp 30.000.000</span></p>
                        <p><strong>Jangka Waktu:</strong> <span id="pd-jangka">24 Bulan</span></p>
                        <p><strong>Status:</strong> <span id="pd-status">Dalam Proses</span></p>
                        <p><strong>Tanggal Pengajuan:</strong> <span id="pd-tanggal">01-10-2025</span></p>
                        <p><strong>Alamat:</strong> <span id="pd-alamat">Jl. Merdeka No. 10, Bandung</span></p>
                    </div>

                    <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-3">Dokumen & Keterangan</h3>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>📄 KTP: tersedia</li>
                        <li>📄 Slip Gaji: tersedia</li>
                        <li>📝 Keterangan: Permohonan untuk modal usaha usaha kecil</li>
                    </ul>

                    <div class="mt-6 text-right">
                        <button id="kembaliPengajuanBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                            Kembali ke Daftar Pengajuan
                        </button>
                    </div>
                </div>
            </section>

            <!-- Section: Upload Hasil SLIK -->
            <section id="slik" class="hidden">
                <div id="slik-list" class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Upload Hasil SLIK</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Pilih pengajuan, lalu upload hasil SLIK (PDF).</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-4 py-2 border">No. Pengajuan</th>
                                    <th class="px-4 py-2 border">Nama Debitur</th>
                                    <th class="px-4 py-2 border">Jenis Kredit</th>
                                    <th class="px-4 py-2 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 border">PGJ-1001</td>
                                    <td class="px-4 py-2 border">Budi Santoso</td>
                                    <td class="px-4 py-2 border">Kredit Mikro</td>
                                    <td class="px-4 py-2 border text-center">
                                        <button
                                            class="lihat-detail-slik bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-3 py-1 rounded font-semibold transition"
                                            data-id="PGJ-1001">
                                            Lihat & Upload
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail SLIK & Upload -->
                <div id="slik-detail" class="bg-white p-6 rounded-lg shadow hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="slikDetailTitle" class="text-2xl font-semibold text-gray-800">Upload Hasil SLIK -
                            PGJ-1001</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                        <p><strong>No. Pengajuan:</strong> PGJ-1001</p>
                        <p><strong>Nama Debitur:</strong> Budi Santoso</p>
                        <p><strong>Nomor KTP:</strong> 3276xxxxxxxx</p>
                        <p><strong>Jenis Kredit:</strong> Kredit Mikro</p>
                    </div>

                    <form id="formSlik" class="space-y-4" onsubmit="event.preventDefault(); handleSlikUpload();">
                        <div>
                            <label class="block font-medium">Upload File (PDF)</label>
                            <input id="slikFile" accept="application/pdf" type="file" class="mt-1" required />
                            <p class="text-xs text-gray-500 mt-1">Hanya file PDF, max 5MB.</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded">
                                Submit Upload
                            </button>
                            <button type="button" id="kembaliSlikBtn"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Section: Monitoring Angsuran -->
            <section id="angsuran" class="hidden">
                <div id="angsuran-list" class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Monitoring Angsuran</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Daftar pinjaman yang dipantau. Klik "Lihat Detail" untuk jadwal &
                        status pembayaran.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-4 py-2 border">No. Perjanjian</th>
                                    <th class="px-4 py-2 border">Nama</th>
                                    <th class="px-4 py-2 border">Plafond</th>
                                    <th class="px-4 py-2 border">Sisa</th>
                                    <th class="px-4 py-2 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 border">PK-2001</td>
                                    <td class="px-4 py-2 border">Budi Santoso</td>
                                    <td class="px-4 py-2 border text-right">Rp 30.000.000</td>
                                    <td class="px-4 py-2 border text-right">Rp 25.000.000</td>
                                    <td class="px-4 py-2 border text-center">
                                        <button
                                            class="lihat-detail-angsuran bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-3 py-1 rounded font-semibold transition"
                                            data-id="PK-2001">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Angsuran -->
                <div id="angsuran-detail" class="bg-white p-6 rounded-lg shadow hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="angsuranDetailTitle" class="text-2xl font-semibold text-gray-800">Detail Angsuran -
                            PK-2001</h2>
                        <a href="#"
                            class="bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-4 py-2 rounded font-semibold">Download
                            PDF</a>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                        <p><strong>No. Perjanjian:</strong> PK-2001</p>
                        <p><strong>Nama:</strong> Budi Santoso</p>
                        <p><strong>Plafond:</strong> Rp 30.000.000</p>
                        <p><strong>Outstanding:</strong> Rp 25.000.000</p>
                        <p><strong>Jangka Waktu:</strong> 36 Bulan</p>
                        <p><strong>Bunga:</strong> 12%</p>
                    </div>

                    <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-3">Jadwal Angsuran (preview)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Tgl Angsuran</th>
                                    <th class="px-3 py-2 border">Pokok</th>
                                    <th class="px-3 py-2 border">Bunga</th>
                                    <th class="px-3 py-2 border">Total</th>
                                    <th class="px-3 py-2 border">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center">1</td>
                                    <td class="px-3 py-2 border text-center">20-09-2025</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.000.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 250.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.250.000</td>
                                    <td class="px-3 py-2 border text-center"><span
                                            class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Lunas</span>
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center">2</td>
                                    <td class="px-3 py-2 border text-center">20-10-2025</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.000.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 250.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.250.000</td>
                                    <td class="px-3 py-2 border text-center"><span
                                            class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Belum
                                            Bayar</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-right">
                        <button id="kembaliAngsuranBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold">Kembali</button>
                    </div>
                </div>
            </section>

            <!-- Section: Status Pengajuan (Admin) -->
            <section id="status" class="hidden">
                <div id="status-list" class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Status Pengajuan</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Lihat status hasil verifikasi pengajuan.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-4 py-2 border">No. Pengajuan</th>
                                    <th class="px-4 py-2 border">Nama</th>
                                    <th class="px-4 py-2 border">Status</th>
                                    <th class="px-4 py-2 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 border">PGJ-1001</td>
                                    <td class="px-4 py-2 border">Budi Santoso</td>
                                    <td class="px-4 py-2 border text-center"><span
                                            class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-medium">Disetujui</span>
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        <button
                                            class="lihat-detail-status bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-3 py-1 rounded font-semibold transition"
                                            data-id="PGJ-1001">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Status -->
                <div id="status-detail" class="bg-white p-6 rounded-lg shadow hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="statusDetailTitle" class="text-2xl font-semibold text-gray-800">Detail Status -
                            PGJ-1001</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                        <p><strong>No. Pengajuan:</strong> PGJ-1001</p>
                        <p><strong>Nama:</strong> Budi Santoso</p>
                        <p><strong>Status Verifikasi:</strong> Disetujui</p>
                        <p><strong>Petugas:</strong> Analis - Andi</p>
                        <p><strong>Tanggal Verifikasi:</strong> 05-10-2025</p>
                        <p><strong>Keterangan:</strong> Lolos verifikasi administrasi dan SLIK.</p>
                    </div>

                    <div class="mt-6 text-right">
                        <button id="kembaliStatusBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold">Kembali</button>
                    </div>
                </div>
            </section>

            <!-- Section: Jadwal Angsuran (Admin) -->
            <section id="jadwal" class="hidden">
                <div id="jadwal-list" class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Jadwal Angsuran</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Kelola dan lihat jadwal angsuran per perjanjian.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-4 py-2 border">No. Perjanjian</th>
                                    <th class="px-4 py-2 border">Nama</th>
                                    <th class="px-4 py-2 border">Tgl Pencairan</th>
                                    <th class="px-4 py-2 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 border">PK-2001</td>
                                    <td class="px-4 py-2 border">Budi Santoso</td>
                                    <td class="px-4 py-2 border text-center">29-08-2025</td>
                                    <td class="px-4 py-2 border text-center">
                                        <button
                                            class="lihat-detail-jadwal bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-3 py-1 rounded font-semibold transition"
                                            data-id="PK-2001">Lihat Jadwal</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="jadwal-detail" class="bg-white p-6 rounded-lg shadow hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="jadwalDetailTitle" class="text-2xl font-semibold text-gray-800">Jadwal Angsuran -
                            PK-2001</h2>
                        <a href="#"
                            class="bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-4 py-2 rounded font-semibold">Download
                            PDF</a>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                        <p><strong>No. Perjanjian:</strong> PK-2001</p>
                        <p><strong>Nama:</strong> Budi Santoso</p>
                        <p><strong>Plafond:</strong> Rp 30.000.000</p>
                        <p><strong>Outstanding:</strong> Rp 25.000.000</p>
                    </div>

                    <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-3">Rincian Jadwal</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-[#0d1b2a] text-white">
                                <tr>
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Tgl</th>
                                    <th class="px-3 py-2 border">Pokok</th>
                                    <th class="px-3 py-2 border">Bunga</th>
                                    <th class="px-3 py-2 border">Total</th>
                                    <th class="px-3 py-2 border">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center">1</td>
                                    <td class="px-3 py-2 border text-center">20-09-2025</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.000.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 250.000</td>
                                    <td class="px-3 py-2 border text-right">Rp 1.250.000</td>
                                    <td class="px-3 py-2 border text-center"><span
                                            class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Lunas</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-right">
                        <button id="kembaliJadwalBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold">Kembali</button>
                    </div>
                </div>
            </section>

            <!-- Section: Cetak Laporan -->
            <section id="laporan" class="hidden">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Cetak Laporan</h2>
                    </div>

                    <p class="text-gray-600 mb-4">Pilih jenis laporan yang ingin dicetak.</p>

                    <div class="grid md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block font-medium">Pilih Laporan</label>
                            <select id="laporanSelect" class="w-full border rounded px-3 py-2">
                                <option value="">-- Pilih jenis laporan --</option>
                                <option value="pengajuan">Laporan Pengajuan Kredit</option>
                                <option value="persetujuan">Laporan Persetujuan Kredit</option>
                                <option value="total">Laporan Total Kredit Berjalan</option>
                                <option value="kolek">Laporan Kolektibilitas Kredit</option>
                                <option value="nasabah">Laporan Data Nasabah</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium">Periode</label>
                            <input type="month" id="laporanPeriode" class="w-full border rounded px-3 py-2" />
                        </div>

                        <div>
                            <button id="generateLaporanBtn"
                                class="bg-yellow-400 hover:bg-yellow-300 text-[#0d1b2a] px-4 py-2 rounded font-semibold">Tampilkan
                                Preview</button>
                        </div>
                    </div>

                    <div id="laporanPreview" class="mt-6 hidden">
                        <h3 id="laporanPreviewTitle" class="text-lg font-semibold mb-3"></h3>

                        <div class="overflow-x-auto">
                            <table id="laporanTable" class="min-w-full text-sm border border-gray-300">
                                <!-- akan diisi oleh JS -->
                            </table>
                        </div>

                        <div class="mt-4 text-right">
                            <button id="cetakBtn"
                                class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded">Cetak</button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Dropdown profile
        const btn = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        window.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Navigation between main sections
        const links = document.querySelectorAll('aside nav a');
        const sections = document.querySelectorAll('main section');
        links.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                sections.forEach(sec => sec.classList.add('hidden'));
                target.classList.remove('hidden');

                // Scroll to top of main for better UX
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        // --- Pengajuan: show/hide detail ---
        const lihatDetailPengajuanButtons = document.querySelectorAll('.lihat-detail-pengajuan');
        const pengajuanList = document.getElementById('pengajuan-list');
        const pengajuanDetail = document.getElementById('pengajuan-detail');
        const kembaliPengajuanBtn = document.getElementById('kembaliPengajuanBtn');

        lihatDetailPengajuanButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('pengajuanDetailTitle').textContent = `Detail Pengajuan - ${id}`;
                document.getElementById('pd-no').textContent = id;
                pengajuanList.classList.add('hidden');
                pengajuanDetail.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        kembaliPengajuanBtn.addEventListener('click', () => {
            pengajuanDetail.classList.add('hidden');
            pengajuanList.classList.remove('hidden');
        });

        // --- SLIK upload flow ---
        const lihatDetailSlikButtons = document.querySelectorAll('.lihat-detail-slik');
        const slikList = document.getElementById('slik-list');
        const slikDetail = document.getElementById('slik-detail');
        const kembaliSlikBtn = document.getElementById('kembaliSlikBtn');

        lihatDetailSlikButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('slikDetailTitle').textContent = `Upload Hasil SLIK - ${id}`;
                slikList.classList.add('hidden');
                slikDetail.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        kembaliSlikBtn.addEventListener('click', () => {
            slikDetail.classList.add('hidden');
            slikList.classList.remove('hidden');
        });

        function handleSlikUpload() {
            const fileInput = document.getElementById('slikFile');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Pilih file PDF dulu.');
                return;
            }

            const file = fileInput.files[0];
            if (file.type !== 'application/pdf') {
                alert('Tolong upload file PDF.');
                return;
            }

            alert(`File "${file.name}" berhasil  diupload.`);
            slikDetail.classList.add('hidden');
            slikList.classList.remove('hidden');
            fileInput.value = '';
        }

        // --- Angsuran detail ---
        const lihatDetailAngsuranButtons = document.querySelectorAll('.lihat-detail-angsuran');
        const angsuranList = document.getElementById('angsuran-list');
        const angsuranDetail = document.getElementById('angsuran-detail');
        const kembaliAngsuranBtn = document.getElementById('kembaliAngsuranBtn');

        lihatDetailAngsuranButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('angsuranDetailTitle').textContent = `Detail Angsuran - ${id}`;
                angsuranList.classList.add('hidden');
                angsuranDetail.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        kembaliAngsuranBtn.addEventListener('click', () => {
            angsuranDetail.classList.add('hidden');
            angsuranList.classList.remove('hidden');
        });

        // --- Status pengajuan detail ---
        const lihatDetailStatusButtons = document.querySelectorAll('.lihat-detail-status');
        const statusList = document.getElementById('status-list');
        const statusDetail = document.getElementById('status-detail');
        const kembaliStatusBtn = document.getElementById('kembaliStatusBtn');

        lihatDetailStatusButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('statusDetailTitle').textContent = `Detail Status - ${id}`;
                statusList.classList.add('hidden');
                statusDetail.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        kembaliStatusBtn.addEventListener('click', () => {
            statusDetail.classList.add('hidden');
            statusList.classList.remove('hidden');
        });

        // --- Jadwal angsuran detail ---
        const lihatDetailJadwalButtons = document.querySelectorAll('.lihat-detail-jadwal');
        const jadwalList = document.getElementById('jadwal-list');
        const jadwalDetail = document.getElementById('jadwal-detail');
        const kembaliJadwalBtn = document.getElementById('kembaliJadwalBtn');

        lihatDetailJadwalButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('jadwalDetailTitle').textContent = `Jadwal Angsuran - ${id}`;
                jadwalList.classList.add('hidden');
                jadwalDetail.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        kembaliJadwalBtn.addEventListener('click', () => {
            jadwalDetail.classList.add('hidden');
            jadwalList.classList.remove('hidden');
        });

        // --- Cetak Laporan (preview) ---
        const laporanSelect = document.getElementById('laporanSelect');
        const generateLaporanBtn = document.getElementById('generateLaporanBtn');
        const laporanPreview = document.getElementById('laporanPreview');
        const laporanPreviewTitle = document.getElementById('laporanPreviewTitle');
        const laporanTable = document.getElementById('laporanTable');
        const cetakBtn = document.getElementById('cetakBtn');

        generateLaporanBtn.addEventListener('click', () => {
            const jenis = laporanSelect.value;
            const periode = document.getElementById('laporanPeriode').value || 'Periode: -';
            if (!jenis) {
                alert('Pilih jenis laporan terlebih dahulu.');
                return;
            }

            laporanPreview.classList.remove('hidden');

            // Isi judul
            let title = '';
            if (jenis === 'pengajuan') title = `Laporan Pengajuan Kredit - ${periode}`;
            else if (jenis === 'persetujuan') title = `Laporan Persetujuan Kredit - ${periode}`;
            else if (jenis === 'total') title = `Laporan Total Kredit Berjalan - ${periode}`;
            else if (jenis === 'kolek') title = `Laporan Kolektibilitas Kredit - ${periode}`;
            else if (jenis === 'nasabah') title = `Laporan Data Nasabah - ${periode}`;
            laporanPreviewTitle.textContent = title;

            // Isi tabel dummy
            let html = '';
            if (jenis === 'pengajuan') {
                html = `
                <thead class="bg-[#0d1b2a] text-white"><tr>
                    <th class="px-4 py-2 border">No. Pengajuan</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Jenis</th>
                    <th class="px-4 py-2 border">Plafond</th>
                </tr></thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">PGJ-1001</td>
                        <td class="px-4 py-2 border">Budi Santoso</td>
                        <td class="px-4 py-2 border">Kredit Mikro</td>
                        <td class="px-4 py-2 border text-right">Rp 30.000.000</td>
                    </tr>
                </tbody>`;
            } else if (jenis === 'persetujuan') {
                html = `
                <thead class="bg-[#0d1b2a] text-white"><tr>
                    <th class="px-4 py-2 border">No. Pengajuan</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                </tr></thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">PGJ-1001</td>
                        <td class="px-4 py-2 border">Budi Santoso</td>
                        <td class="px-4 py-2 border text-center">Disetujui</td>
                        <td class="px-4 py-2 border text-center">05-10-2025</td>
                    </tr>
                </tbody>`;
            } else if (jenis === 'total') {
                html = `
                <thead class="bg-[#0d1b2a] text-white"><tr>
                    <th class="px-4 py-2 border">Total Kredit Berjalan</th>
                    <th class="px-4 py-2 border">Jumlah Rekening</th>
                    <th class="px-4 py-2 border">Total Outstanding</th>
                </tr></thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">Kredit Mikro</td>
                        <td class="px-4 py-2 border text-center">1</td>
                        <td class="px-4 py-2 border text-right">Rp 25.000.000</td>
                    </tr>
                </tbody>`;
            } else if (jenis === 'kolek') {
                html = `
                <thead class="bg-[#0d1b2a] text-white"><tr>
                    <th class="px-4 py-2 border">No. Perjanjian</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Kolektibilitas</th>
                </tr></thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">PK-2001</td>
                        <td class="px-4 py-2 border">Budi Santoso</td>
                        <td class="px-4 py-2 border text-center">1 (Lancar)</td>
                    </tr>
                </tbody>`;
            } else if (jenis === 'nasabah') {
                html = `
                <thead class="bg-[#0d1b2a] text-white"><tr>
                    <th class="px-4 py-2 border">No. Rekening</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">No. KTP</th>
                </tr></thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">N-0001</td>
                        <td class="px-4 py-2 border">Budi Santoso</td>
                        <td class="px-4 py-2 border">3276xxxxxxxx</td>
                    </tr>
                </tbody>`;
            }

            laporanTable.innerHTML = html;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // show home by default
        document.querySelector('main section#home').classList.remove('hidden');
    </script>
</body>

</html>
