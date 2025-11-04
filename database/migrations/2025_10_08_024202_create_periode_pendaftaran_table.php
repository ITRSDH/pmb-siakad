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
        Schema::create('periode_pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_periode');
            $table->text('deskripsi')->nullable();
            // Foreign Keys
            $table->uuid('gelombang_id');
            $table->foreign('gelombang_id')->references('id')->on('gelombang')->onDelete('cascade');
            $table->uuid('jalur_pendaftaran_id');
            $table->foreign('jalur_pendaftaran_id')->references('id')->on('jalur_pendaftaran')->onDelete('cascade');
            $table->uuid('biaya_pendaftaran_id');
            $table->foreign('biaya_pendaftaran_id')->references('id')->on('biaya_pendaftaran')->onDelete('cascade');
            
            // Periode Timeline
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Kuota & Status
            $table->integer('kuota')->default(0);
            $table->integer('kuota_terisi')->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'draft', 'selesai'])->default('draft');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'tanggal_mulai', 'tanggal_selesai']);
            $table->index(['gelombang_id', 'jalur_pendaftaran_id']);
            
            // Unique constraint untuk mencegah duplikasi periode yang sama
            $table->unique(['gelombang_id', 'jalur_pendaftaran_id', 'tanggal_mulai'], 'unique_periode_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_pendaftaran');
    }
};
