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
            'user_id' => User::factory(),
            'nama_peternakan' => fake()->company(),
            'alamat' => fake()->address(),
            'nomor_handphone' => fake()->phoneNumber(),
            'foto_profil' => null,
            'jenis_akun' => 'free',
            'pro_berlaku_hingga' => null,
            'periode_deteksi' => now()->format('Y-m'),
            'deteksi_terpakai' => 0,
        ];
    }
}
