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
        Schema::table('pendaftar_document', function (Blueprint $table) {
            $table->enum('status_dokumen', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar_document', function (Blueprint $table) {
            $table->dropColumn('status_dokumen');
        });
    }
};
