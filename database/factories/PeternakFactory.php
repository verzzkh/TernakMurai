<?php

namespace Database\Factories;

use App\Models\Peternak;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peternak> */
class PeternakFactory extends Factory
{
    protected $model = Peternak::class;

    public function definition(): array
    {
        return [
            // Buat user otomatis saat membuat data peternak via factory.
            'user_id' => User::factory(),
            // Nama peternakan (untuk identitas peternak di sistem).
            'nama_peternakan' => fake()->company(),
            // Alamat peternakan (opsional).
            'alamat' => fake()->address(),
            // Nomor handphone (opsional) untuk kontak.
            'nomor_handphone' => fake()->phoneNumber(),
            // Foto profil (nullable) — default null pada data dummy.
            'foto_profil' => null,
        ];
    }
}
