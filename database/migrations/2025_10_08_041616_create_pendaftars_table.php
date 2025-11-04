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
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pendaftaran')->unique();
            // Optional link to users table (NULL for guest registrations)
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            // Optional Google user table (if you use a separate google_users table)
            $table->uuid('google_user_id')->nullable();
            $table->foreign('google_user_id')->references('id')->on('google_users')->nullOnDelete();

            $table->uuid('periode_pendaftaran_id');
            $table->foreign('periode_pendaftaran_id')->references('id')->on('periode_pendaftaran')->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir')->nullable();

            // Registration status: draft, submitted, rejected
            $table->enum('status', ['draft', 'submitted', 'rejected'])->default('draft');

            // Metadata
            $table->string('source')->nullable(); // e.g., 'guest','user','google'
            $table->json('meta')->nullable(); // extensible metadata (ip, browser, utm, etc.)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
