<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index()
    {
        // Menampilkan semua user selain admin
        $users = User::where('role', '!=', 'admin')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email sekarang wajib karena dipakai untuk login
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:guru_bk,siswa'],
            // Password sekarang wajib untuk SEMUA akun (Guru & Siswa)
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // Validasi khusus siswa
            'nisn' => ['nullable', 'required_if:role,siswa', 'string', 'unique:users'],
            'kelas' => ['nullable', 'required_if:role,siswa', 'string'],
            'angkatan' => ['nullable', 'required_if:role,siswa', 'integer'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Langsung pakai input password dari form
            
            // Kolom khusus siswa, jika guru maka null
            'nisn' => $request->role === 'siswa' ? $request->nisn : null,
            'kelas' => $request->role === 'siswa' ? $request->kelas : null,
            'angkatan' => $request->role === 'siswa' ? $request->angkatan : null,
            
            // Jika database Anda terlanjur mewajibkan username (not null), kita isi sementara dengan memotong nama email. 
            // Nanti bisa Anda hapus baris ini jika kolom username di database sudah di-drop.
            'username' => explode('@', $request->email)[0] . rand(100, 999), 
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Pengecualian unique untuk data user yang sedang diedit
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:guru_bk,siswa'],
            
            'nisn' => ['nullable', 'required_if:role,siswa', 'string', 'unique:users,nisn,' . $user->id],
            'kelas' => ['nullable', 'required_if:role,siswa', 'string'],
            'angkatan' => ['nullable', 'required_if:role,siswa', 'integer'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nisn' => $request->role === 'siswa' ? $request->nisn : null,
            'kelas' => $request->role === 'siswa' ? $request->kelas : null,
            'angkatan' => $request->role === 'siswa' ? $request->angkatan : null,
        ]);

        // Update password secara manual
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed']
            ]);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}