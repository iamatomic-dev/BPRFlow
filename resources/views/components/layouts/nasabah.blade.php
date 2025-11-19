<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Nasabah' }} | BPR Parinama Simfoni Indonesia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#0d1b2a] text-white fixed top-0 left-0 h-screen">
        <div class="p-6 text-center border-b border-gray-700 flex items-center justify-center gap-2">
            <img src="{{ Vite::asset('resources/images/Logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
            <div class="text-left">
                <h2 class="text-lg font-bold leading-tight">BPR Parinama</h2>
                <p class="text-xs">Simfoni Indonesia</p>
            </div>
        </div>

        <nav class="mt-6">
            <a href="{{ route('nasabah.dashboard') }}"
                class="block px-6 py-3 hover:bg-[#1b263b] transition {{ request()->routeIs('nasabah.dashboard') ? 'bg-[#1b263b]' : '' }}">
                Beranda
            </a>
            <a href="{{ route('pengajuan.step1') }}"
                class="block px-6 py-3 hover:bg-[#1b263b] transition {{ request()->routeIs('pengajuan.*') ? 'bg-[#1b263b]' : '' }}">
                Pengajuan Kredit
            </a>
            <a href="{{ route('riwayat.index') }}"
                class="block px-6 py-3 hover:bg-[#1b263b] transition {{ request()->routeIs('riwayat.*') ? 'bg-[#1b263b]' : '' }}">
                Status Pengajuan
            </a>
            <a href="{{ route('simulasi.index') }}"
                class="block px-6 py-3 hover:bg-[#1b263b] transition {{ request()->routeIs('simulasi.index') ? 'bg-[#1b263b]' : '' }}">
                Simulasi Kredit
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header
        class="sticky top-0 left-64 w-[calc(100%-16rem)] bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            {{ $header ?? '' }}
        </div>

        <div class="relative">
            <button id="dropdownButton"
                class="flex items-center gap-2 px-4 py-2 bg-gray-50 border rounded-lg shadow hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ Auth::user()->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="dropdownMenu"
                class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg overflow-hidden z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Edit
                    Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        {{ $slot }}
    </main>

    <script>
        // Dropdown profile
        const btn = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        btn?.addEventListener('click', () => menu.classList.toggle('hidden'));
        window.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
