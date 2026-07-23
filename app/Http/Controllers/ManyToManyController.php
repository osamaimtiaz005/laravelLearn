<?php

// ============================================================
// FILE: ManyToManyController.php
// PURPOSE: Teach MANY-TO-MANY (User ↔ Role) step by step
// ============================================================

// namespace = this class lives in the Controllers folder
namespace App\Http\Controllers;

// use = import models so we can write Role / User (not full path each time)
use App\Models\Role; // Role model → roles table
use App\Models\Team;
use App\Models\User; // User model → users table

/**
 * ============================================================
 * MANY-TO-MANY — word by word + DRY RUN for beginners
 * ============================================================
 *
 * Idea:
 *   One User can have many Roles (Admin, Editor).
 *   One Role can belong to many Users (many Admins).
 *
 * WHAT IS A PIVOT?
 *   Middle / bridge table that connects the two sides.
 *   Table name here: role_user
 *
 *   users          role_user (PIVOT)           roles
 *   id=1 Ali       user_id=1, role_id=1   →    Admin
 *                  user_id=1, role_id=2   →    Editor
 *   id=2 Sara      user_id=2, role_id=1   →    Admin
 *
 *   Each pivot ROW = one link (“Ali is Admin”).
 *   Extra columns (is_active, notes) = info about THAT link.
 *
 *   $user->roles;                 // reads through pivot
 *   $role->pivot->is_active;      // extra pivot field
 *   $user->roles()->attach(1);    // INSERT pivot row
 *   $user->roles()->detach(1);    // DELETE pivot row
 *
 *   Short: pivot = “who is linked to whom” (+ optional link data).
 *
 * Laravel both sides:
 *   User::roles() → belongsToMany(Role::class)
 *   Role::users() → belongsToMany(User::class)
 *
 * SAMPLE DATA used in DRY RUN comments below:
 *   users:  1=Ali, 2=Sara
 *   roles:  1=Admin, 2=Editor, 3=Viewer
 *
 * Symbols you will see below:
 *   ::   = call a method on the CLASS (static), e.g. User::find(1)
 *   ->   = call a method on an OBJECT, e.g. $user->roles()
 *   []   = array
 *   fn () =>  = short arrow function (one-line function)
 *
 * Useful methods in this file:
 *   attach, detach, sync, syncWithoutDetaching, toggle,
 *   updateExistingPivot, withPivot, wherePivot
 */
class ManyToManyController extends Controller
{
    // --------------------------------------------------------
    // METHOD: index
    // URL:    GET /many-to-many
    // RETURN: HTML page (Blade view)
    //
    // DRY RUN:
    //   1) seedRolesIfNeeded() — if roles empty, create Admin/Editor/Viewer
    //      and link Ali → Admin+Viewer, Sara → Admin
    //   2) $users = all users with roles loaded
    //      e.g. Ali { roles_count:2, roles:[Admin, Viewer] }
    //   3) $roles = all roles with users loaded
    //      e.g. Admin { users_count:2, users:[Ali, Sara] }
    //   4) $sampleUser = first user who has roles (Ali)
    //   5) $sampleRole = first role who has users (Admin)
    //   6) Blade gets $users, $roles, $sampleUser, $sampleRole
    // --------------------------------------------------------
    public function index()
    {
        // $this = this controller object
        // ->seedRolesIfNeeded() = create sample roles/links if DB empty
        $this->seedRolesIfNeeded();

        // User::with('roles')
        //   User::        = start from users table
        //   with('roles') = ALSO load related roles (uses pivot role_user)
        //   withCount('roles') = add roles_count field to each user
        //   get()         = fetch ALL matching rows as a Collection
        $users = User::with('roles')->withCount('roles')->get();

        // Same idea from the other side: Role → many Users
        $roles = Role::with('users')->withCount('users')->get();

        // firstWhere(fn ($u) => ...)
        //   walk the collection; return FIRST user where condition is true
        //   fn ($u) => $u->roles_count > 0  means: user who already has roles
        // ?? $users->first()
        //   if none found, use the first user anyway (or null if no users)
        $sampleUser = $users->firstWhere(fn ($u) => $u->roles_count > 0) ?? $users->first();
        $sampleRole = $roles->firstWhere(fn ($r) => $r->users_count > 0) ?? $roles->first();

        // return view('many_to_many.index', [...])
        //   'many_to_many.index' = resources/views/many_to_many/index.blade.php
        //   array keys become variables in Blade: $users, $roles, ...
        return view('many_to_many.index', [
            'users' => $users,
            'roles' => $roles,
            'sampleUser' => $sampleUser,
            'sampleRole' => $sampleRole,
        ]);
    }

    // --------------------------------------------------------
    // METHOD: users
    // URL:    GET /many-to-many/users
    // RETURN: JSON — all users + their roles (FORWARD direction)
    //
    // DRY RUN:
    //   Browser → /many-to-many/users
    //   Return roughly:
    //   [
    //     { id:1, name:"Ali",  roles_count:2, roles:[{name:"Admin"},{name:"Viewer"}] },
    //     { id:2, name:"Sara", roles_count:1, roles:[{name:"Admin"}] }
    //   ]
    // --------------------------------------------------------
    public function users()
    {
        $this->seedRolesIfNeeded();

        // Laravel auto-converts this Collection to JSON in the browser
        return User::with('roles')->withCount('roles')->get();
    }

    // --------------------------------------------------------
    // METHOD: roles
    // URL:    GET /many-to-many/roles
    // RETURN: JSON — all roles + their users (BACKWARD direction)
    //
    // DRY RUN:
    //   [
    //     { id:1, name:"Admin",  users_count:2, users:[{name:"Ali"},{name:"Sara"}] },
    //     { id:2, name:"Editor", users_count:0, users:[] },
    //     { id:3, name:"Viewer", users_count:1, users:[{name:"Ali"}] }
    //   ]
    // --------------------------------------------------------
    public function roles()
    {
        $this->seedRolesIfNeeded();

        return Role::with('users')->withCount('users')->get();
    }

    // --------------------------------------------------------
    // METHOD: userRoles
    // URL:    GET /many-to-many/user/{id}   e.g. /many-to-many/user/1
    // PARAM:  $id = user id from the URL
    // RETURN: JSON — one user + many roles (FORWARD Two-Way Test)
    //
    // DRY RUN (id=1 Ali):
    //   1) find Ali + load roles through pivot
    //   2) roles_count = 2
    //   3) pivot_example becomes:
    //      [
    //        { role:"Admin",  is_active:true, notes:"seed" },
    //        { role:"Viewer", is_active:true, notes:"seed" }
    //      ]
    //   4) JSON proves FORWARD: one user → many roles
    // --------------------------------------------------------
    public function userRoles($id)
    {
        // find($id) = SELECT * FROM users WHERE id = $id LIMIT 1
        // with('roles') = also load roles through pivot
        // Result: User model OR null if not found
        $user = User::with('roles')->withCount('roles')->find($id);

        // ! $user means: if $user is null / not found
        if (! $user) {
            // response()->json(data, status)
            //   404 = Not Found HTTP code
            return response()->json(['message' => 'User not found'], 404);
        }

        // response()->json([...]) = send JSON to browser
        return response()->json([
            // Teaching block for the Two-Way Test (forward)
            'two_way_test' => [
                'direction' => 'FORWARD',
                'question' => 'Can one user have many roles?',
                // . = string join in PHP
                'answer' => 'True — this user has '.$user->roles_count.' role(s)',
            ],
            'user' => $user,

            // $user->roles->map(fn ($role) => [...])
            //   map = turn each role into a small array
            //   $role->pivot = the role_user ROW for this user+role link
            //   $role->pivot->is_active = extra column on that pivot row
            'pivot_example' => $user->roles->map(fn ($role) => [
                'role' => $role->name,
                'is_active' => $role->pivot->is_active,
                'notes' => $role->pivot->notes,
            ]),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: roleUsers
    // URL:    GET /many-to-many/role/{id}
    // PARAM:  $id = role id from the URL
    // RETURN: JSON — one role + many users (BACKWARD Two-Way Test)
    //
    // DRY RUN (id=1 Admin):
    //   1) find Admin + load users via pivot
    //   2) users_count = 2 (Ali, Sara)
    //   3) JSON proves BACKWARD: one role → many users
    // --------------------------------------------------------
    public function roleUsers($id)
    {
        // Start from Role; load all users linked via pivot
        $role = Role::with('users')->withCount('users')->find($id);

        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json([
            'two_way_test' => [
                'direction' => 'BACKWARD',
                'question' => 'Can one role belong to many users?',
                'answer' => 'True — this role has '.$role->users_count.' user(s)',
            ],
            'role' => $role,
        ]);
    }

    // --------------------------------------------------------
    // METHOD: attach
    // URL:    GET /many-to-many/attach/{userId}/{roleId}
    // MEANING: ADD a link in the pivot (keep other roles)
    //
    // DRY RUN: attach Editor (2) to Ali (1)
    //
    // BEFORE role_user:
    //   user_id | role_id
    //        1  | 1 (Admin)
    //        1  | 3 (Viewer)
    //
    // CODE RUNS:
    //   $user = Ali (id 1)
    //   $role = Editor (id 2)
    //   syncWithoutDetaching([ 2 => [is_active=>true, notes=>...] ])
    //
    // AFTER role_user:
    //   user_id | role_id
    //        1  | 1 (Admin)   ← kept
    //        1  | 3 (Viewer)  ← kept
    //        1  | 2 (Editor)  ← ADDED
    //
    // JSON roles pluck: ["Admin", "Viewer", "Editor"]
    //
    // Note: attach() alone can error if link already exists (unique).
    //       syncWithoutDetaching is safer when you click the demo twice.
    // --------------------------------------------------------
    public function attach($userId, $roleId)
    {
        // Find user and role by primary key (or null)
        $user = User::find($userId);
        $role = Role::find($roleId);

        // || means OR — if either is missing, stop
        if (! $user || ! $role) {
            return response()->json(['message' => 'User or Role not found'], 404);
        }

        // $user->roles()
        //   WITH () = relationship QUERY builder (for attach/detach/sync)
        //   WITHOUT () $user->roles = already loaded Collection
        //
        // syncWithoutDetaching([ roleId => [pivot columns] ])
        //   ADD this role if missing
        //   KEEP all other roles
        //   set is_active / notes on the pivot row
        //
        // Why not always attach()?
        //   attach() can error if the unique (user_id, role_id) already exists
        //   syncWithoutDetaching is safer for demos you click twice
        $user->roles()->syncWithoutDetaching([
            $role->id => [
                'is_active' => true,
                'notes' => 'attached via demo',
            ],
        ]);
        // Same idea as:
        //   $user->roles()->attach($role->id, ['is_active' => true, 'notes' => '...']);

        // load('roles') = refresh related roles on this $user object
        // (so the JSON below shows the new list)
        $user->load('roles');

        return response()->json([
            'method' => 'attach / syncWithoutDetaching',
            'meaning' => 'Add this role to the user; keep other roles',
            'user_id' => $user->id,
            // pluck('name') = list of only the name column values
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: detach
    // URL:    GET /many-to-many/detach/{userId}/{roleId}
    // MEANING: DELETE one pivot link (other roles stay)
    //
    // DRY RUN: detach Editor (2) from Ali (1)
    //
    // BEFORE:
    //   1|1 Admin
    //   1|2 Editor
    //   1|3 Viewer
    //
    // RUN: $user->roles()->detach(2);
    //   SQL idea: DELETE FROM role_user WHERE user_id=1 AND role_id=2
    //
    // AFTER:
    //   1|1 Admin   ← kept
    //   1|3 Viewer  ← kept
    //   (Editor link gone)
    //
    // detach() with NO id would wipe ALL of Ali's roles.
    // --------------------------------------------------------
    public function detach($userId, $roleId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // detach($roleId)
        //   DELETE FROM role_user WHERE user_id = this user AND role_id = $roleId
        //   Other pivot rows for this user are NOT deleted
        //
        // detach() with no argument
        //   would remove ALL roles for this user
        $user->roles()->detach($roleId);

        // Reload roles after change
        // load() refreshes relationship data on the model
        $user->load('roles');

        return response()->json([
            'method' => 'detach',
            'meaning' => 'Remove this role from the user; other roles stay',
            'user_id' => $user->id,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: sync
    // URL:    GET /many-to-many/sync/{userId}/{roleIds}
    // EXAMPLE: /many-to-many/sync/1/1,2
    // MEANING: user's roles become EXACTLY this list (drops others)
    //
    // DRY RUN: sync Ali to roles 2 and 3 only
    //
    // URL gives $roleIds = "2,3" (string)
    //
    // STEP A — parse string to array of ints:
    //   explode(',', "2,3")     → ["2", "3"]
    //   map (int) trim          → [2, 3]
    //   $ids = [2, 3]
    //
    // STEP B — pivot BEFORE (Ali):
    //   user_id | role_id
    //        1  | 1  Admin
    //        1  | 2  Editor
    //        1  | 6  (example extra)
    //
    //   Current set: {1, 2, 6}
    //   Desired set: {2, 3}
    //
    // STEP C — Laravel compares and:
    //   REMOVE  1 and 6   (in current, not in desired)
    //   KEEP    2         (in both)
    //   INSERT  3         (in desired, not in current)
    //
    // STEP D — pivot AFTER:
    //   user_id | role_id
    //        1  | 2
    //        1  | 3
    //
    // That is why it is called sync: DB matches the array exactly.
    // Different from attach (attach only adds, never removes extras).
    // --------------------------------------------------------
    public function sync($userId, $roleIds)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // $roleIds comes from URL as a STRING like "1,2"
        //
        // explode(',', $roleIds)
        //   split string by comma → ["1", "2"]
        //
        // collect(...)
        //   turn array into Laravel Collection (has map/filter helpers)
        //
        // ->map(fn ($id) => (int) trim($id))
        //   trim = remove spaces
        //   (int) = cast string "1" to number 1
        //
        // ->filter()
        //   remove empty / zero values
        //
        // ->values()->all()
        //   reindex keys and return a plain PHP array [1, 2]
        $ids = collect(explode(',', $roleIds))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->values()
            ->all();

        // sync([1, 2])
        //   After this call, user has ONLY roles 1 and 2
        //   Any other role links are REMOVED from the pivot
        //   Different from attach (attach only adds)
        $user->roles()->sync($ids);
        $user->load('roles');

        return response()->json([
            'method' => 'sync',
            'meaning' => 'Replace all roles with exactly this set',
            'synced_ids' => $ids,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: toggle
    // URL:    GET /many-to-many/toggle/{userId}/{roleId}
    // MEANING: if linked → unlink; if not linked → link (flip)
    //
    // DRY RUN A — Ali already has Editor (2):
    //   BEFORE: 1|1, 1|2, 1|3
    //   toggle(2) → DETACH 2
    //   AFTER:  1|1, 1|3
    //
    // DRY RUN B — click toggle again (Editor missing):
    //   BEFORE: 1|1, 1|3
    //   toggle(2) → ATTACH 2
    //   AFTER:  1|1, 1|3, 1|2
    //
    // Like a checkbox: checked ↔ unchecked
    // --------------------------------------------------------
    public function toggle($userId, $roleId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // toggle([$roleId])
        //   checks the pivot:
        //   - row exists → detach it
        //   - row missing → attach it
        // Good for checkbox-style UI
        $user->roles()->toggle([$roleId]);
        $user->load('roles');

        return response()->json([
            'method' => 'toggle',
            'meaning' => 'Flip membership for this role',
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: updatePivot
    // URL:    GET /many-to-many/pivot/{userId}/{roleId}
    // MEANING: change EXTRA columns on an existing pivot row
    //          (does NOT add/remove the role link itself)
    //
    // DRY RUN: update Ali–Admin pivot
    //
    // BEFORE role_user row:
    //   user_id=1, role_id=1, is_active=1, notes="seed"
    //
    // CHECK: does Ali have role 1? exists() → true
    //
    // RUN: updateExistingPivot(1, [is_active=>false, notes=>...])
    //   SQL idea:
    //   UPDATE role_user
    //   SET is_active=0, notes='updated via updateExistingPivot demo'
    //   WHERE user_id=1 AND role_id=1
    //
    // AFTER same link still exists, data changed:
    //   user_id=1, role_id=1, is_active=0, notes="updated via..."
    //
    // If Ali did NOT have Admin → return 422 "Attach first"
    // --------------------------------------------------------
    public function updatePivot($userId, $roleId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check the link exists before updating
        // $user->roles() = query on the relationship
        // ->where('roles.id', $roleId) = only this role
        // ->exists() = true if at least one matching pivot row
        if (! $user->roles()->where('roles.id', $roleId)->exists()) {
            // 422 = Unprocessable — request understood but cannot proceed
            return response()->json([
                'message' => 'User does not have this role yet. Attach first.',
            ], 422);
        }

        // updateExistingPivot($roleId, [columns])
        //   UPDATE role_user SET is_active=0, notes='...'
        //   WHERE user_id = this user AND role_id = $roleId
        //   The link stays; only pivot DATA changes
        $user->roles()->updateExistingPivot($roleId, [
            'is_active' => false,
            'notes' => 'updated via updateExistingPivot demo',
        ]);

        $user->load('roles');

        return response()->json([
            'method' => 'updateExistingPivot',
            'meaning' => 'Update pivot columns without changing which roles are linked',
            // firstWhere('id', ...) = find role in collection by id
            // ?->pivot = null-safe: if role missing, do not error; return null
            'pivot' => $user->roles->firstWhere('id', (int) $roleId)?->pivot,
        ]);
    }

    // --------------------------------------------------------
    // METHOD: wherePivot
    // URL:    GET /many-to-many/where-pivot/{userId}
    // MEANING: filter related roles BY pivot column values
    //
    // DRY RUN (Ali):
    //   Pivot rows:
    //     Admin  is_active=1
    //     Editor is_active=0   (after updatePivot demo)
    //     Viewer is_active=1
    //
    //   wherePivot('is_active', true)  → ["Admin", "Viewer"]
    //   wherePivot('is_active', false) → ["Editor"]
    //   all_roles shows name + pivot fields for each
    // --------------------------------------------------------
    public function wherePivot($userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'method' => 'wherePivot',

            // wherePivot('is_active', true)
            //   only roles where the pivot row has is_active = 1
            'active_roles' => $user->roles()->wherePivot('is_active', true)->pluck('name'),

            // same idea for inactive links
            'inactive_roles' => $user->roles()->wherePivot('is_active', false)->pluck('name'),

            // Show all roles with their pivot extras
            // (bool) casts 0/1 to false/true for clearer JSON
            'all_roles' => $user->roles->map(fn ($r) => [
                'name' => $r->name,
                'is_active' => (bool) $r->pivot->is_active,
                'notes' => $r->pivot->notes,
            ]),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: seedRolesIfNeeded (PRIVATE)
    // private = only this class can call it (not from routes)
    // : void  = returns nothing
    // PURPOSE: create Admin/Editor/Viewer + sample pivot links once
    //
    // DRY RUN (first visit when roles table empty):
    //   1) exists()? false → continue
    //   2) INSERT roles: Admin(1), Editor(2), Viewer(3)
    //   3) first user = Ali (id 1)
    //   4) attach Admin+Viewer to Ali
    //        role_user: (1,1), (1,3)     ← FORWARD many roles
    //   5) second user = Sara (id 2)
    //   6) attach Admin to Sara
    //        role_user: (2,1)            ← BACKWARD many users on Admin
    //   7) Editor exists but may have 0 users yet
    //
    // Second visit: exists()? true → return immediately (no duplicate seed)
    // --------------------------------------------------------
    private function seedRolesIfNeeded(): void
    {
        // Role::query()->exists()
        //   true if roles table has at least one row
        if (Role::query()->exists()) {
            // Already seeded — stop here
            return;
        }

        // create([...]) = INSERT into roles and return Role model
        $admin = Role::query()->create(['name' => 'Admin', 'label' => 'Administrator']);
        $editor = Role::query()->create(['name' => 'Editor', 'label' => 'Content Editor']);
        $viewer = Role::query()->create(['name' => 'Viewer', 'label' => 'Read Only']);

        // Need at least one user to create pivot links
        $user = User::query()->first();
        if (! $user) {
            return;
        }

        // attach([ id => [pivot data], id2 => [...] ])
        //   INSERT multiple rows into role_user in one call
        //   Proves FORWARD: one user → many roles
        $user->roles()->attach([
            $admin->id => ['is_active' => true, 'notes' => 'seed'],
            $viewer->id => ['is_active' => true, 'notes' => 'seed'],
        ]);

        // Second user also gets Admin
        // Proves BACKWARD: one role (Admin) → many users
        // where('id', '!=', $user->id) = any user that is NOT the first one
        $user2 = User::query()->where('id', '!=', $user->id)->first();
        if ($user2) {
            // attach(singleId, [pivot columns])
            $user2->roles()->attach($admin->id, [
                'is_active' => true,
                'notes' => 'seed second admin',
            ]);
        }

        // Editor was created so it exists for demos even if unused yet
        // unset($editor) = drop the local variable (role stays in DB)
        unset($editor);
    }

    /**
     * GET /many-to-many/teams (or whatever route you wired)
     *
     * WHY THE ERROR HAPPENED:
     *   unique(team_id, user_id) on team_user means one user can join
     *   the same team only ONCE.
     *   attach() always INSERTs → second run (or random() picks same team)
     *   → Duplicate entry '2-1'
     *
     * FIX: syncWithoutDetaching() = add link if missing, ignore if exists
     *   (same idea we used for roles)
     */
    public function teams()
    {
        $users = User::all();
        $teams = Team::all();

        if ($teams->isEmpty()) {
            return response()->json(['message' => 'No teams found. Seed teams first.'], 404);
        }

        foreach ($users as $user) {
            // BAD (your old code): always INSERT → can duplicate
            // $user->teams()->attach($teams->random()->id);

            // GOOD: add only if not already linked
            $user->teams()->syncWithoutDetaching([
                $teams->random()->id,
            ]);
        }

        // User::with('teams')->get() = all users + their teams
        // NOT $user->with(...) — with() is a static/query starter on the model class
        return response()->json([
            'message' => 'Linked each user to a random team (no duplicates)',
            'user_teams' => User::with('teams')->get(),
        ]);
    }
}
