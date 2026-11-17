<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('kategori')->after('id')->nullable();
            // Menambahkan foreign key ke tabel users (khusus guru)
            $table->foreignId('guru_id')->nullable()->after('kategori')->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn(['kategori', 'guru_id']);
        });
    }
};