<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Pelaporan Siswa</title>
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
        <!-- BAGIAN KIRI: Sidebar Siswa -->
        <aside class="w-72 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-gray-200 dark:border-gray-700 shadow-lg flex flex-col justify-between">
            <div class="p-6">
                <h3 class="text-xl font-extrabold text-gray-800 dark:text-white mb-8 uppercase tracking-wider">Menu Siswa</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 p-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-2xl transition-all duration-300">
                            <span class="text-xl">🏠</span>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.laporan') }}" class="flex items-center gap-3 p-3 bg-blue-600/10 text-blue-700 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                            <span class="text-xl">📝</span>
                            <span class="font-bold">Pelaporan</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- BAGIAN BAWAH SIDEBAR: Tema & Logout -->
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

        <!-- BAGIAN KANAN: Konten Utama -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white">Layanan Pelaporan BK</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Ceritakan masalah atau keluhan Anda dengan aman. Laporan ini akan dibaca langsung oleh Guru BK.</p>
            </header>

            <div class="max-w-4xl space-y-8">
                <!-- Pesan Sukses / Error -->
                @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                        <span class="font-bold">Gagal!</span> {{ session('error') }}
                    </div>
                @endif

                <!-- Form Buat Topik Laporan Baru -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Buat Laporan Baru</h3>
                    <form action="{{ route('siswa.laporan.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="isi_laporan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Laporan / Keluhan</label>
                            <textarea id="isi_laporan" name="isi_laporan" rows="4" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700/50 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tuliskan keluhan, masalah belajar, atau hal lain yang ingin diceritakan..." required></textarea>
                            @error('isi_laporan')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm px-6 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all shadow-md hover:shadow-lg">
                            Kirim Laporan
                        </button>
                    </form>
                </div>

                <!-- Riwayat Laporan Saya (Gelembung Chat) -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Riwayat Laporan Saya</h3>
                    
                    @if(isset($laporans) && $laporans->count() > 0)
                        <div class="space-y-4">
                            @foreach($laporans as $laporan)
                                <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 rounded-2xl transition-all duration-300 hover:shadow-md">
                                    
                                    <!-- HEADER YANG BISA DIKLIK (Sama seperti guru) -->
                                    <div class="flex justify-between items-center p-6 cursor-pointer" onclick="toggleDetail({{ $laporan->id }})">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                                Topik Laporan
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">🕒 {{ $laporan->created_at->format('d M Y - H:i') }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            @if($laporan->status == 'menunggu')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">Menunggu Balasan</span>
                                            @elseif($laporan->status == 'diproses')
                                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">Diproses</span>
                                            @else
                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">Selesai</span>
                                            @endif
                                            <span class="text-gray-400 transform transition-transform duration-300" id="icon-{{ $laporan->id }}">▼</span>
                                        </div>
                                    </div>
                                    
                                    <!-- KONTEN DETAIL LENGKAP -->
                                    <div id="detail-{{ $laporan->id }}" class="hidden border-t border-gray-200 dark:border-gray-600 p-6 bg-white dark:bg-gray-800/50 rounded-b-2xl">
                                        
                                        <!-- Laporan Utama dari Siswa -->
                                        <div class="mb-6 flex justify-end">
                                            <div class="max-w-[80%] text-right">
                                                <span class="text-xs font-semibold text-gray-500 mr-1">Anda (Laporan Awal)</span>
                                                <div class="mt-1 bg-blue-500 text-white p-4 rounded-2xl rounded-tr-none shadow-sm text-left inline-block">
                                                    {{ $laporan->isi_laporan }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Riwayat Chat (Tanggapan) -->
                                        @if(isset($laporan->tanggapans))
                                            @foreach($laporan->tanggapans as $tanggapan)
                                                @if($tanggapan->user->role == 'guru_bk')
                                                    <!-- Pesan Guru BK (Kiri bagi Siswa) -->
                                                    <div class="mb-4 flex justify-start">
                                                        <div class="max-w-[80%]">
                                                            <span class="text-xs font-semibold text-gray-500 ml-1">Guru BK - {{ $tanggapan->created_at->format('H:i, d M') }}</span>
                                                            <div class="mt-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-4 rounded-2xl rounded-tl-none shadow-sm text-left">
                                                                {{ $tanggapan->pesan }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Pesan Siswa (Kanan bagi Siswa) -->
                                                    <div class="mb-4 flex justify-end">
                                                        <div class="max-w-[80%] text-right">
                                                            <span class="text-xs font-semibold text-gray-500 mr-1">Anda - {{ $tanggapan->created_at->format('H:i, d M') }}</span>
                                                            <div class="mt-1 bg-blue-500 text-white p-4 rounded-2xl rounded-tr-none shadow-sm text-left inline-block">
                                                                {{ $tanggapan->pesan }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                        <!-- Form Balas Pesan (Hanya jika belum selesai) -->
                                        @if($laporan->status != 'selesai')
                                            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                                                <form action="{{ route('laporan.tanggapan', $laporan->id) }}" method="POST" class="flex gap-4">
                                                    @csrf
                                                    <input type="text" name="pesan" class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Ketik balasan Anda..." required>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow-md transition-all">Kirim</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="mt-6 text-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl text-gray-500 dark:text-gray-400 text-sm font-semibold">
                                                Kasus ini telah ditutup oleh Guru BK. Anda tidak dapat membalas lagi.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada riwayat laporan.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Script Toggle Tema & Accordion -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });

        // Logika Buka/Tutup Pesan (Accordion)
        function toggleDetail(id) {
            const detail = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            
            if(detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)'; // Putar ikon panah ke atas
            } else {
                detail.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)'; // Kembalikan ikon panah ke bawah
            }
        }
    </script>
</body>
</html>