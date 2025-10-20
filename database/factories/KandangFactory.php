<?php

namespace Database\Factories;

use App\Models\Kandang;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kandang> */
class KandangFactory extends Factory
{
    protected $model = Kandang::class;

    public function definition(): array
    {
        return [
            'peternak_id' => Peternak::factory(),
            'nomor_kandang' => fake()->unique()->bothify('KDG-##'),
            'deskripsi_kandang' => fake()->sentence(),
            'status' => 'kosong',
            'indukan_jantan_id' => null,
            'indukan_betina_id' => null,
        ];
    }
}
