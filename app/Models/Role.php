<?php

// ============================================================
// FILE: Role.php — MANY-TO-MANY with User (belongsToMany)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * MANY-TO-MANY (Role side)
 * ============================================================
 *
 * WHAT IS A PIVOT?
 *   Middle / bridge table that stores links between two models.
 *   Here: role_user connects users ↔ roles.
 *
 *   users          role_user (PIVOT)           roles
 *   id=1 Ali       user_id=1, role_id=1   →    Admin
 *                  user_id=1, role_id=2   →    Editor
 *
 *   Each pivot ROW = one link (“Ali is Admin”).
 *
 *   $user->roles()->attach(1);     // insert pivot row
 *   $user->roles()->detach(1);     // delete pivot row
 *   $role->pivot->is_active;       // extra column on that link
 *
 * SHORT: pivot = “who is linked to whom” (+ optional link data).
 *
 * Two-Way Test:
 *   One role (Admin) → many users     True
 *   One user → many roles             True (on User model)
 *   → belongsToMany both sides
 *
 * Useful: attach / detach / sync / toggle / updateExistingPivot /
 *         withPivot / withTimestamps / wherePivot
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

    /**
     * MANY-TO-MANY: Role → Users
     *
     * return $this->belongsToMany(User::class)
     *   - This sets up the many-to-many relationship through the pivot table (role_user).
     *  withPivot() tells Laravel to retrieve those extra pivot columns when loading the relationship.

     * ->withPivot('is_active', 'notes')
     *   - How does withPivot know which table the extra columns come from?
     *       - By default, belongsToMany infers the pivot table name based on the two related models, using alphabetical order.
     *       - For Role ↔ User, this is role_user (see migration).
     *       - withPivot('is_active', 'notes') tells Eloquent to include the columns 'is_active' and 'notes' from the pivot table (role_user) for every relationship result.
     *       - If you use custom table/column names, you can pass them to belongsToMany:
     *           return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
     *       - But if you don't specify, Laravel convention is used.
     *       - Example: $role->users->first()->pivot->is_active reads from role_user.is_active for this link.
     *
     * ->withTimestamps()
     *   - Fills the pivot's created_at and updated_at columns on every insert/update for role_user.
     *
     * Example:
     *   $role = Role::where('name', 'Admin')->first();
     *   $role->users; // Retrieves users with pivot (role_user) data: is_active, notes, timestamps, etc.
     */


    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_active', 'notes')
            ->withTimestamps();

        // Full form:
        // return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
        //     ->withPivot('is_active', 'notes')
        //     ->withTimestamps();
    }
}
