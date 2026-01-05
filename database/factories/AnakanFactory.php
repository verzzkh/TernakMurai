<?php

namespace Database\Factories;

use App\Models\Anakan;
use App\Models\Peternak;
use App\Models\Kandang;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anakan> */
class AnakanFactory extends Factory
{
    protected $model = Anakan::class;

    public function definition(): array
    {
        return [
            'peternak_id' => Peternak::factory(),
            'kandang_id' => Kandang::factory(),
            'perkawinan_id' => null,
            'nomor_ring' => strtoupper(fake()->bothify('ANAK-####')),
            'tanggal_lahir' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['jantan','betina','tidak_diketahui']),
            'status_pertumbuhan' => fake()->randomElement(['trotol','pastol','lomba']),
            'deskripsi_karakteristik' => fake()->sentence(),
            'catatan_perubahan' => null,
            'catatan_penjualan' => null,
            'harga' => null,
            'status_penjualan' => 'belum_dijual',
            'tanggal_jual' => null,
            'foto_path' => null,
        ];
    }
}
