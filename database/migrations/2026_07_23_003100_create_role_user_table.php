<?php

/**
 * ============================================================
 * MANY-TO-MANY — pivot table role_user
 * ============================================================
 *
 * WHAT IS A PIVOT?
 *   A pivot is the MIDDLE / bridge table that connects two tables
 *   in a many-to-many relationship.
 *
 * WHY YOU NEED IT:
 *   users and roles cannot both store “many” links with only one
 *   user_id or role_id on the other table.
 *   So you add a bridge table (the pivot).
 *
 * PICTURE:
 *   users          role_user (PIVOT)           roles
 *   -----          -----------------           -----
 *   id=1 Ali       user_id=1, role_id=1   →    id=1 Admin
 *                  user_id=1, role_id=2   →    id=2 Editor
 *   id=2 Sara      user_id=2, role_id=1   →    id=1 Admin
 *
 *   Each ROW in the pivot = one link (“Ali is Admin”).
 *
 * IN THIS PROJECT:
 *   Pivot table name: role_user
 *   Columns:          user_id, role_id
 *   Extra on link:    is_active, notes, timestamps
 *
 * IN LARAVEL:
 *   $user->roles;                 // uses pivot behind the scenes
 *   $role->pivot->is_active;      // extra columns from role_user
 *   $user->roles()->attach(1);    // INSERT a pivot row
 *   $user->roles()->detach(1);    // DELETE a pivot row
 *
 * SHORT VERSION:
 *   pivot = join table that stores “who is linked to whom”
 *           (and optional info about that link).
 *
 * Naming rule (Laravel default):
 *   Alphabetical model names → role_user (not user_role)
 */
/**
 * WHY DO WE CREATE THE PIVOT TABLE HERE, IF WE ALREADY DEFINED THE RELATION IN THE MODEL?
 *
 * In your Role model (see app/Models/Role.php, the users() method), you define:
 *
 *   public function users() {
 *     return $this->belongsToMany(User::class)
 *         ->withPivot('is_active', 'notes')
 *         ->withTimestamps();
 *   }
 *
 * This tells Laravel/Eloquent: "A Role is related to many Users, through a pivot table (role_user). 
 * There may be extra info on that link (is_active, notes, timestamps)."
 *
 * BUT: This PHP code ONLY DEFINES HOW LARAVEL TREATS THE RELATIONSHIP in your application.
 * It does not create any database tables or columns!
 *
 * Laravel models and relationships describe what Eloquent *should do* in code:
 *   - How to join records
 *   - How to access extra pivot data (is_active, notes, timestamps)
 *   - How PHP code will "understand" the link
 *
 * However, the ACTUAL storage of those links—in other words, the real database table that holds which users have which roles—
 * must exist in the database. That's what this migration does!
 *
 * This migration creates the 'role_user' table in the database, with:
 *   - user_id and role_id columns (to store the links)
 *   - columns for any "extra" data you specified in withPivot (is_active, notes)
 *   - timestamps for when the link was created/updated
 *
 * In summary:
 *   - Model code (with belongsToMany and withPivot) = describes the relationship and what data can be attached to it in CODE.
 *   - Migration (this file) = physically CREATES the actual table, columns, and data structure in the database so the relationship can
 *     store those links and extra info.
 * Migration creates the columns in the database.
 * withPivot() tells Laravel to retrieve those extra pivot columns when loading the relationship.
 * withTimestamps() tells Laravel to automatically fill the created_at and updated_at columns when the link is created/updated.
 * unique(['user_id', 'role_id']) tells Laravel to prevent duplicate links between the same user and role.
 *
 * Without this migration, the code in your model WILL NOT WORK—there's nowhere to write the user↔role links or is_active/notes fields!
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // role_user = PIVOT (middle table) for User ↔ Role
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();

            // The two sides of every link
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            // Extra data ABOUT the link (not about user or role alone)
            // Read later as: $user->roles->first()->pivot->is_active
            $table->boolean('is_active')->default(true);
            $table->string('notes')->nullable();

            $table->timestamps();

            // Same user + same role only once
            $table->unique(['user_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
