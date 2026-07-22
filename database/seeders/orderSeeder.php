<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * orderSeeder — insert sample data for the Two-Way Test
 *
 * $user->orders()->create([...])
 *   FORWARD demo: attach a new order to one user
 *   Laravel sets orders.user_id = $user->id  (BACKWARD link)
 *
 * Run:
 *   php artisan db:seed --class=orderSeeder
 * Or via DatabaseSeeder:
 *   php artisan db:seed
 */
class orderSeeder extends Seeder
{
    public function run(): void
    {
        // Pick any existing user (random) to own the new order
        $user = User::query()->inRandomOrder()->first(['*']);

        // No users yet → cannot create an order (FK would fail)
        if (! $user) {
            return;
        }

        // hasMany create = one more order for this user (Forward: many allowed)
        $user->orders()->create([
            'name' => Str::random(5),
            'description' => 'Order description',
            'price' => rand(100, 1000).' USD',
        ]);
    }
}
