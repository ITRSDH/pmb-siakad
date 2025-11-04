<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan factory sudah ada untuk App\Models\Pendaftar
        \App\Models\Pendaftar::factory()->count(100)->create();
    }
}
