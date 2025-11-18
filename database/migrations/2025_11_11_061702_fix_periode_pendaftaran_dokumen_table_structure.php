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
        // Drop the table first to recreate it with correct structure
        Schema::dropIfExists('periode_pendaftaran_dokumen');
        
        // Recreate with proper structure for pivot table
        Schema::create('periode_pendaftaran_dokumen', function (Blueprint $table) {
            // Foreign Keys sebagai composite primary key
            $table->uuid('periode_pendaftaran_id');
            $table->foreign('periode_pendaftaran_id')->references('id')->on('periode_pendaftaran')->onDelete('cascade');
            
            $table->uuid('dokumen_pendaftar_id');
            $table->foreign('dokumen_pendaftar_id')->references('id')->on('dokumen_pendaftar')->onDelete('cascade');
            
            // Additional fields for pivot table
            $table->boolean('is_wajib')->default(true)->comment('Apakah dokumen ini wajib untuk periode ini');
            $table->text('catatan')->nullable()->comment('Catatan khusus untuk dokumen di periode ini');
            
            $table->timestamps();
            
            // Composite primary key
            $table->primary(['periode_pendaftaran_id', 'dokumen_pendaftar_id']);
            
            // Indexes for better performance
            $table->index('is_wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the fixed table
        Schema::dropIfExists('periode_pendaftaran_dokumen');
        
        // Recreate original structure (with problematic UUID id)
        Schema::create('periode_pendaftaran_dokumen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('periode_pendaftaran_id');
            $table->foreign('periode_pendaftaran_id')->references('id')->on('periode_pendaftaran')->onDelete('cascade');
            
            $table->uuid('dokumen_pendaftar_id');
            $table->foreign('dokumen_pendaftar_id')->references('id')->on('dokumen_pendaftar')->onDelete('cascade');
            
            $table->boolean('is_wajib')->default(true);
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            $table->unique(['periode_pendaftaran_id', 'dokumen_pendaftar_id'], 'unique_periode_dokumen');
            $table->index('periode_pendaftaran_id');
            $table->index('dokumen_pendaftar_id');
            $table->index('is_wajib');
        });
    }
};
