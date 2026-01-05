<?php

namespace Database\Factories;

use App\Models\DeteksiPenyakit;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeteksiPenyakit> */
class DeteksiPenyakitFactory extends Factory
{
    protected $model = DeteksiPenyakit::class;

    public function definition(): array
    {
        return [
            'peternak_id' => Peternak::factory(),
            'nama_burung' => fake()->word(),
            'foto_path' => null,
            'gejala' => fake()->sentence(),
            'perilaku' => fake()->sentence(),
            'riwayat_kesehatan' => fake()->sentence(),
            'lingkungan' => fake()->sentence(),
            'makanan' => fake()->sentence(),
            'hasil_analisis' => fake()->paragraph(),
            'diagnosis_utama' => fake()->word(),
            'tingkat_kepercayaan' => fake()->numberBetween(50, 100),
            'rekomendasi' => fake()->sentence(),
            'is_saved' => fake()->boolean(50),
        ];
    }
}
