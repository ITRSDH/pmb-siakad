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
        Schema::create('biaya_pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_biaya')->unique();
            $table->integer('jumlah_biaya');
            $table->uuid('jalur_pendaftaran_id');
            $table->foreign('jalur_pendaftaran_id')->references('id')->on('jalur_pendaftaran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_pendaftaran');
    }
};
