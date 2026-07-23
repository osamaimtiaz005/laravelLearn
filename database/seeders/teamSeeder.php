<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class teamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Team::create([
            'name' => 'Team 1',
        ]);
        Team::create([
            'name' => 'Team 2',
        ]);
        Team::create([
            'name' => 'Team 3',
        ]);
        Team::create([
            'name' => 'Team 4',
        ]);
        Team::create([
            'name' => 'Team 5',
        ]);
        Team::create([
            'name' => 'Team 6',
        ]);
        Team::create([
            'name' => 'Team 7',
        ]);
        Team::create([
            'name' => 'Team 8',
        ]);
    }
}
