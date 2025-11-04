<?php

namespace Database\Factories;

use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PendaftarFactory extends Factory
{
    protected $model = Pendaftar::class;

    public function definition(): array
    {
        // Ambil satu periode_pendaftaran_id secara acak (pastikan sudah ada data di tabel ini)
        $periodeId = PeriodePendaftaran::inRandomOrder()->value('id') ?? Str::uuid()->toString();

        return [
            'nomor_pendaftaran' => Pendaftar::generateNomorPendaftaran($periodeId),
            'user_id' => null, // atau generate UUID acak jika ingin
            'google_user_id' => null,
            'periode_pendaftaran_id' => $periodeId,
            'nama_lengkap' => $this->faker->name(),
            'nik' => $this->faker->optional()->numerify('################'),
            'email' => $this->faker->unique()->safeEmail(),
            'no_hp' => $this->faker->phoneNumber(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-17 years'),
            'alamat' => $this->faker->address(),
            'pendidikan_terakhir' => $this->faker->randomElement(['SMA', 'SMK', 'MA', 'Paket C']),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'rejected']),
        ];
    }
}
