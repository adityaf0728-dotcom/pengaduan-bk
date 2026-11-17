<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Laporan Siswa (Guru BK)</title>
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
        
        <!-- SIDEBAR GURU BK -->
        <aside class="w-72 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-gray-200 dark:border-gray-700 shadow-lg flex flex-col justify-between">
            <div class="p-6">
                <h3 class="text-xl font-extrabold text-gray-800 dark:text-white mb-8 uppercase tracking-wider">Menu Guru BK</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ url('/gurubk/dashboard') }}" class="flex items-center gap-3 p-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-2xl transition-all duration-300">
                            <span class="text-xl">🏠</span>
                            <span class="font-bold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.laporan') }}" class="flex items-center gap-3 p-3 bg-blue-600/10 text-blue-700 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                            <span class="text-xl">📝</span>
                            <span class="font-semibold">Laporan Siswa</span>
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

        <!-- KONTEN UTAMA -->
        <main class="flex-1 overflow-y-auto p-8 lg:p-12">
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white">Daftar Laporan Siswa</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Klik pada nama siswa untuk membaca detail dan membalas keluhan.</p>
            </header>

            <div class="max-w-6xl space-y-4">
                @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800">
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800">
                        <span class="font-bold">Gagal!</span> {{ session('error') }}
                    </div>
                @endif

                @if(isset($laporans) && $laporans->count() > 0)
                    @foreach($laporans as $laporan)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-md rounded-2xl transition-all duration-300 hover:shadow-lg">
                            
                            <!-- HEADER YANG BISA DIKLIK -->
                            <div class="flex justify-between items-center p-6 cursor-pointer" onclick="toggleDetail({{ $laporan->id }})">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2 flex-wrap">
                                        👤 {{ $laporan->user->name ?? 'Siswa' }} 
                                        <span class="text-sm font-normal text-gray-500">(Kelas: {{ $laporan->user->kelas ?? '-' }})</span>
                                        
                                        <!-- BADGE KATEGORI -->
                                        <span class="ml-2 bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-semibold px-2.5 py-0.5 rounded-md border border-indigo-200 dark:border-indigo-700">
                                            Topik: {{ $laporan->kategori ?? 'Umum' }}
                                        </span>
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">🕒 {{ $laporan->created_at->format('d M Y - H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($laporan->status == 'menunggu')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">Aktif / Menunggu</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">Selesai</span>
                                    @endif
                                    <span class="text-gray-400 transform transition-transform duration-300" id="icon-{{ $laporan->id }}">▼</span>
                                </div>
                            </div>
                            
                            <!-- KONTEN DETAIL LENGKAP -->
                            <div id="detail-{{ $laporan->id }}" class="hidden border-t border-gray-100 dark:border-gray-700 p-6 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
                                
                                <!-- Laporan Utama dari Siswa -->
                                <div class="mb-6 flex justify-start">
                                    <div class="max-w-[80%]">
                                        <span class="text-xs font-semibold text-gray-500 ml-1">Laporan Awal - {{ $laporan->user->name }}</span>
                                        <div class="mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-2xl rounded-tl-none shadow-sm text-gray-800 dark:text-gray-200">
                                            {{ $laporan->isi_laporan }}

                                            <!-- TAMPILKAN LAMPIRAN LAPORAN AWAL SISWA -->
                                            @if($laporan->lampiran)
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">📎 Bukti Lampiran:</p>
                                                    @if(in_array(strtolower(pathinfo($laporan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                        <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $laporan->lampiran) }}" alt="Bukti Lampiran" class="max-w-xs sm:max-w-sm rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm hover:opacity-90 transition-opacity">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 text-sm bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 px-3 py-2 rounded-xl border border-blue-200 dark:border-blue-800 transition-colors">
                                                            📄 <u>Buka Dokumen Lampiran</u>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Riwayat Chat (Tanggapan) -->
                                @foreach($laporan->tanggapans as $tanggapan)
                                    @if($tanggapan->user->role == 'guru_bk')
                                        <!-- Pesan Guru BK (Kanan) -->
                                        <div class="mb-4 flex justify-end">
                                            <div class="max-w-[80%] text-right">
                                                <span class="text-xs font-semibold text-gray-500 mr-1">Anda (Guru BK) - {{ $tanggapan->created_at->format('H:i, d M') }}</span>
                                                <div class="mt-1 bg-blue-500 text-white p-4 rounded-2xl rounded-tr-none shadow-sm text-left inline-block">
                                                    {{ $tanggapan->pesan }}

                                                    <!-- TAMPILKAN LAMPIRAN CHAT GURU BK -->
                                                    @if($tanggapan->lampiran)
                                                        <div class="mt-3 pt-2 border-t border-blue-400/50">
                                                            @if(in_array(strtolower(pathinfo($tanggapan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $tanggapan->lampiran) }}" alt="Lampiran" class="max-w-xs rounded-xl border border-blue-300 shadow-sm hover:opacity-90 transition-opacity">
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 text-sm bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-xl transition-colors">
                                                                    📄 <u>Buka Lampiran Dokumen</u>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Pesan Siswa (Kiri) -->
                                        <div class="mb-4 flex justify-start">
                                            <div class="max-w-[80%]">
                                                <span class="text-xs font-semibold text-gray-500 ml-1">{{ $tanggapan->user->name }} - {{ $tanggapan->created_at->format('H:i, d M') }}</span>
                                                <div class="mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-2xl rounded-tl-none shadow-sm text-gray-800 dark:text-gray-200">
                                                    {{ $tanggapan->pesan }}

                                                    <!-- TAMPILKAN LAMPIRAN CHAT SISWA -->
                                                    @if($tanggapan->lampiran)
                                                        <div class="mt-3 pt-2 border-t border-gray-100 dark:border-gray-600">
                                                            @if(in_array(strtolower(pathinfo($tanggapan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $tanggapan->lampiran) }}" alt="Lampiran" class="max-w-xs rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm hover:opacity-90 transition-opacity">
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-3 py-2 rounded-xl transition-colors">
                                                                    📄 <u>Buka Lampiran Dokumen</u>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Form Balas Pesan -->
                                @if($laporan->status == 'menunggu')
                                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                                        <form action="{{ route('laporan.tanggapan', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                            @csrf
                                            <div class="flex gap-3">
                                                <div class="flex-1 flex gap-2">
                                                    <input type="text" name="pesan" class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Ketik balasan Anda di sini..." required>
                                                    
                                                    <!-- Tombol Lampiran File/Gambar -->
                                                    <label class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 flex items-center justify-center px-4 rounded-xl border border-gray-300 dark:border-gray-600 transition-colors" title="Lampirkan File/Gambar">
                                                        <span class="text-lg">📎</span>
                                                        <input type="file" name="lampiran" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" onchange="showFileName(this, {{ $laporan->id }})">
                                                    </label>
                                                </div>
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow-md transition-all">Kirim</button>
                                            </div>
                                            <!-- Info Nama File yang Dipilih -->
                                            <p id="file-name-{{ $laporan->id }}" class="text-xs text-blue-600 dark:text-blue-400 font-medium hidden"></p>
                                        </form>

                                        <!-- Tombol Selesaikan Kasus -->
                                        <form action="{{ route('guru.laporan.selesai', $laporan->id) }}" method="POST" class="mt-4 text-right">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin masalah ini sudah selesai? Siswa tidak akan bisa membalas lagi.')" class="text-sm text-gray-500 hover:text-green-600 dark:hover:text-green-400 font-semibold transition-colors underline">
                                                ✔ Tandai Laporan Selesai / Tutup Kasus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="mt-6 text-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl text-gray-500 dark:text-gray-400 text-sm font-semibold">
                                        Kasus ini telah ditutup dan diselesaikan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-md rounded-3xl p-12 text-center">
                        <div class="text-6xl mb-4">📭</div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Belum Ada Laporan</h3>
                        <p class="text-gray-500 dark:text-gray-400">Saat ini belum ada keluhan dari siswa.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Logika Mode Gelap/Terang
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

        // Logika Buka/Tutup Pesan (Accordion)
        function toggleDetail(id) {
            const detail = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            
            if(detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                detail.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Menampilkan nama file yang dipilih sebelum dikirim
        function showFileName(input, id) {
            const label = document.getElementById('file-name-' + id);
            if (input.files && input.files.length > 0) {
                label.textContent = '📎 File siap dikirim: ' + input.files[0].name;
                label.classList.remove('hidden');
            } else {
                label.classList.add('hidden');
            }
        }
    </script>
</body>
</html>