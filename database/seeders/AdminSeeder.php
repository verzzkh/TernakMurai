<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // If an admin user already exists, skip creation
        if (User::where('email', 'admin@example.test')->exists() || Admin::where('username', 'admin')->exists()) {
            return;
        }

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('admin1'),
        ]);

        Admin::create([
            'user_id' => $user->id,
            'username' => 'admin',
            'password' => Hash::make('admin1'),
            'nama_lengkap' => 'Administrator',
        ]);
    }
}
