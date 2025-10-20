<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice> */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 years', 'now');
        $end = (clone $start)->modify('+1 month');

        return [
            'peternak_id' => Peternak::factory(),
            'periode_mulai' => $start->format('Y-m-d'),
            'periode_selesai' => $end->format('Y-m-d'),
            'harga' => 25000,
            'status' => fake()->randomElement(['pending','paid','rejected']),
            'metode' => fake()->randomElement(['manual','midtrans']),
            'bukti_path' => null,
            'paid_at' => null,
        ];
    }
}
