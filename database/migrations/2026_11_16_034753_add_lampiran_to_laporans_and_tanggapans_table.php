<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Menambahkan kolom di tabel laporans (opsional jika siswa juga bisa kirim file saat melapor)
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('isi_laporan');
        });

        // Menambahkan kolom di tabel tanggapans (untuk balasan Guru BK & Siswa)
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('pesan');
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
    }
};