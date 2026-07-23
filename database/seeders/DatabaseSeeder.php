<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — master seeder
 *
 * php artisan db:seed
 *   runs run() below
 *   $this->call(SomeSeeder::class) = run that seeder class
 *
 * Order matters when tables depend on each other:
 *   users should exist before orderSeeder (orders need user_id)
 *   userSeeder / factory create users; orderSeeder needs a user row
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fill students table (learning demo)
        $this->call(studentSeeder::class);

        // ONE-TO-MANY demo data: needs at least one user in DB
        // Prefer running user seed / factory before this if users table is empty
        $this->call(orderSeeder::class);

        // MANY-TO-MANY demo: roles + role_user pivot links
        $this->call(RoleSeeder::class);

        // Other learning seeders
        $this->call(subscriptionSeeder::class);
        $this->call(userSeeder::class);

        // Extra test user via factory
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
