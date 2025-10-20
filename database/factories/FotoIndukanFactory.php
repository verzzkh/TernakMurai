<?php

namespace Database\Factories;

use App\Models\FotoIndukan;
use App\Models\Indukan;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FotoIndukan> */
class FotoIndukanFactory extends Factory
{
    protected $model = FotoIndukan::class;

    public function definition(): array
    {
        return [
            'peternak_id' => Peternak::factory(),
            'indukan_id' => Indukan::factory(),
            'path' => 'images/' . fake()->bothify('indukan-####.jpg'),
            'caption' => fake()->sentence(),
            'is_cover' => fake()->boolean(20),
        ];
    }
}
