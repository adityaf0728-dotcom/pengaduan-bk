<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // =====================================
    // BAGIAN SISWA
    // =====================================
    public function create()
    {
        // Ambil laporan milik siswa yang sedang login beserta riwayat chat-nya
        $laporans = Laporan::with(['tanggapans.user'])
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Diperbarui ke view siswa.laporan agar data $laporans tampil di halaman pelaporan
        return view('siswa.laporan', compact('laporans')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
        ]);

        Laporan::create([
            'user_id' => Auth::id(),
            'isi_laporan' => $request->isi_laporan,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Silakan tunggu balasan dari Guru BK.');
    }

    // =====================================
    // BAGIAN GURU BK
    // =====================================
    
    // [FUNGSI BARU] Menampilkan Dashboard beserta statistik Laporan
    public function dashboardGuru()
    {
        // Menghitung semua laporan yang ada
        $totalLaporanMasuk = Laporan::count(); 

        // Menghitung laporan yang sudah tidak berstatus 'menunggu'
        $totalLaporanTerbalas = Laporan::where('status', '!=', 'menunggu')->count();

        // Me-return view dashboard guru bk beserta variabel angkanya
        return view('gurubk.dashboard', compact('totalLaporanMasuk', 'totalLaporanTerbalas'));
    }

    public function indexGuru()
    {
        // Ambil semua laporan beserta data siswa dan riwayat chat-nya
        $laporans = Laporan::with(['user', 'tanggapans.user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('gurubk.laporan', compact('laporans'));
    }

    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => 'selesai']);

        return back()->with('success', 'Laporan berhasil ditandai selesai.');
    }

    // =====================================
    // FITUR CHAT / TANGGAPAN (BISA DIGUNAKAN GURU & SISWA)
    // =====================================
    public function kirimTanggapan(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        $laporan = Laporan::findOrFail($id);

        // Mencegah kirim pesan jika status sudah selesai
        if ($laporan->status === 'selesai') {
            return back()->with('error', 'Laporan ini sudah ditutup.');
        }

        // Simpan pesan ke tabel tanggapans
        Tanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
        ]);

        // (Opsional) Update status laporan menjadi 'diproses' jika guru yang membalas
        if (Auth::user()->role === 'guru_bk' && $laporan->status === 'menunggu') {
            $laporan->update(['status' => 'diproses']);
        }

        return back()->with('success', 'Pesan terkirim!');
    }
}