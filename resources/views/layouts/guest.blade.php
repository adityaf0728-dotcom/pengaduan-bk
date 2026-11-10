<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Ruang BK SMAN 1 Bangkalan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
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
<body class="relative antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-500 min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-blue-500 selection:text-white">

    <!-- Latar Belakang Ambient Glow (Sama dengan Welcome) -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[500px] h-[500px] rounded-full bg-blue-400/20 dark:bg-blue-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob"></div>
        <div class="absolute top-[10%] -right-[10%] w-[600px] h-[600px] rounded-full bg-indigo-400/20 dark:bg-indigo-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[550px] h-[550px] rounded-full bg-sky-400/20 dark:bg-sky-600/10 blur-[100px] mix-blend-multiply dark:mix-blend-screen opacity-80 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Header Tombol Dark Mode & Kembali -->
    <div class="relative z-20 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>

        <button onclick="toggleTheme()" class="p-2.5 text-slate-500 hover:bg-slate-200/70 dark:text-slate-400 dark:hover:bg-slate-800/70 rounded-full transition-all duration-300" aria-label="Toggle Dark Mode">
            <svg id="theme-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
        </button>
    </div>

    <!-- Konten Utama Slot Login -->
    <main class="relative z-10 flex-grow flex items-center justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <footer class="relative z-10 py-6 text-center text-xs text-slate-400 dark:text-slate-600">
        &copy; {{ date('Y') }} SMAN 1 Bangkalan. Ruang Bimbingan Konseling.
    </footer>

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