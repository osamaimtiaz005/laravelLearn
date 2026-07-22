<?php

// ============================================================
// FILE: create_profiles_table migration
// PURPOSE: Create the "profiles" table in MySQL
// RUN WITH: php artisan migrate
// ============================================================

/**
 * ONE-TO-ONE IN DATABASE (very simple)
 *
 * users table (already exists):
 *   id | name | email | ...
 *    1 | Ali  | a@x.com
 *
 * profiles table (this file creates it):
 *   id | user_id | phone | city | ...
 *    1 |    1    | 0300  | Lahore
 *
 * user_id = 1 means this profile belongs to user Ali.
 *
 * RULE: foreign key goes on the CHILD table (profiles).
 *
 * ============================================================
 * METHODS YOU CAN APPLY ON FOREIGN KEY / FIELDS
 * ============================================================
 *
 * ---------- A) What happens when PARENT (user) is DELETED ----------
 *
 * ->cascadeOnDelete()
 *   Word meaning: cascade = pass the delete down to child
 *   If user is deleted → profile is ALSO deleted automatically
 *   Best when: profile has no meaning without the user
 *   Example we use below.
 *
 * ->nullOnDelete()
 *   Word meaning: null = empty / no value
 *   If user is deleted → profile STAYS, but user_id becomes NULL
 *   IMPORTANT: column must be nullable() first
 *   Example:
 *     $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
 *   Best when: you want to keep the child row after parent is gone
 *
 * ->restrictOnDelete()
 *   Word meaning: restrict = block / stop
 *   If user still has a profile → MySQL REFUSES to delete the user
 *   Error until you delete profile first
 *   Best when: you want to force careful deletes
 *
 * ->noActionOnDelete()
 *   Similar idea to restrict (database decides / often blocks)
 *   Less used in Laravel apps; prefer restrictOnDelete()
 *
 * ---------- B) What happens when PARENT (user) id is UPDATED ----------
 * (rare, because ids usually never change)
 *
 * ->cascadeOnUpdate()
 *   If users.id changes → profiles.user_id updates to match
 *
 * ->restrictOnUpdate()
 *   Block changing users.id if a profile still points to it
 *
 * ---------- C) Extra field rules (not only for foreign keys) ----------
 *
 * ->unique()
 *   Value can appear only ONCE in this column
 *   On user_id → true one-to-one (one user, one profile)
 *   Without unique → one user could have many profiles (one-to-many)
 *
 * ->nullable()
 *   Column can be empty (NULL)
 *   Required if you use nullOnDelete()
 *   Example: $table->string('phone')->nullable();
 *
 * ->default('value')
 *   If you don't send a value, use this default
 *   Example: $table->string('city')->default('Lahore');
 *
 * ->index()
 *   Makes searching this column faster
 *   foreignId() already creates an index in most cases
 *
 * ->after('column')
 *   Place this column after another column (MySQL)
 *   Example: $table->string('phone')->after('user_id');
 *
 * ->comment('text')
 *   Add a note inside the database about this column
 *
 * ---------- D) Quick choose guide ----------
 *
 * Want profile deleted with user?     → cascadeOnDelete()
 * Want profile kept, user_id empty?   → nullable() + nullOnDelete()
 * Want delete user blocked if linked? → restrictOnDelete()
 * Want true one-to-one?               → unique() on user_id
 */

// Import migration helper classes
use Illuminate\Database\Migrations\Migration; // base class for migrations
use Illuminate\Database\Schema\Blueprint;     // used to describe columns
use Illuminate\Support\Facades\Schema;        // Schema::create / drop helpers

// return new class extends Migration
//   anonymous class = class without a name (Laravel style for migrations)
//   extends Migration = must have up() and down() methods
return new class extends Migration
{
    /**
     * up() = runs when you type: php artisan migrate
     * This CREATES the table.
     */
    public function up(): void
    {
        // Schema::create('profiles', function (...) { ... })
        //   Schema     = Laravel database schema tool
        //   ::create   = make a new table
        //   'profiles' = table name (MUST be plural for Profile model)
        //   function (Blueprint $table) = closure that defines columns
        //   $table     = object used to add columns
        Schema::create('profiles', function (Blueprint $table) {

            // $table->id();
            //   creates column: id (BIGINT, primary key, auto increment)
            //   1, 2, 3, 4... automatic
            $table->id();

            // ========================================================
            // OUR CHOICE FOR THIS DEMO:
            //   foreignId + constrained + cascadeOnDelete + unique
            // ========================================================
            //
            // $table->foreignId('user_id')
            //   creates column user_id (BIGINT unsigned)
            //   this is the LINK to users.id
            //
            // ->constrained('users')
            //   means: user_id must exist in users.id
            //   MySQL will reject invalid user ids
            //
            // ->cascadeOnDelete()
            //   if user is deleted, delete their profile too
            //   (see alternatives in the big comment at top)
            //
            // ->unique()
            //   one user_id can appear only ONCE in profiles
            //   this enforces true one-to-one in the database
            //
            // OTHER EXAMPLES (do NOT run all at once — pick one style):
            //
            // 1) Keep profile, clear link when user deleted:
            // $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->unique();
            //
            // 2) Block deleting user if profile still exists:
            // $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->unique();
            //
            // 3) Cascade delete + also cascade update of id:
            // $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            //
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            // string('phone') = VARCHAR column named phone
            // nullable() = allowed to be empty (NULL)
            // You can also chain: ->default('N/A')->comment('mobile number')
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // timestamps() = creates created_at and updated_at columns
            // Laravel fills these automatically
            $table->timestamps();
        });
    }

    /**
     * down() = runs when you type: php artisan migrate:rollback
     * This DELETES the table (undo).
     */
    public function down(): void
    {
        // Schema::dropIfExists('profiles')
        //   drop = delete table
        //   IfExists = only if table is there (no error if missing)
        Schema::dropIfExists('profiles');
    }
};
