<?php

namespace Database\Factories;

use App\Models\Indukan;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Indukan> */
class IndukanFactory extends Factory
{
    protected $model = Indukan::class;

    public function definition(): array
    {
        return [
            'peternak_id' => Peternak::factory(),
            'nomor_ring' => strtoupper(fake()->bothify('RING-####')),
            'nama' => fake()->firstName(),
            'jenis_kelamin' => fake()->randomElement(['jantan','betina']),
            'tanggal_lahir' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'catatan' => fake()->sentence(),
            'prestasi' => null,
            'karakteristik' => fake()->sentence(),
        ];
    }
}
