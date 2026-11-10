<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - Dashboard Siswa</title>
    
    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Deteksi Tema Awal -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- BAGIAN KIRI: Sidebar -->
        <aside class="w-72 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-gray-200 dark:border-gray-700 shadow-lg flex flex-col justify-between">
            <div class="p-6">
                <h3 class="text-xl font-extrabold text-gray-800 dark:text-white mb-8 uppercase tracking-wider">Menu Siswa</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 p-3 bg-blue-600/10 text-blue-700 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                            <span class="text-xl">🏠</span>
                            <span class="font-bold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <!-- Link Pelaporan & Riwayat yang sudah diperbaiki -->
                        <a href="{{ route('siswa.laporan') }}" class="flex items-center gap-3 p-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-2xl transition-all duration-300">
                            <span class="text-xl">📝</span>
                            <span class="font-semibold">Pelaporan & Riwayat</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- BAGIAN BAWAH SIDEBAR: Tema & Logout -->
            <div class="p-6 space-y-4 border-t border-gray-200 dark:border-gray-700">
                
                <!-- Tombol Mode Gelap/Terang -->
                <button id="theme-toggle" type="button" class="w-full flex items-center justify-center gap-2 p-3 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                    <span id="theme-toggle-dark-icon" class="hidden text-sm font-semibold">🌙 Mode Gelap</span>
                    <span id="theme-toggle-light-icon" class="hidden text-sm font-semibold">☀️ Mode Terang</span>
                </button>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-2xl hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300">
                        <span class="text-xl">🚪</span>
                        <span class="font-bold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- BAGIAN KANAN: Konten Utama -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white">Dashboard Siswa</h2>
            </header>

            <div class="space-y-8 max-w-7xl">
                <!-- Grid Card Informasi Siswa -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- PERBAIKAN: Card yang sebelumnya NISN diubah menjadi Nama -->
                    <div class="bg-gradient-to-br from-blue-500/90 to-indigo-600/90 backdrop-blur-md shadow-xl rounded-3xl p-6 text-white transform transition-all duration-300 hover:-translate-y-2 hover:scale-105 cursor-default">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Nama</h3>
                            <div class="p-3 bg-white/20 rounded-2xl">👤</div>
                        </div>
                        <!-- Menggunakan truncate agar jika nama terlalu panjang tidak merusak layout -->
                        <p class="text-3xl font-black drop-shadow-md truncate" title="{{ Auth::user()->name }}">{{ Auth::user()->name ?? '-' }}</p>
                        <p class="text-sm mt-3 opacity-90 font-medium">Nama Lengkap Siswa</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-500/90 to-teal-600/90 backdrop-blur-md shadow-xl rounded-3xl p-6 text-white transform transition-all duration-300 hover:-translate-y-2 hover:scale-105 cursor-default">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Kelas</h3>
                            <div class="p-3 bg-white/20 rounded-2xl">🏫</div>
                        </div>
                        <p class="text-4xl font-black drop-shadow-md">{{ Auth::user()->kelas ?? '-' }}</p>
                        <p class="text-sm mt-3 opacity-90 font-medium">Ruang Kelas Saat Ini</p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-500/90 to-orange-600/90 backdrop-blur-md shadow-xl rounded-3xl p-6 text-white transform transition-all duration-300 hover:-translate-y-2 hover:scale-105 cursor-default">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Angkatan</h3>
                            <div class="p-3 bg-white/20 rounded-2xl">📅</div>
                        </div>
                        <p class="text-4xl font-black drop-shadow-md">{{ Auth::user()->angkatan ?? '-' }}</p>
                        <p class="text-sm mt-3 opacity-90 font-medium">Tahun Masuk</p>
                    </div>
                </div>

                <!-- Papan Sambutan -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl rounded-3xl p-8">
                    <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-3">Selamat Datang, {{ Auth::user()->name }}! 🎓</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">Ini adalah halaman dashboard khusus siswa. Di sini Anda dapat melakukan pelaporan dengan Guru BK dengan aman, dan melihat riwayat pelaporan.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Logika Toggle Tema -->
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        var themeToggleBtn = document.getElementById('theme-toggle');

        // Atur icon yang tampil pertama kali
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        // Fungsi klik tombol
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme') === 'light' || (!('color-theme' in localStorage) && !document.documentElement.classList.contains('dark'))) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        });
    </script>
</body>
</html>