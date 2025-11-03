<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Direktur | BPR Parinama</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">

        <aside class="w-64 bg-[#0d1b2a] text-white flex-shrink-0">
            <div class="p-6 text-center border-b border-gray-700 flex items-center justify-center gap-2">
                <img src="{{ Vite::asset('resources/images/Logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div class="text-left">
                    <h2 class="text-lg font-bold leading-tight">BPR Parinama</h2>
                    <p class="text-xs">Simfoni Indonesia</p>
                </div>
            </div>
            <nav class="mt-6">
                <a href="#home" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-house mr-2"></i>Beranda</a>
                <a href="#screening" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-clipboard-list mr-2"></i>Keputusan Screening Awal</a>
                <a href="#analisis" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-chart-bar mr-2"></i>Analisis Kelayakan Kredit</a>
                <a href="#keputusan" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-handshake-angle mr-2"></i>Keputusan Kredit</a>
                <a href="#monitoring" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Monitoring Angsuran</a>
                <a href="#status" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-chart-line mr-2"></i>Status Pengajuan</a>
                <a href="#laporan" class="block px-6 py-3 hover:bg-[#1b263b] transition"><i class="fa-solid fa-print mr-2"></i>Cetak Laporan</a>
            </nav>
        </aside>

        <main class="flex-1 p-8 relative">
            <div class="flex justify-between items-center mb-6">
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

            <section id="home" class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Ringkasan Pengajuan Kredit</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-xl transition">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Pengajuan Baru</h3>
                        <p class="text-3xl font-bold text-blue-600">12</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-xl transition">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Menunggu Keputusan</h3>
                        <p class="text-3xl font-bold text-yellow-500">8</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-xl transition">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Disetujui</h3>
                        <p class="text-3xl font-bold text-green-600">25</p>
                    </div>
                </div>
            </section>

            <section id="screening" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Keputusan Screening Awal</h2>
                <p>Menampilkan daftar pengajuan untuk proses screening awal.</p>
            </section>

            <section id="analisis" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Analisis Kelayakan Kredit</h2>
                <p>Analisis detail pengajuan kredit.</p>
            </section>

            <section id="keputusan" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Keputusan Kredit</h2>
                <p>Menampilkan keputusan akhir pengajuan kredit.</p>
            </section>

            <section id="monitoring" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Monitoring Angsuran</h2>
                <p>Daftar angsuran dan status pembayaran debitur.</p>
            </section>

            <section id="status" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Status Pengajuan</h2>
                <p>Lihat status pengajuan kredit oleh nasabah.</p>
            </section>

            <section id="laporan" class="hidden">
                <h2 class="text-2xl font-semibold mb-2">Cetak Laporan</h2>
                <p>Cetak laporan pengajuan dan hasil keputusan kredit.</p>
            </section>
        </main>
    </div>

    <script>
        const btn = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        window.addEventListener('click', e => {
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