<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Kelas - {{ config('app.name', 'Laravel') }}</title>
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
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-72 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-gray-200 dark:border-gray-700 shadow-lg flex flex-col justify-between">
            <div class="p-6">
                <h3 class="text-xl font-extrabold text-gray-800 dark:text-white mb-8 uppercase tracking-wider">Navigasi</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-2xl transition-all duration-300">
                            <span class="text-xl">🏠</span>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-3 bg-blue-600/10 text-blue-700 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                            <span class="text-xl">👥</span>
                            <span class="font-bold">Kelola Pengguna</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-6 space-y-4 border-t border-gray-200 dark:border-gray-700">
                <button id="theme-toggle" type="button" class="w-full flex items-center justify-center gap-2 p-3 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                    <span id="theme-toggle-dark-icon" class="hidden text-sm font-semibold">🌙 Mode Gelap</span>
                    <span id="theme-toggle-light-icon" class="hidden text-sm font-semibold">☀️ Mode Terang</span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-2xl hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-300">
                        <span class="text-xl">🚪</span>
                        <span class="font-bold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN UTAMA: PILIH KELAS -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 dark:text-white tracking-tight">Angkatan {{ $angkatan }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Pilih kelas untuk melihat data siswa.</p>
                </div>
                
                <!-- [TAMBAHAN BARU] Tombol Aksi Hapus & Kembali -->
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('admin.users.destroyAngkatan', $angkatan) }}" method="POST" onsubmit="return confirm('PERINGATAN BAHAYA:\n\nApakah Anda yakin ingin menghapus SELURUH akun siswa pada Angkatan {{ $angkatan }}?\n\nSemua data siswa di angkatan ini tidak dapat dikembalikan!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-md flex items-center gap-2">
                            <span>🗑️</span> Hapus Angkatan {{ $angkatan }}
                        </button>
                    </form>

                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-sm flex items-center">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @if(isset($kelasList))
                    @foreach($kelasList as $kelas)
                    <a href="{{ route('admin.users.kelas', ['angkatan' => $angkatan, 'kelas' => $kelas]) }}" class="flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-500 dark:hover:border-blue-500 transform hover:-translate-y-1 transition-all group">
                        <div class="text-center">
                            <h3 class="text-gray-800 dark:text-white font-black text-lg">{{ $kelas }}</h3>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </main>
    </div>

    <script>
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