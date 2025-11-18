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
            // Add foreign key to dokumen_pendaftar
            $table->uuid('dokumen_pendaftar_id')->nullable()->after('pendaftar_id');
            $table->foreign('dokumen_pendaftar_id')->references('id')->on('dokumen_pendaftar')->onDelete('set null');
            
            // Add index for better performance
            $table->index('dokumen_pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar_document', function (Blueprint $table) {
            // Drop foreign key and index first
            $table->dropForeign(['dokumen_pendaftar_id']);
            $table->dropIndex(['dokumen_pendaftar_id']);
            
            // Drop column
            $table->dropColumn('dokumen_pendaftar_id');
        });
    }
};
