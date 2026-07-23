<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Many-to-Many</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 860px; margin: 2rem auto; padding: 0 1rem; line-height: 1.55; color: #222; }
        h1 { margin-bottom: .25rem; }
        .sub { color: #666; margin-bottom: 1.5rem; }
        section { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.2rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 .6rem; font-size: 1.15rem; }
        h3 { margin: 1rem 0 .4rem; font-size: 1rem; }
        code, pre { background: #f4f4f4; border-radius: 4px; }
        code { padding: 0 .25rem; }
        pre { padding: .75rem; overflow-x: auto; }
        .row { padding: .6rem 0; border-top: 1px solid #eee; }
        .row:first-of-type { border-top: 0; padding-top: 0; }
        .muted { color: #666; font-size: .85rem; }
        .bad { color: #a33; }
        .good { color: #1a7a3c; font-weight: 600; }
        .box { background: #f8f9fb; border: 1px solid #c9d3e3; border-radius: 8px; padding: 1rem; margin: .75rem 0; }
        .box.alt { background: #f8faf8; border-color: #cfe3d4; }
        .pass { background: #eef2f8; border: 1px solid #c9d3e3; border-radius: 8px; padding: .9rem 1rem; }
        ul, ol { margin: .4rem 0 .8rem; padding-left: 1.2rem; }
        table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        th, td { border: 1px solid #ddd; padding: .45rem .55rem; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

    <h1>Many-to-Many</h1>
    <p class="sub">User ↔ Role. Pivot table <code>role_user</code>. Method: <code>belongsToMany</code>.</p>

    <section>
        <h2>What is many-to-many?</h2>
        <pre>User Ali ──┬── Role Admin
           └── Role Editor

Role Admin ──┬── User Ali
             └── User Sara

Both directions can be "many".</pre>
        <p class="good">Needs a <strong>pivot</strong> table — not a single <code>user_id</code> on roles.</p>
        <p class="muted">This is why “User buys Product” is many-to-many, not one-to-many.</p>
    </section>

    <section>
        <h2>What is a pivot?</h2>
        <p>A <strong>pivot</strong> is the <strong>middle table</strong> that connects two tables in a many-to-many relationship.</p>

        <h3>Why you need it</h3>
        <p><code>users</code> and <code>roles</code> cannot both store “many” links with a single <code>user_id</code> or <code>role_id</code> on the other table.</p>
        <p>So you add a bridge table:</p>
        <pre>users          role_user (PIVOT)           roles
-----          -----------------           -----
id=1 Ali       user_id=1, role_id=1   →    id=1 Admin
               user_id=1, role_id=2   →    id=2 Editor
id=2 Sara      user_id=2, role_id=1   →    id=1 Admin</pre>
        <p>Each <strong>row in the pivot</strong> = one link (“Ali is Admin”).</p>

        <h3>In this project</h3>
        <ul>
            <li>Pivot table name: <code>role_user</code></li>
            <li>Columns: <code>user_id</code>, <code>role_id</code></li>
            <li>Extra data on the link: <code>is_active</code>, <code>notes</code>, timestamps</li>
        </ul>

        <h3>In Laravel</h3>
        <pre>$user->roles;                    // uses the pivot behind the scenes
$role->pivot->is_active;         // extra columns from role_user
$user->roles()->attach(1);       // insert a pivot row
$user->roles()->detach(1);       // delete a pivot row</pre>
        <p class="good">Short version: pivot = the join table that stores “who is linked to whom” (and optional info about that link).</p>
    </section>

    <section>
        <h2>Two-Way Test</h2>
        <div class="box alt">
            <h3>Forward — User → Roles</h3>
            <p><strong>Question:</strong> Can one user have many roles?</p>
            <p class="good">True</p>
            <pre>$user->roles;   // belongsToMany — a list</pre>
            @if ($sampleUser)
                <p>Live: <strong>{{ $sampleUser->name }}</strong> has
                    <strong>{{ $sampleUser->roles_count }}</strong> role(s)
                    @if ($sampleUser->roles_count)
                        ({{ $sampleUser->roles->pluck('name')->join(', ') }})
                    @endif
                </p>
            @endif
        </div>
        <div class="box">
            <h3>Backward — Role → Users</h3>
            <p><strong>Question:</strong> Can one role belong to many users?</p>
            <p class="good">True</p>
            <pre>$role->users;   // belongsToMany — a list</pre>
            @if ($sampleRole)
                <p>Live: Role <strong>{{ $sampleRole->name }}</strong> has
                    <strong>{{ $sampleRole->users_count }}</strong> user(s)</p>
            @endif
        </div>
        <div class="pass">
            Forward True + Backward True → <span class="good">many-to-many</span>
            (compare Orders: backward is only <em>one</em> user → one-to-many).
        </div>
    </section>

    <section>
        <h2>Compare relationship types</h2>
        <table>
            <tr>
                <th>Type</th>
                <th>Forward</th>
                <th>Backward</th>
                <th>Laravel</th>
            </tr>
            <tr>
                <td>One-to-one</td>
                <td>1 profile</td>
                <td>1 user</td>
                <td>hasOne / belongsTo</td>
            </tr>
            <tr>
                <td>One-to-many</td>
                <td>many orders</td>
                <td>1 user</td>
                <td>hasMany / belongsTo</td>
            </tr>
            <tr>
                <td>Many-to-many</td>
                <td>many roles</td>
                <td>many users</td>
                <td>belongsToMany / belongsToMany</td>
            </tr>
        </table>
    </section>

    <section>
        <h2>Useful belongsToMany methods</h2>
        <table>
            <tr><th>Method</th><th>Meaning</th><th>Try</th></tr>
            <tr>
                <td><code>attach</code> / <code>syncWithoutDetaching</code></td>
                <td>Add link(s); keep others</td>
                <td><a href="{{ route('many-to-many.attach', [$sampleUser->id ?? 1, $sampleRole->id ?? 1]) }}">attach</a></td>
            </tr>
            <tr>
                <td><code>detach</code></td>
                <td>Remove one link (or all)</td>
                <td><a href="{{ route('many-to-many.detach', [$sampleUser->id ?? 1, $sampleRole->id ?? 1]) }}">detach</a></td>
            </tr>
            <tr>
                <td><code>sync([1,2])</code></td>
                <td>Exact set — drops roles not in list</td>
                <td><a href="{{ route('many-to-many.sync', [$sampleUser->id ?? 1, '1,2']) }}">sync 1,2</a></td>
            </tr>
            <tr>
                <td><code>toggle</code></td>
                <td>Add if missing / remove if present</td>
                <td><a href="{{ route('many-to-many.toggle', [$sampleUser->id ?? 1, $sampleRole->id ?? 1]) }}">toggle</a></td>
            </tr>
            <tr>
                <td><code>updateExistingPivot</code></td>
                <td>Change pivot columns only</td>
                <td><a href="{{ route('many-to-many.pivot', [$sampleUser->id ?? 1, $sampleRole->id ?? 1]) }}">update pivot</a></td>
            </tr>
            <tr>
                <td><code>wherePivot</code></td>
                <td>Filter by pivot values</td>
                <td><a href="{{ route('many-to-many.where-pivot', $sampleUser->id ?? 1) }}">wherePivot</a></td>
            </tr>
            <tr>
                <td><code>withPivot</code> / <code>withTimestamps</code></td>
                <td>Expose extra pivot fields</td>
                <td>defined on the relationship</td>
            </tr>
            <tr>
                <td><code>with('roles')</code> / <code>withCount</code></td>
                <td>Eager load / count</td>
                <td><a href="{{ route('many-to-many.users') }}">/users</a></td>
            </tr>
        </table>
        <pre>// Code cheatsheet
$user->roles()->attach(1);
$user->roles()->attach(1, ['notes' => 'vip']);
$user->roles()->detach(1);
$user->roles()->sync([1, 2]);
$user->roles()->syncWithoutDetaching([3]);
$user->roles()->toggle([1]);
$user->roles()->updateExistingPivot(1, ['is_active' => false]);
$user->roles()->wherePivot('is_active', true)->get();
$role = $user->roles->first();
$role->pivot->is_active;   // extra column from role_user
$role->pivot->notes;</pre>
    </section>

    <section>
        <h2>How to test</h2>
        <ol>
            <li><strong>Forward:</strong> <a href="{{ route('many-to-many.user', $sampleUser->id ?? 1) }}">/many-to-many/user/{{ $sampleUser->id ?? 1 }}</a> — many roles</li>
            <li><strong>Backward:</strong> <a href="{{ route('many-to-many.role', $sampleRole->id ?? 1) }}">/many-to-many/role/{{ $sampleRole->id ?? 1 }}</a> — many users</li>
            <li>Try attach / detach / sync / toggle links above</li>
            <li>Related lessons: <a href="{{ route('one-to-many.index') }}">one-to-many</a> · <a href="{{ route('many-to-one.index') }}">many-to-one</a></li>
        </ol>
    </section>

    <section>
        <h2>All routes</h2>
        <p><a href="{{ route('many-to-many.index') }}">/many-to-many</a></p>
        <p><a href="{{ route('many-to-many.users') }}">/many-to-many/users</a></p>
        <p><a href="{{ route('many-to-many.roles') }}">/many-to-many/roles</a></p>
        <p><a href="{{ route('many-to-many.user', 1) }}">/many-to-many/user/1</a></p>
        <p><a href="{{ route('many-to-many.role', 1) }}">/many-to-many/role/1</a></p>
        <p><a href="{{ route('many-to-many.attach', [1, 1]) }}">/many-to-many/attach/1/1</a></p>
        <p><a href="{{ route('many-to-many.detach', [1, 1]) }}">/many-to-many/detach/1/1</a></p>
        <p><a href="{{ route('many-to-many.sync', [1, '1,2']) }}">/many-to-many/sync/1/1,2</a></p>
        <p><a href="{{ route('many-to-many.toggle', [1, 2]) }}">/many-to-many/toggle/1/2</a></p>
        <p><a href="{{ route('many-to-many.pivot', [1, 1]) }}">/many-to-many/pivot/1/1</a></p>
        <p><a href="{{ route('many-to-many.where-pivot', 1) }}">/many-to-many/where-pivot/1</a></p>
    </section>

    <section>
        <h2>Live — Users + roles (forward)</h2>
        @forelse ($users as $user)
            <div class="row">
                <div class="muted">User #{{ $user->id }} · {{ $user->roles_count }} role(s)</div>
                <strong>{{ $user->name }}</strong>
                @forelse ($user->roles as $role)
                    <div>— {{ $role->name }}
                        <span class="muted">
                            (active: {{ $role->pivot->is_active ? 'yes' : 'no' }}
                            @if ($role->pivot->notes)
                                · {{ $role->pivot->notes }}
                            @endif)
                        </span>
                    </div>
                @empty
                    <div class="bad">No roles</div>
                @endforelse
            </div>
        @empty
            <p>No users.</p>
        @endforelse
    </section>

    <section>
        <h2>Live — Roles + users (backward)</h2>
        @forelse ($roles as $role)
            <div class="row">
                <div class="muted">Role #{{ $role->id }} · {{ $role->users_count }} user(s)</div>
                <strong>{{ $role->name }}</strong> — {{ $role->label }}
                @forelse ($role->users as $user)
                    <div>— {{ $user->name }}</div>
                @empty
                    <div class="bad">No users yet</div>
                @endforelse
            </div>
        @empty
            <p>No roles. Refresh this page to auto-seed, or run <code>php artisan db:seed --class=RoleSeeder</code></p>
        @endforelse
    </section>

</body>
</html>
