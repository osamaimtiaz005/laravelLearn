<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seed roles + sample many-to-many links (role_user pivot).
 *
 * WHAT IS A PIVOT?
 *   Middle table role_user stores each User–Role link as one row.
 *   attach / syncWithoutDetaching below INSERT (or keep) those pivot rows.
 *
 * Run after users exist:
 *   php artisan migrate
 *   php artisan db:seed --class=RoleSeeder
 */

/**
 * Helpful Eloquent methods used in this seeder:
 *
 * - query(): Start a new query on the model.
 * - firstOrCreate(): Retrieve the first matching record or create it if it doesn't exist.
 * - first(): Retrieve the first record in the database.
 * - create(): Create a new database record.
 * - syncWithoutDetaching(): Attach records to a many-to-many relation without removing existing ones.
 * - sync(): Attach records and remove any others not in the array.
 * - detach(): Remove records from the many-to-many relation.
 * - attach(): Attach records to the many-to-many relation.
 */

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or get the 'Admin' role.
        //    - firstOrCreate() tries to find a Role by 'name' = 'Admin'.
        //    - If a role with that name does not exist, it creates one and sets 'label' to 'Administrator'.
        //    - $admin will now contain the Eloquent Role instance for 'Admin'.
        $admin = Role::query()->firstOrCreate(
            ['name' => 'Admin'],                 // Unique column/value to search by
            ['label' => 'Administrator']         // Additional columns to set if created
        );

        // 2. Create or get the 'Editor' role.
        //    - Same logic as above, but the name is 'Editor' and label is 'Content Editor'.
        $editor = Role::query()->firstOrCreate(
            ['name' => 'Editor'],
            ['label' => 'Content Editor']
        );

        // 3. Create or get the 'Viewer' role.
        //    - Same logic again for 'Viewer', label set to 'Read Only'.
        $viewer = Role::query()->firstOrCreate(
            ['name' => 'Viewer'],
            ['label' => 'Read Only']
        );

        // 4. Get the first existing user in the database.
        //    - This is so we can assign some roles to at least one user.
        $user = User::query()->first();

        // 5. If there are no users at all, stop here.
        //    - This check prevents errors if the users table is empty.
        if (! $user) {
            return;
        }

        // 6. Assign 'Admin' and 'Editor' roles to this first user.
        //    - syncWithoutDetaching() ensures existing roles are not removed—only these roles are added if not already set.
        //    - The array keys are role IDs. The values are pivot-data (extra columns in role_user table).
        //      'is_active' = true   ← marks the role as active for this user
        //      'notes' = 'RoleSeeder' ← a comment so we know this was from seeding
        //    - So, after this, the user will have both Admin and Editor roles (if not already present),
        //      and any other roles he/she had are kept.
        $user->roles()->syncWithoutDetaching([
            $admin->id => ['is_active' => true, 'notes' => 'RoleSeeder'],
            $editor->id => ['is_active' => true, 'notes' => 'RoleSeeder'],
        ]);

        // 7. Find a second user (with a *different* id than the first user).
        //    - This shows many-to-many works with more than one user.
        $user2 = User::query()->where('id', '!=', $user->id)->first();

        // 8. If a second user exists, assign some different roles.
        //    - This demo assigns 'Admin' and 'Viewer' roles to user2.
        //    - Again, syncWithoutDetaching() preserves any existing roles.
        //    - Each role link includes is_active=true and a note.
        if ($user2) {
            $user2->roles()->syncWithoutDetaching([
                $admin->id => ['is_active' => true, 'notes' => 'RoleSeeder'],
                $viewer->id => ['is_active' => true, 'notes' => 'RoleSeeder'],
            ]);
        }
        // The result: 
        // - At least two users now have multiple roles with relevant pivot data set.
        // - No existing roles get removed from either user (helpful if you re-seed).
    }
}
