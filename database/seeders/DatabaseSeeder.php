<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the SQL importer / dynamic seeder which will try to execute
        // database/migrations/ternak.sql if it exists, otherwise it will
        // provide lightweight fallback sample data.

    // Also provide an Eloquent-based seeder that generates similar data
    // (models + factories) instead of executing raw SQL. Run manually with:
    // php artisan db:seed --class=Database\\Seeders\\TernakEloquentSeeder
    // Uncomment the next line to run it automatically.
        $this->call(TernakEloquentSeeder::class);

        // Create default admin account (username: admin, password: admin1)
        $this->call(AdminSeeder::class);
    }
}
