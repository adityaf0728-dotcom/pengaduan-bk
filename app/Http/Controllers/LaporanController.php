<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tanggapan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // =====================================
    // BAGIAN SISWA
    // =====================================
    public function create()
    {
        // Ambil daftar guru bk untuk ditampilkan di form pilihan siswa
        $gurus = User::where('role', 'guru_bk')->get();

        // Ambil laporan milik siswa yang sedang login beserta riwayat chat-nya
        $laporans = Laporan::with(['tanggapans.user', 'guru'])
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Kirim data $laporans dan $gurus ke view
        return view('siswa.laporan', compact('laporans', 'gurus')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'isi_laporan' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Validasi file laporan
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'guru_id' => $request->guru_id,
            'kategori' => $request->kategori,
            'isi_laporan' => $request->isi_laporan,
            'lampiran' => $lampiranPath, // Simpan path ke laporans
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim ke Guru BK pilihanmu. Silakan tunggu balasan.');
    }

    // =====================================
    // BAGIAN GURU BK
    // =====================================
    
    public function dashboardGuru()
    {
        // Menghitung laporan masuk KHUSUS untuk guru bk yang sedang login
        $totalLaporanMasuk = Laporan::where('guru_id', Auth::id())->count(); 

        // Menghitung laporan yang sudah tidak berstatus 'menunggu' KHUSUS untuk guru bk ini
        $totalLaporanTerbalas = Laporan::where('guru_id', Auth::id())
                                       ->where('status', '!=', 'menunggu')
                                       ->count();

        return view('gurubk.dashboard', compact('totalLaporanMasuk', 'totalLaporanTerbalas'));
    }

    public function indexGuru()
    {
        // Ambil laporan yang HANYA DITUJUKAN kepada guru bk yang sedang login
        $laporans = Laporan::with(['user', 'tanggapans.user'])
                    ->where('guru_id', Auth::id())
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
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Maksimal 5MB
        ]);
        $laporan = Laporan::findOrFail($id);
        if ($laporan->status === 'selesai') {
            return back()->with('error', 'Laporan ini sudah ditutup.');
        }
        // Proses penyimpanan file jika ada
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
        }

        Tanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'lampiran' => $lampiranPath, // Menyimpan path file ke database
        ]);
        return back()->with('success', 'Pesan terkirim!');
    }
}