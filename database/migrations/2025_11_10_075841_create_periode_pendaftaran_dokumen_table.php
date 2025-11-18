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
        Schema::create('periode_pendaftaran_dokumen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Foreign Keys
            $table->uuid('periode_pendaftaran_id');
            $table->foreign('periode_pendaftaran_id')->references('id')->on('periode_pendaftaran')->onDelete('cascade');
            
            $table->uuid('dokumen_pendaftar_id');
            $table->foreign('dokumen_pendaftar_id')->references('id')->on('dokumen_pendaftar')->onDelete('cascade');
            
            // Additional fields for pivot table
            $table->boolean('is_wajib')->default(true)->comment('Apakah dokumen ini wajib untuk periode ini');
            $table->text('catatan')->nullable()->comment('Catatan khusus untuk dokumen di periode ini');
            
            $table->timestamps();
            
            // Unique constraint to prevent duplicate combinations
            $table->unique(['periode_pendaftaran_id', 'dokumen_pendaftar_id'], 'unique_periode_dokumen');
            
            // Indexes for better performance
            $table->index('periode_pendaftaran_id');
            $table->index('dokumen_pendaftar_id');
            $table->index('is_wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_pendaftaran_dokumen');
    }
};
