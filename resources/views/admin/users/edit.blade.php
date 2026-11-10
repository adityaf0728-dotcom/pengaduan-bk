<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengguna - Admin</title>
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
        <main class="flex-1 overflow-y-auto p-8 lg:p-12 w-full flex justify-center items-center">
            <div class="w-full max-w-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl rounded-3xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">Edit Pengguna</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-xl transition-colors">Kembali</a>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Peran (Role)</label>
                        <select name="role" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                            <option value="siswa" {{ $user->role === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru_bk" {{ $user->role === 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                        </select>
                    </div>
                    <div class="p-4 bg-gray-100 dark:bg-gray-700/50 rounded-xl space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">* Kosongkan password jika tidak ingin mengubahnya.</p>
                        <div>
                            <label class="block text-sm font-medium mb-1">Password Baru</label>
                            <input type="password" name="password" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all mt-4">Simpan Perubahan</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>