<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Kelola Pengguna</title>
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
                        <!-- Menu ini sekarang aktif -->
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

        <!-- KONTEN UTAMA: TABEL PENGGUNA -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white">{{ $title ?? 'Kelola Pengguna' }}</h2>
            </header>

            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl rounded-3xl overflow-hidden">
                
                <!-- Header Tabel & Tombol Tambah -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Akun Sistem</h3>
                    <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        + Tambah Pengguna
                    </a>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-sm uppercase tracking-wider">
                                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">No</th>
                                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Nama</th>
                                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Email</th>
                                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-center">Peran</th>
                                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-200">
                            @foreach($users as $index => $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="p-4 border-b border-gray-100 dark:border-gray-700">{{ $index + 1 }}</td>
                                <td class="p-4 border-b border-gray-100 dark:border-gray-700 font-bold">{{ $user->name }}</td>
                                <td class="p-4 border-b border-gray-100 dark:border-gray-700">{{ $user->email }}</td>
                                <td class="p-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' : '' }}
                                        {{ $user->role === 'guru_bk' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                        {{ $user->role === 'siswa' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="p-4 border-b border-gray-100 dark:border-gray-700 text-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-block p-2 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-lg hover:bg-yellow-200 transition-colors" title="Edit">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-lg hover:bg-red-200 transition-colors" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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