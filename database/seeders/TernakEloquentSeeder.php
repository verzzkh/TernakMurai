<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Anakan;
use App\Models\DeteksiPenyakit;
use App\Models\FotoIndukan;
use App\Models\Indukan;
use App\Models\Invoice;
use App\Models\Kandang;
use App\Models\Perkawinan;
use App\Models\Peternak;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;

class TernakEloquentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few peternak with related data
        Peternak::factory()
            ->count(5)
            ->create()
            ->each(function (Peternak $peternak) {
                // create admin for first peternak
                if (rand(1,5) === 1) {
                    Admin::factory()->create(['user_id' => $peternak->user_id]);
                }

                // create indukan
                $indukans = Indukan::factory()->count(8)->create(['peternak_id' => $peternak->id]);

                // create kandang referencing some indukan
                $kandangs = Kandang::factory()->count(3)->create(['peternak_id' => $peternak->id]);
                foreach ($kandangs as $i => $kandang) {
                    $male = $indukans->random();
                    $female = $indukans->random();
                    $kandang->update(['indukan_jantan_id' => $male->id, 'indukan_betina_id' => $female->id]);

                    // create perkawinan
                    Perkawinan::factory()->create([
                        'kandang_id' => $kandang->id,
                        'indukan_jantan_id' => $male->id,
                        'indukan_betina_id' => $female->id,
                    ]);
                }

                // create anakan for peternak
                Anakan::factory()->count(10)->create(['peternak_id' => $peternak->id, 'kandang_id' => $kandangs->random()->id]);

                // create foto for some indukan
                foreach ($indukans->take(4) as $ind) {
                    FotoIndukan::factory()->create(['peternak_id' => $peternak->id, 'indukan_id' => $ind->id]);
                }

                // create transaksi and invoices
                Transaksi::factory()->count(6)->create(['peternak_id' => $peternak->id]);
                Invoice::factory()->count(2)->create(['peternak_id' => $peternak->id]);

                // create some deteksi_penyakit
                DeteksiPenyakit::factory()->count(3)->create(['peternak_id' => $peternak->id]);
            });

        // Create a known admin user for login testing
        User::factory()->create(['name' => 'Seeder Admin', 'email' => 'admin@seed.local']);
    }
}
