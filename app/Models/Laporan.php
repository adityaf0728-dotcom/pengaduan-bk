<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi data
    protected $fillable = [
        'user_id',       // ID Siswa yang melapor
        'guru_id',       // [TAMBAHAN BARU] ID Guru BK yang dipilih
        'kategori',      // [TAMBAHAN BARU] Topik Laporan (Curhat, Pembullyan, dll)
        'isi_laporan',
        'lampiran',      // [TAMBAHAN BARU] Untuk menyimpan nama file lampiran/gambar
        'feedback',
        'status',
    ];

    // Membuat relasi bahwa satu laporan dimiliki oleh satu user (siswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // [TAMBAHAN BARU] Membuat relasi ke Guru BK tujuan
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Membuat relasi bahwa satu laporan memiliki banyak tanggapan (chat)
    public function tanggapans()
    {
        return $this->hasMany(Tanggapan::class);
    }
}