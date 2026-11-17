<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pengguna - Admin</title>
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
    <div class="flex min-h-screen items-center justify-center p-4 lg:p-8">
        
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-2xl rounded-[2rem] p-8 lg:p-10 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-gradient-to-br from-blue-500/10 to-purple-500/10 blur-3xl pointer-events-none"></div>

            <div class="flex justify-between items-center mb-8 relative z-10">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 dark:text-white tracking-tight">Tambah Pengguna</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Lengkapi formulir di bawah untuk mendaftarkan akun baru.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-5 py-2.5 rounded-xl transition-all shadow-sm">
                    <span>🔙</span> Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/30 dark:border-red-800 relative z-10">
                    <div class="flex items-center gap-2 mb-2 text-red-700 dark:text-red-400 font-bold">
                        <span>⚠️</span> Gagal menyimpan data!
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ========================================== -->
            <!-- FORM IMPORT EXCEL KHUSUS SISWA MASAL       -->
            <!-- ========================================== -->
            <div class="mb-8 p-6 rounded-2xl bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 relative z-10">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">📥 Import Data Siswa (Excel)</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Gunakan format baris pertama: <strong>nama, email, nisn, kelas, angkatan</strong>. Password akan otomatis menggunakan NISN masing-masing.</p>
                
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4">
                    @csrf
                    <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
                    
                    <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex-shrink-0">
                        Import Excel
                    </button>
                </form>
            </div>

            <!-- GARIS PEMISAH -->
            <div class="relative flex items-center py-5 z-10">
                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                <span class="flex-shrink-0 mx-4 text-gray-400 text-sm font-bold tracking-wider">ATAU TAMBAH MANUAL</span>
                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
            </div>
            <!-- ========================================== -->

            <!-- FORM MANUAL (TETAP SEPERTI SEMULA) -->
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6 relative z-10" autocomplete="off">
                @csrf
                
                <!-- Baris 1: Nama & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Alamat Email (Untuk Login)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email" autocomplete="new-password" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                    </div>
                </div>
                
                <!-- Baris 2: Role -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Hak Akses (Role)</label>
                        <select name="role" id="roleSelect" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm cursor-pointer">
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>🎓 Siswa</option>
                            <option value="guru_bk" {{ old('role') == 'guru_bk' ? 'selected' : '' }}>👨‍🏫 Guru BK</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 3: Password (Wajib untuk semua) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 my-6"></div>

                <!-- KHUSUS SISWA (Akan disembunyikan jika role Guru dipilih) -->
                <div id="siswaFields" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">NISN (Siswa)</label>
                            <input type="text" name="nisn" id="nisnInput" value="{{ old('nisn') }}" placeholder="Nomor Induk" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: X IPA 1" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Angkatan Masuk</label>
                            <input type="number" name="angkatan" value="{{ old('angkatan') }}" placeholder="Contoh: 2024" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white px-5 py-4 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                        </div>
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-lg py-4 px-6 rounded-2xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Simpan Pengguna Manual
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Logika Toggle Tampilan berdasarkan Role -->
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const siswaFields = document.getElementById('siswaFields');
        const nisnInput = document.getElementById('nisnInput');

        // Fungsi untuk mengatur tampilan form
        function toggleFields() {
            if (roleSelect.value === 'siswa') {
                siswaFields.classList.remove('hidden');
                nisnInput.setAttribute('required', 'required'); // NISN wajib untuk siswa
            } else {
                siswaFields.classList.add('hidden');
                nisnInput.removeAttribute('required'); // NISN tidak wajib untuk guru
            }
        }

        // Panggil saat halaman pertama dimuat
        toggleFields();

        // Panggil saat pilihan role diubah
        roleSelect.addEventListener('change', toggleFields);
    </script>
</body>
</html>