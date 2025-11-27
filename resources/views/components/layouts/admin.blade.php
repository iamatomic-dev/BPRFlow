<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }} | BPR Parinama Simfoni Indonesia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <div id="sidebarOverlay"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden transition-opacity opacity-0 md:hidden">
    </div>

    <aside id="sidebar"
        class="fixed top-0 left-0 z-50 h-screen w-64 bg-[#0d1b2a] text-white transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0">

        <div class="h-16 flex items-center justify-center border-b border-gray-700 px-6 gap-3 bg-[#0d1b2a]">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            <div class="text-left">
                <h2 class="text-base font-bold leading-tight">BPR Parinama</h2>
                <p class="text-[10px] text-gray-400 tracking-wider">SIMFONI INDONESIA</p>
            </div>
            <button id="closeSidebar" class="md:hidden ml-auto text-gray-400 hover:text-white">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <nav class="mt-6 px-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-[#1b263b] text-white' : 'text-gray-300 hover:bg-[#1b263b] hover:text-white' }}">
                <i class="fa-solid fa-house w-6 text-center mr-2 text-sm"></i>
                <span class="font-medium">Beranda</span>
            </a>

            <a href="{{ route('admin.pengajuan.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.pengajuan.*') ? 'bg-[#1b263b] text-white' : 'text-gray-300 hover:bg-[#1b263b] hover:text-white' }}">
                <i class="fa-solid fa-file-invoice-dollar w-6 text-center mr-2 text-sm"></i>
                <span class="font-medium">Pengajuan Kredit</span>
            </a>

            <a href="{{ route('admin.slik.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.slik.*') ? 'bg-[#1b263b] text-white' : 'text-gray-300 hover:bg-[#1b263b] hover:text-white' }}">
                <i class="fa-solid fa-upload w-6 text-center mr-2 text-sm"></i>
                <span class="font-medium">Upload Slik</span>
            </a>

            <a href="{{ route('admin.angsuran.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.angsuran.*') ? 'bg-[#1b263b] text-white' : 'text-gray-300 hover:bg-[#1b263b] hover:text-white' }}">
                <i class="fa-solid fa-clock-rotate-left w-6 text-center mr-2 text-sm"></i>
                <span class="font-medium">Kredit & Angsuran</span>
            </a>

            @php
                $isLaporanActive = request()->routeIs('admin.laporan.*');
            @endphp

            <div>
                <button type="button" id="laporanBtn"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ $isLaporanActive ? 'bg-[#1b263b] text-white' : 'text-gray-300 hover:bg-[#1b263b] hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chart-line w-6 text-center mr-2 text-sm"></i>
                        <span class="font-medium">Laporan</span>
                    </div>

                    <i id="laporanArrow"
                        class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isLaporanActive ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="laporanMenu" class="space-y-1 mt-1 {{ $isLaporanActive ? 'block' : 'hidden' }}">

                    <a href="{{ route('admin.laporan.nasabah') }}"
                        class="block pl-12 pr-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.nasabah') ? 'text-white bg-[#26354f]' : 'text-gray-400 hover:text-white hover:bg-[#26354f]' }}">
                        Data Nasabah
                    </a>

                    <a href="{{ route('admin.laporan.pengajuan') }}"
                        class="block pl-12 pr-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.pengajuan') ? 'text-white bg-[#26354f]' : 'text-gray-400 hover:text-white hover:bg-[#26354f]' }}">
                        Status Pengajuan
                    </a>

                    <a href="{{ route('admin.laporan.analisis') }}"
                        class="block pl-12 pr-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.analisis') ? 'text-white bg-[#26354f]' : 'text-gray-400 hover:text-white hover:bg-[#26354f]' }}">
                        Analisis Kredit
                    </a>

                    <a href="{{ route('admin.laporan.monitoring') }}"
                        class="block pl-12 pr-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.monitoring') ? 'text-white bg-[#26354f]' : 'text-gray-400 hover:text-white hover:bg-[#26354f]' }}">
                        Monitoring Angsuran
                    </a>

                    <a href="{{ route('admin.laporan.rekapitulasi') }}"
                        class="block pl-12 pr-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.rekapitulasi') ? 'text-white bg-[#26354f]' : 'text-gray-400 hover:text-white hover:bg-[#26354f]' }}">
                        Rekapitulasi
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <div class="flex flex-col min-h-screen md:ml-64 transition-all duration-300">

        <header class="sticky top-0 z-30 bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-6">

            <div class="flex items-center gap-4">
                <button id="hamburgerBtn"
                    class="p-2 -ml-2 text-gray-600 rounded-md md:hidden hover:bg-gray-100 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="text-base md:text-lg font-semibold text-gray-800 truncate max-w-[180px] sm:max-w-none">
                    {{ $header ?? '' }}
                </div>
            </div>

            <div class="relative">
                <button id="dropdownButton"
                    class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">

                    <div
                        class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>

                    <span class="hidden sm:block text-sm font-medium text-gray-700 truncate max-w-[100px]">
                        {{ Auth::user()->name }}
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="dropdownMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50 transform origin-top-right transition-all">
                    <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                        <p class="text-xs text-gray-500">Login sebagai</p>
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                        <i class="fa-solid fa-user mr-2 w-4"></i> Edit Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        <footer class="p-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} BPR Parinama Simfoni Indonesia. All rights reserved.
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- LOGIKA DROPDOWN PROFIL ---
            const dropdownBtn = document.getElementById('dropdownButton');
            const dropdownMenu = document.getElementById('dropdownMenu');

            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            // --- LOGIKA SIDEBAR MOBILE ---
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10); // Fade in effect
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => sidebarOverlay.classList.add('hidden'), 300); // Wait for transition
            }

            hamburgerBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openSidebar();
            });

            sidebarOverlay.addEventListener('click', closeSidebar);
            closeSidebarBtn.addEventListener('click', closeSidebar);

            // --- GLOBAL CLICK LISTENER (Untuk menutup dropdown/sidebar jika klik di luar) ---
            window.addEventListener('click', (e) => {
                // Tutup dropdown jika klik di luar
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });

        const laporanBtn = document.getElementById('laporanBtn');
        const laporanMenu = document.getElementById('laporanMenu');
        const laporanArrow = document.getElementById('laporanArrow');

        if (laporanBtn) {
            laporanBtn.addEventListener('click', () => {
                // Toggle Hidden Class
                laporanMenu.classList.toggle('hidden');

                // Rotate Arrow Icon
                laporanArrow.classList.toggle('rotate-180');
            });
        }
    </script>
    @stack('scripts')

</body>

</html>
