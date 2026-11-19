<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pengaduan Siswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* Animasi Pergerakan Cahaya Latar (Ambient Background) */
        @keyframes blob-bounce {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(40px, -60px) scale(1.1); }
            66% { transform: translate(-30px, 30px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob-bounce 15s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 4s; }
        .animation-delay-4000 { animation-delay: 8s; }
    </style>
</head>
<body class="relative antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-500 min-h-screen overflow-x-hidden selection:bg-blue-500 selection:text-white">

    <!-- Latar Belakang Ambient Glow Baru yang Lebih Elegan (Pengganti Grid) -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[500px] h-[500px] rounded-full bg-blue-400/20 dark:bg-blue-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob"></div>
        <div class="absolute top-[10%] -right-[10%] w-[600px] h-[600px] rounded-full bg-indigo-400/20 dark:bg-indigo-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[550px] h-[550px] rounded-full bg-sky-400/20 dark:bg-sky-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Konten Utama -->
    <div class="relative z-10 flex flex-col min-h-screen">
        
        <!-- Navbar Glassmorphism -->
        <nav class="w-full bg-white/70 dark:bg-slate-950/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 transition-colors duration-300 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3"> 
                <div class="flex justify-between items-center">
                    
                    <!-- LOGO & TITLE (Sudah Diperbesar) -->
                    <div class="flex-shrink-0 flex items-center gap-4">
                        <img src="{{ asset('images/SmansabaLogo.png') }}" alt="Logo SMANSABA" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-sm hover:scale-105 transition-transform duration-300">
                        <span class="font-extrabold text-2xl sm:text-3xl tracking-tight text-slate-900 dark:text-white">Ruang BK SMAN 1 Bangkalan</span>
                    </div>
                    
                    <!-- Tombol Navigasi Kanan -->
                    <div class="flex items-center gap-4 sm:gap-6">
                        <button onclick="toggleTheme()" class="p-2.5 text-slate-500 hover:bg-slate-200/70 dark:text-slate-400 dark:hover:bg-slate-800/70 rounded-full transition-all duration-300" aria-label="Toggle Dark Mode">
                            <svg id="theme-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                        </button>

                        @if (Route::has('login'))
                            @auth
                                <!-- PERBAIKAN LOGIKA REDIRECT DI SINI -->
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dashboard Admin</a>
                                @elseif(auth()->user()->role === 'guru_bk')
                                    <a href="{{ route('gurubk.dashboard') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dashboard Guru BK</a>
                                @else
                                    <a href="{{ route('siswa.dashboard') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dashboard Siswa</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-full shadow-md shadow-blue-500/20 hover:shadow-blue-500/40 transition-all duration-300 transform hover:-translate-y-0.5">Masuk</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-10 items-center w-full">
                
                <!-- Teks Kiri -->
                <div class="text-left space-y-7">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-blue-50/80 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-bold shadow-sm backdrop-blur-md border border-blue-100 dark:border-blue-800/50">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                        </span>
                        Layanan Pelaporan Siswa SMAN 1 Bangkalan
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-[4rem] font-black tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                        Berani Bicara,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-600 dark:from-blue-400 dark:via-indigo-400 dark:to-sky-400">Kami Siap Mendengar.</span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed font-medium">
                        Sampaikan keluhan, masalah perundungan, atau kendala belajar Anda. Kami di Bimbingan Konseling (BK) menjamin kerahasiaan penuh setiap laporan demi kenyamanan dan keamanan Anda.
                    </p>
                    
                    <div class="pt-2">
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-3 px-8 py-4 text-base font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group">
                            Mulai Konsultasi & Laporan
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Footage Kanan -->
                <div class="relative w-full max-w-lg mx-auto lg:max-w-none">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50 dark:shadow-black/50 ring-1 ring-slate-900/5 dark:ring-white/10 group">
                        <img src="{{ asset('images/BK.png') }}" alt="Sesi Konseling" class="w-full h-[400px] lg:h-[500px] object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-80"></div>
                    </div>
                    
                    <!-- Floating Badge Privilege -->
                    <div class="absolute -bottom-6 -left-4 sm:-left-8 bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 sm:p-5 rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/50 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <p class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Privasi Terjamin</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Data pelapor 100% aman</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Logika Dark Mode -->
    <script>
        const themeIcon = document.getElementById('theme-icon');
        const sunIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />`;
        const moonIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />`;

        function updateIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.innerHTML = sunIcon;
            } else {
                themeIcon.innerHTML = moonIcon;
            }
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateIcon();
        }

        updateIcon();
    </script>
</body>
</html>