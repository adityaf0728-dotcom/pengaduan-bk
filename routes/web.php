<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $adminCount = \App\Models\User::where('role', 'admin')->count();
        $guruCount = \App\Models\User::where('role', 'guru_bk')->count();
        $siswaCount = \App\Models\User::where('role', 'siswa')->count();
        return view('admin.dashboard', compact('adminCount', 'guruCount', 'siswaCount'));
    })->name('admin.dashboard');
    
    Route::resource('users', AdminUserController::class)->names('admin.users');
});

// ==========================================
// RUTE KHUSUS GURU BK
// ==========================================
Route::middleware(['auth', 'role:guru_bk'])->prefix('gurubk')->group(function () {
    
    // PERBAIKAN: Kembalikan nama rute menjadi 'gurubk.dashboard'
    Route::get('/dashboard', [LaporanController::class, 'dashboardGuru'])->name('gurubk.dashboard');

    Route::get('/laporan', [LaporanController::class, 'indexGuru'])->name('guru.laporan');
    
    // Rute untuk menandai selesai
    Route::put('/laporan/{id}/selesai', [LaporanController::class, 'tandaiSelesai'])->name('guru.laporan.selesai');
});

// ==========================================
// RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');

    Route::get('/pelaporan', [LaporanController::class, 'create'])->name('siswa.laporan');
    Route::post('/pelaporan', [LaporanController::class, 'store'])->name('siswa.laporan.store');
});

// ==========================================
// RUTE GLOBAL (UNTUK FITUR BALAS PESAN / CHAT)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::post('/laporan/{id}/tanggapan', [LaporanController::class, 'kirimTanggapan'])->name('laporan.tanggapan');
});

// ==========================================
// RUTE PROFIL
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';