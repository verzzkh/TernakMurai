<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi> */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        $tipe = fake()->randomElement(['pemasukan','pengeluaran']);
        $kategori = $tipe === 'pemasukan' ? fake()->randomElement(['penjualan_anakan','penjualan_indukan','pemasukan_lainnya']) : fake()->randomElement(['pakan','vitamin','perawatan','pengeluaran_lainnya']);

        return [
            'peternak_id' => Peternak::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'tipe' => $tipe,
            'kategori' => $kategori,
            'jumlah' => fake()->randomFloat(2, 1000, 500000),
            'nama_item' => fake()->word(),
            'deskripsi' => fake()->sentence(),
            'anakan_id' => null,
            'indukan_id' => null,
            'ring_referensi' => null,
        ];
    }
}
