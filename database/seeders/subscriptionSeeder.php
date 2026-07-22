<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class subscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Make sure to import the User model at the top:
        // use App\Models\User;

        // Get all users to assign subscriptions in auto-increment pattern
        $users = \App\Models\User::all();

        // If you want to create one subscription per user with auto-incremented user id
        foreach ($users as $user) {
            Subscription::create([
                'user_id' => $user->id, // this will be auto-incrementing through loop
                'name' => 'Basic',
                'description' => 'Basic subscription',
                'price' => 10,
                'currency' => 'USD',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 30,
                'status' => 'active',
            ]);
        }

        // If you only want to create for a single user (like before), but clarify auto-increment:
        // $user = \App\Models\User::orderBy('id')->first();
        // Subscription::create([
        //     'user_id' => $user ? $user->id : 1,  // uses first (smallest id, auto-incremented in DB)
        //     ...
        // ]);
    }
}
