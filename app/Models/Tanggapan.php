<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'user_id',
        'pesan',
        'lampiran', // [TAMBAHAN BARU] Untuk menyimpan nama file lampiran/gambar
    ];

    // Relasi balik ke Laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    // Relasi ke User (Siapa yang ngetik pesan ini)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}