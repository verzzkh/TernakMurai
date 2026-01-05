<?php

namespace Database\Factories;

use App\Models\Perkawinan;
use App\Models\Kandang;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perkawinan> */
class PerkawinanFactory extends Factory
{
    protected $model = Perkawinan::class;

    public function definition(): array
    {
        return [
            'kandang_id' => Kandang::factory(),
            'indukan_jantan_id' => null,
            'indukan_betina_id' => null,
            'nomor_trip' => fake()->numberBetween(1, 1000),
            'tanggal_kawin' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'catatan' => fake()->sentence(),
        ];
    }
}
