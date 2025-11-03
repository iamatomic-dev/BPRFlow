    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | BPR Parinama</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    </head>

    <body class="bg-gray-100 text-gray-800">

        <body class="bg-gray-100 text-gray-800">

            <!-- Sidebar: fixed, full height -->
            <aside class="w-64 bg-[#0d1b2a] text-white fixed top-0 left-0 h-screen">
                <div class="p-6 text-center border-b border-gray-700 flex items-center justify-center gap-2">
                    <img src="{{ Vite::asset('resources/images/Logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <div class="text-left">
                        <h2 class="text-lg font-bold leading-tight">BPR Parinama</h2>
                        <p class="text-xs">Simfoni Indonesia</p>
                    </div>
                </div>
                <nav class="mt-6">
                    <a href="#home" class="block px-6 py-3 hover:bg-[#1b263b] transition">Beranda</a>
                    <a href="#pengajuan" class="block px-6 py-3 hover:bg-[#1b263b] transition">Pengajuan Kredit</a>
                    <a href="#status" class="block px-6 py-3 hover:bg-[#1b263b] transition">Status Pengajuan</a>
                    <a href="#jadwal" class="block px-6 py-3 hover:bg-[#1b263b] transition">Jadwal Angsuran</a>
                    <a href="#simulasi" class="block px-6 py-3 hover:bg-[#1b263b] transition">Simulasi Kredit</a>
                </nav>
            </aside>
            </aside>

            <!-- Sticky Header full width -->
            <header class="sticky top-0 left-64 w-[calc(100%-16rem)] bg-gray-100 z-40 shadow-md px-6 py-4 flex justify-between items-center">
                <h1 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg overflow-hidden z-50">
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
            </header>

            <!-- Main Content -->
            <main class="flex-1 ml-64 p-6">

                <!-- Section: Beranda -->
                <section id="home" class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Produk Kredit Unggulan Kami</h2>
                    <p class="text-gray-600 mb-8">Kredit Parinama bertujuan memberikan solusi bagi anda yang membutuhkan pinjaman baik untuk kebutuhan konsumtif, investasi ataupun modal kerja dengan syarat yang mudah dan proses yang cepat.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                            <div class="flex items-center justify-center bg-blue-100 w-16 h-16 rounded-full mb-4">
                                <i class="fa-solid fa-briefcase text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Kredit Modal Kerja (KMK)</h3>
                            <p class="text-gray-600">Kredit untuk usaha ekonomi produktif di sektor Usaha Mikro Kecil dan Menengah</p>
                            <ul class="list-disc list-inside text-gray-600 ml-4">
                                <li>Pembelian Bahan Baku</li>
                                <li>Pengembangan Usaha</li>
                                <li>Biaya Operasional</li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                            <div class="flex items-center justify-center bg-green-100 w-16 h-16 rounded-full mb-4">
                                <i class="fa-solid fa-home text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Kredit Multi Guna (KMG)</h3>
                            <p class="text-gray-600">Kredit yang diberikan kepada anggota masyarakat untuk berbagai kebutuhan konsumtif</p>
                            <ul class="list-disc list-inside text-gray-600 ml-4">
                                <li>Biaya Pendidikan</li>
                                <li>Biaya Renovasi Rumah</li>
                                <li>Biaya Pernikahan</li>
                                <li>Biaya Konsumtif Lainnya</li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all">
                            <div class="flex items-center justify-center bg-yellow-100 w-16 h-16 rounded-full mb-4">
                                <i class="fa-solid fa-chart-line text-yellow-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Kredit Investasi (KI)</h3>
                            <p class="text-gray-600">Untuk membiayai kebutuhan barang modal dalam rangka rehabilitasi, modernisasi, perluasan, pendirian proyek baru dan atau kebutuhan khusus terkait investasi</p>
                            <ul class="list-disc list-inside text-gray-600 ml-4">
                                <li>Pembelian Aset Usaha</li>
                                <li>Pembelian Mesin Usaha</li>
                                <li>Pembelian Properti Usaha</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-8 bg-white rounded-2xl shadow-md p-8 hover:shadow-xl transition-all">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Syarat dan Ketentuan Umum Pengajuan Pinjaman Kredit :</h3>
                        <p class="text-gray-600 mb-4">
                            *Persyaratan Dokumen WAJIB dilampirkan aslinya disaat pengikatan kredit
                        </p>
                        <h4 class="font-semibold text-gray-800 mb-3">Karyawan</h4>
                        <ul class="list-disc list-inside text-gray-600 ml-4 mb-6">
                            <li>Foto Copy KTP Suami Istri</li>
                            <li>Foto Copy Kartu Keluarga dan Surat Nikah</li>
                            <li>Rumah Milik Sendiri (PBB & Rek Listrik / Telp / Pam) / Jika Rumah Kontrak (Surat Perjanjian Sewa Rumah & Usaha)</li>
                            <li>Slip Gaji / Surat Keterangan Penghasilan (Karyawan / Pegawai / PNS)</li>
                            <li>Surat Keterangan Bekerja Minimal 3 (tiga) Tahun dan Tanda Pengenal Perusahaan</li>
                            <li>Foto Copy Surat Ijin Praktek (Khusus Profesional)</li>
                            <li>Foto Copy NPWP</li>
                            <li>Foto Copy Buku Tabungan / Rek Koran 3 Bulan Terakhir</li>
                        </ul>
                        <h4 class="font-semibold text-gray-800 mb-3">Wiraswasta</h4>
                        <ul class="list-disc list-inside text-gray-600 ml-4 mb-6">
                            <li>Foto Copy KTP Suami Istri</li>
                            <li>Foto Copy Kartu Keluarga dan Surat Nikah</li>
                            <li>Rumah Milik Sendiri (PBB & Rek Listrik / Telp / Pam) / Jika Rumah Kontrak (Surat Perjanjian Sewa Rumah & Usaha)</li>
                            <li>Surat Keterangan Domisili (SKD) dan Surat Keterangan Usaha (SKU)</li>
                            <li>Foto Copy NPWP Surat Keterangan Terdaftar</li>
                            <li>SIUP TDP Anggaran Dasar PTV / CV</li>
                            <li>Foto Copy Tabungan / Rekening Koran 3 (tiga) bulan terakhir</li>
                            <li>Purchasing Order (PO) Kontrak Kerja / SPK Faktur dan bukti pendukung adanya proyek yang akan dilaksanakan</li>
                        </ul>
                    </div>
                </section>

                <!-- Section: Pengajuan Kredit -->
                <section id="pengajuan" class="mb-8 hidden">
                    <form action="{{ route('pengajuan.store') }}" method="POST" class="mt-4 space-y-6 bg-white p-6 rounded-lg shadow">
                        @csrf
                        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Form Pengajuan Kredit</h2>

                        <!-- Data Pemohon -->
                        <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-2">Data Pemohon</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium">Jenis Fasilitas</label>
                                <select name="jenis_fasilitas" class="w-full border rounded px-3 py-2" required>
                                    <option value="KMG">Kredit Modal Kerja (KMG)</option>
                                    <option value="KMK">Kredit Multi Guna (KMK)</option>
                                    <option value="KI">Kredit Investasi (KI)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium">Jumlah Pinjaman (Rp)</label>
                                <input type="number" name="jumlah_pinjaman" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Jangka Waktu (bulan)</label>
                                <input type="number" name="jangka_waktu" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Nomor KTP</label>
                                <input type="text" name="no_ktp" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium">No. HP</label>
                                <input type="text" name="no_hp" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Email</label>
                                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-medium">Alamat Tempat Tinggal</label>
                                <textarea name="alamat_tinggal" rows="2" class="w-full border rounded px-3 py-2" required></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-medium">Alamat Sesuai KTP</label>
                                <textarea name="alamat_ktp" rows="2" class="w-full border rounded px-3 py-2" required></textarea>
                            </div>
                            <div>
                                <label class="block font-medium">Status Perkawinan</label>
                                <select name="status_perkawinan" class="w-full border rounded px-3 py-2" required>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai">Cerai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium">No. NPWP (Wajib untuk pinjaman >50 juta)</label>
                                <input type="text" name="no_npwp" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block font-medium">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Agama</label>
                                <input type="text" name="agama" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu_kandung" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block font-medium">Status Kepemilikan Rumah</label>
                                <select name="status_rumah" class="w-full border rounded px-3 py-2" required>
                                    <option value="Milik Sendiri">Milik Sendiri</option>
                                    <option value="Sewa">Sewa</option>
                                </select>
                            </div>
                        </div>

                        <!-- Data Pasangan -->
                        <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-2 mt-6">Data Pasangan</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div><label class="block font-medium">Nama Pasangan</label><input type="text" name="pasangan_nama" class="w-full border rounded px-3 py-2"></div>
                            <div><label class="block font-medium">No. KTP Pasangan</label><input type="text" name="pasangan_no_ktp" class="w-full border rounded px-3 py-2"></div>
                            <div class="md:col-span-2"><label class="block font-medium">Alamat Tinggal Pasangan</label><textarea name="pasangan_alamat_tinggal" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
                            <div class="md:col-span-2"><label class="block font-medium">Alamat KTP Pasangan</label><textarea name="pasangan_alamat_ktp" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
                            <div><label class="block font-medium">Pekerjaan Pasangan</label><input type="text" name="pasangan_pekerjaan" class="w-full border rounded px-3 py-2"></div>
                            <div><label class="block font-medium">Email Pasangan</label><input type="email" name="pasangan_email" class="w-full border rounded px-3 py-2"></div>
                        </div>

                        <!-- Data Penjamin -->
                        <h3 class="text-lg font-semibold text-blue-900 border-b pb-2 mb-2 mt-6">Data Penjamin / Kerabat</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div><label class="block font-medium">Nama Penjamin</label><input type="text" name="penjamin_nama" class="w-full border rounded px-3 py-2"></div>
                            <div><label class="block font-medium">No. KTP Penjamin</label><input type="text" name="penjamin_no_ktp" class="w-full border rounded px-3 py-2"></div>
                            <div><label class="block font-medium">Hubungan dengan Pemohon</label><input type="text" name="penjamin_hubungan" class="w-full border rounded px-3 py-2"></div>
                            <div class="md:col-span-2"><label class="block font-medium">Alamat Penjamin</label><textarea name="penjamin_alamat" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
                            <div><label class="block font-medium">Email Penjamin</label><input type="email" name="penjamin_email" class="w-full border rounded px-3 py-2"></div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="bg-yellow-400 text-[#0d1b2a] px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300 transition">
                                Submit
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Section: Status Pengajuan -->
                <section id="status" class="mb-8 hidden">

                </section>

                <!-- Section: Jadwal Angsuran -->
                <section id="jadwal" class="hidden mb-8">
                </section>

            </main>
            </div>

            <script>
                const btn = document.getElementById('dropdownButton');
                const menu = document.getElementById('dropdownMenu');
                btn.addEventListener('click', () => menu.classList.toggle('hidden'));
                window.addEventListener('click', (e) => {
                    if (!btn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });

                const links = document.querySelectorAll('aside nav a');
                const sections = document.querySelectorAll('main section');
                links.forEach(link => {
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        const target = document.querySelector(link.getAttribute('href'));
                        sections.forEach(sec => sec.classList.add('hidden'));
                        target.classList.remove('hidden');
                    });
                });
            </script>
        </body>

    </html>
