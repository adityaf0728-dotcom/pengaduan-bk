<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi data
    protected $fillable = [
        'user_id',
        'isi_laporan',
        'feedback',
        'status',
    ];

    // Membuat relasi bahwa satu laporan dimiliki oleh satu user (siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Membuat relasi bahwa satu laporan memiliki banyak tanggapan (chat)
    public function tanggapans()
    {
        return $this->hasMany(Tanggapan::class);
    }
}