<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            // Menghubungkan laporan dengan id siswa (user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Kolom untuk isi laporan/keluhan dari siswa
            $table->text('isi_laporan');
            // Kolom untuk balasan dari Guru BK (bisa kosong/nullable pada awalnya)
            $table->text('feedback')->nullable();
            // Status laporan untuk mengetahui apakah sudah dibalas atau belum
            $table->enum('status', ['menunggu', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};