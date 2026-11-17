<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class AdminUserController extends Controller
{
    public function index()
    {
        // Level 1: Ambil data angkatan unik dan jumlah staff
        $angkatanSiswa = User::where('role', 'siswa')
            ->whereNotNull('angkatan')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'asc')
            ->pluck('angkatan');

        $staffCount = User::whereIn('role', ['admin', 'guru_bk'])->count();

        return view('admin.users.index', compact('angkatanSiswa', 'staffCount'));
    }

    public function showStaff()
    {
        // Level 3 (Khusus Staff)
        $users = User::whereIn('role', ['admin', 'guru_bk'])->latest()->get();
        $title = "Data Pengelola (Admin & Guru BK)";
        
        return view('admin.users.list', compact('users', 'title'));
    }

    public function showAngkatan($angkatan)
    {
        // Level 2: Ambil daftar kelas unik berdasarkan angkatan
        $kelasList = User::where('role', 'siswa')
            ->where('angkatan', $angkatan)
            ->whereNotNull('kelas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas', 'asc')
            ->pluck('kelas');

        return view('admin.users.angkatan', compact('angkatan', 'kelasList'));
    }

    public function showKelas($angkatan, $kelas)
    {
        // Level 3 (Khusus Siswa per Kelas)
        $users = User::where('role', 'siswa')
            ->where('angkatan', $angkatan)
            ->where('kelas', $kelas)
            ->latest()
            ->get();
        $title = "Data Siswa - Kelas $kelas (Angkatan $angkatan)";
        
        return view('admin.users.list', compact('users', 'title'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:guru_bk,siswa'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nisn' => ['nullable', 'required_if:role,siswa', 'string', 'unique:users'],
            'kelas' => ['nullable', 'required_if:role,siswa', 'string'],
            'angkatan' => ['nullable', 'required_if:role,siswa', 'integer'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), 
            'nisn' => $request->role === 'siswa' ? $request->nisn : null,
            'kelas' => $request->role === 'siswa' ? $request->kelas : null,
            'angkatan' => $request->role === 'siswa' ? $request->angkatan : null,
            'username' => explode('@', $request->email)[0] . rand(100, 999), 
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('admin.users.index')->with('success', 'Data siswa dari Excel berhasil diimport!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
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

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed']
            ]);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    // [TAMBAHAN BARU] Fungsi Hapus Seluruh Angkatan
    public function destroyAngkatan($angkatan)
    {
        User::where('role', 'siswa')
            ->where('angkatan', $angkatan)
            ->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Seluruh akun siswa Angkatan ' . $angkatan . ' berhasil dihapus permanen.');
    }
}