<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center w-full">
        
        <!-- Sisi Kiri: Brand & Deskripsi (Gaya FB) -->
        <div class="lg:col-span-7 text-left space-y-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/SmansabaLogo.png') }}" alt="Logo SMANSABA" class="w-16 h-16 sm:w-20 sm:h-20 object-contain drop-shadow-md">
                <div class="flex flex-col">
                    <h1 class="font-black text-3xl sm:text-4xl text-slate-900 dark:text-white leading-none">Ruang BK</h1>
                    <span class="font-bold text-sm sm:text-base text-blue-600 dark:text-blue-400 tracking-wider uppercase mt-1">SMAN 1 Bangkalan</span>
                </div>
            </div>

            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-slate-100 leading-tight">
                Masuk ke akun Anda untuk menyampaikan aspirasi & laporan.
            </h2>

            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed font-medium">
                Satu langkah untuk lingkungan sekolah yang lebih aman dan nyaman. Privasi dan kerahasiaan identitas Anda terjamin sepenuhnya.
            </p>
        </div>

        <!-- Sisi Kanan: Form Card Login -->
        <div class="lg:col-span-5 w-full max-w-md mx-auto">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl p-8 sm:p-10 rounded-3xl shadow-2xl shadow-slate-300/50 dark:shadow-black/50 border border-white/20 dark:border-slate-800">
                
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Login Pengaduan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masukkan kredensial yang telah terdaftar.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Field Input NIS/Email (Ukuran Diperbesar) -->
                    <div>
                        <label for="email" class="block font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">NIS / Email</label>
                        <input id="email" 
                               class="block w-full px-4 py-3.5 text-base rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder:text-slate-400" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               placeholder="Masukkan NIS atau Email"
                               required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>

                    <!-- Field Input Password (Ukuran Diperbesar) -->
                    <div>
                        <label for="password" class="block font-semibold text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Kata Sandi</label>
                        <input id="password" 
                               class="block w-full px-4 py-3.5 text-base rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder:text-slate-400" 
                               type="password" 
                               name="password" 
                               placeholder="Masukkan Kata Sandi"
                               required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                    </div>

                    <!-- Checkbox Ingat Saya & Lupa Password -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-slate-800" name="remember">
                            <span class="ms-2 text-xs font-semibold text-slate-600 dark:text-slate-400">Ingat saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('password.request') }}">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login (Besar dan Tebal) -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 px-6 rounded-xl font-bold text-base text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                            Masuk Sekarang
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-guest-layout>