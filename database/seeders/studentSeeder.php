<?php
// PHP opening tag — required at the top of every PHP file

namespace Database\Seeders;
// namespace = group/folder name for this class
// Database\Seeders matches the folder: database/seeders/
// Laravel finds and loads seeders using this namespace

// Import (bring in) classes/helpers we need below:
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Trait that can disable Eloquent model events while seeding
// (imported by make:seeder; not used in this class)

use Illuminate\Database\Seeder;
// Base Seeder class from Laravel — our class must extend this

use Illuminate\Support\Facades\DB;
// DB facade = Query Builder shortcut to insert/update/delete without an Eloquent model

use Illuminate\Support\Str;
// Str helper = string utilities (we use Str::random() for dummy names)

/**
 * studentSeeder
 * -------------
 * WHAT IS A SEEDER?
 * A seeder inserts dummy/test data into the database.
 * Migration builds the table. Seeder fills the table.
 *
 * CREATE THIS FILE:
 *   php artisan make:seeder studentSeeder
 *
 * RUN ONLY THIS SEEDER:
 *   php artisan db:seed --class=studentSeeder
 *
 * RUN ALL SEEDERS (via DatabaseSeeder):
 *   php artisan db:seed
 *   (only runs this file if DatabaseSeeder calls: $this->call(studentSeeder::class);)
 *
 * MIGRATE + SEED TOGETHER:
 *   php artisan migrate:fresh --seed
 *   php artisan migrate:refresh --seed
 *
 * NOTE: Each run() adds ONE new row. It does not delete old students.
 */
class studentSeeder extends Seeder
{
    // class = defines this seeder
    // studentSeeder = class name (must match --class=studentSeeder)
    // extends Seeder = inherit Laravel seeder behavior (Artisan can call run())

    /**
     * run()
     * -----
     * Laravel ALWAYS calls this method when the seeder runs.
     * public  = Artisan can call it from outside
     * void    = this method returns nothing
     */
    public function run(): void
    {
        // Make a random 10-character string for the student name
        // Example result: "aK9mPq2xYz"
        $name = Str::random(10);

        // Insert ONE row into the "students" table using Query Builder
        // DB::table('students') → choose the table
        // ->insert([...])      → add a row (column => value)
        // Columns must match the students migration: name, email, batch
        // "id" is auto-created by the database ($table->id() in migration)
        DB::table('students')->insert([
            'name'  => $name,                    // random name from above
            'email' => $name.'@yopmail.com',     // name + "@yopmail.com" ( . means join strings)
            'batch' => rand(2010, 2025),         // random year from 2010 to 2025
        ]);
    }
}
