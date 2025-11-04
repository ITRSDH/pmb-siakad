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
        Schema::table('biaya_pendaftaran', function (Blueprint $table) {
            // Menghapus unique constraint pada nama_biaya jika ada
            $table->dropUnique(['nama_biaya']);
            
            // Menambahkan unique constraint untuk kombinasi nama_biaya + jalur_pendaftaran_id
            $table->unique(['nama_biaya', 'jalur_pendaftaran_id'], 'unique_biaya_per_jalur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biaya_pendaftaran', function (Blueprint $table) {
            // Menghapus constraint unique yang baru
            $table->dropUnique('unique_biaya_per_jalur');
            
            // Mengembalikan unique constraint pada nama_biaya
            $table->unique('nama_biaya');
        });
    }
};
