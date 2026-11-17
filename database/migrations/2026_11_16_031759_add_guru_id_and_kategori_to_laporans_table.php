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
        Schema::table('laporans', function (Blueprint $table) {
            // Cek apakah kolom guru_id belum ada sebelum menambahkannya
            if (!Schema::hasColumn('laporans', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            }
            
            // Cek apakah kolom kategori belum ada sebelum menambahkannya
            if (!Schema::hasColumn('laporans', 'kategori')) {
                $table->string('kategori')->nullable()->after('guru_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            // Hapus foreign key dan kolom hanya jika kolom tersebut ada
            if (Schema::hasColumn('laporans', 'guru_id')) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            }
            if (Schema::hasColumn('laporans', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};