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
        Schema::create('kelengkapan_pendaftar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Foreign key ke tabel pendaftar
            $table->uuid('pendaftar_id');
            $table->foreign('pendaftar_id')->references('id')->on('pendaftar')->onDelete('cascade');
            
            // 1. Biodata
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('nisn', 10);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 15);
            $table->string('email');
            
            // 2. Alamat
            $table->text('alamat_lengkap');
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('kecamatan');
            $table->string('kelurahan');
            
            // 3. Pendidikan
            $table->string('asal_sekolah');
            $table->string('tahun_lulus');
            
            // 5. Dokumen
            $table->string('pas_foto')->nullable();
            $table->string('ktp')->nullable();
            $table->string('kk')->nullable();
            $table->string('ijazah_skl')->nullable();
            
            // 6. Orang tua
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu');
            
            // Status verifikasi
            $table->enum('status', ['draft', 'submitted', 'verified', 'rejected'])->default('draft');
            $table->text('catatan_admin')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelengkapan_pendaftar');
    }
};
