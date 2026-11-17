<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Siswa</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-72 bg-[#1e293b] border-r border-gray-700 shadow-lg flex flex-col justify-between">
            <div class="p-6">
                <h3 class="text-lg font-extrabold text-white mb-8 uppercase tracking-wider">Menu Siswa</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-all duration-300">
                            <span>🏠</span> <span class="font-semibold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.laporan') }}" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-all duration-300">
                            <span>📝</span> <span class="font-semibold">Pelaporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.profil') }}" class="flex items-center gap-3 p-3 bg-blue-600/20 text-blue-400 rounded-xl transition-all duration-300 shadow-sm">
                            <span>👤</span> <span class="font-bold">Profil</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="p-6 space-y-4">
                <button id="theme-toggle" class="w-full flex items-center justify-center gap-2 p-3 text-gray-400 bg-gray-800 rounded-xl hover:bg-gray-700 transition-all duration-300">
                    <span id="theme-toggle-dark-icon" class="hidden text-sm font-semibold">🌙 Mode Gelap</span>
                    <span id="theme-toggle-light-icon" class="hidden text-sm font-semibold">☀️ Mode Terang</span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 bg-red-900/30 text-red-400 rounded-xl hover:bg-red-800 hover:text-white transition-all duration-300">
                        <span class="font-bold">🚪 Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold text-white">Profil Saya</h2>
                <p class="text-gray-400 mt-2">Lihat informasi akun Anda dan ubah kata sandi di sini.</p>
            </header>

            <div class="max-w-4xl space-y-6">
                
                <!-- Notifikasi Berhasil -->
                @if (session('status'))
                    <div class="p-4 mb-4 text-sm text-green-400 rounded-lg bg-green-900/30 border border-green-800" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Bagian Informasi Akun (Hanya Baca) -->
                <div class="bg-[#1e293b] border border-gray-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Informasi Siswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nama Lengkap</label>
                            <div class="w-full p-3 bg-[#0f172a] text-gray-300 rounded-xl border border-gray-700">{{ Auth::user()->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                            <div class="w-full p-3 bg-[#0f172a] text-gray-300 rounded-xl border border-gray-700">{{ Auth::user()->email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Kelas</label>
                            <div class="w-full p-3 bg-[#0f172a] text-gray-300 rounded-xl border border-gray-700">{{ Auth::user()->kelas ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Angkatan</label>
                            <div class="w-full p-3 bg-[#0f172a] text-gray-300 rounded-xl border border-gray-700">{{ Auth::user()->angkatan ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Ubah Password -->
                <div class="bg-[#1e293b] border border-gray-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Ubah Kata Sandi</h3>
                    <form method="POST" action="{{ route('siswa.profil.password') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-400 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="w-full p-3 bg-[#0f172a] text-white rounded-xl border border-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @error('current_password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="w-full p-3 bg-[#0f172a] text-white rounded-xl border border-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-3 bg-[#0f172a] text-white rounded-xl border border-gray-700 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300">
                                Simpan Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Logika Toggle Tema (sama seperti sebelumnya)
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        var themeToggleBtn = document.getElementById('theme-toggle');

        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

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