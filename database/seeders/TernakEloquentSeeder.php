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
use Illuminate\Support\Facades\Hash;

class TernakEloquentSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =========================================================
         * 1. Buat akun user peternak dan semua relasinya
         * =========================================================
         */
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => fake()->unique()->userName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
            ]);

            $peternak = Peternak::factory()->create([
                'user_id' => $user->id,
            ]);

            // Buat data dummy terkait peternak
            $indukans = Indukan::factory()->count(8)->create(['peternak_id' => $peternak->id]);

            $kandangs = Kandang::factory()->count(3)->create(['peternak_id' => $peternak->id]);
            foreach ($kandangs as $kandang) {
                $male = $indukans->random();
                $female = $indukans->random();

                $kandang->update([
                    'indukan_jantan_id' => $male->id,
                    'indukan_betina_id' => $female->id,
                ]);

                Perkawinan::factory()->create([
                    'kandang_id' => $kandang->id,
                    'indukan_jantan_id' => $male->id,
                    'indukan_betina_id' => $female->id,
                ]);
            }

            Anakan::factory()->count(10)->create([
                'peternak_id' => $peternak->id,
                'kandang_id' => $kandangs->random()->id,
            ]);

            foreach ($indukans->take(4) as $ind) {
                FotoIndukan::factory()->create([
                    'peternak_id' => $peternak->id,
                    'indukan_id' => $ind->id,
                ]);
            }

            Transaksi::factory()->count(6)->create(['peternak_id' => $peternak->id]);
            Invoice::factory()->count(2)->create(['peternak_id' => $peternak->id]);
            DeteksiPenyakit::factory()->count(3)->create(['peternak_id' => $peternak->id]);
        }

        /**
         * =========================================================
         * 2. Buat akun admin
         * =========================================================
         */
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create([
                'name' => fake()->unique()->userName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
            ]);

            Admin::factory()->create([
                'user_id' => $user->id,
                'username' => fake()->unique()->userName(),
                'nama_lengkap' => fake()->name(),
            ]);
        }

        /**
         * =========================================================
         * 3. Buat akun login testing manual
         * =========================================================
         */
        $adminUser = User::factory()->create([
            'name' => 'SeederAdmin',
            'email' => 'admin@seed.local',
            'password' => Hash::make('password'),
        ]);
        Admin::factory()->create([
            'user_id' => $adminUser->id,
            'username' => 'adminseed',
            'nama_lengkap' => 'Admin Seeder',
        ]);

        $peternakUser = User::factory()->create([
            'name' => 'Pak Rakha',
            'email' => 'rakha@seed.local',
            'password' => Hash::make('password'),
        ]);
        Peternak::factory()->create([
            'user_id' => $peternakUser->id,
            'nama_peternakan' => 'Peternakan Rakha Jaya',
            'nomor_handphone' => '08123456789',
        ]);
    }
}
