<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One-to-One</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; color: #222; }
        h1 { margin-bottom: .25rem; }
        .sub { color: #666; margin-bottom: 1.5rem; }
        section { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.2rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 .6rem; font-size: 1.1rem; }
        code, pre { background: #f4f4f4; border-radius: 4px; }
        code { padding: 0 .25rem; }
        pre { padding: .75rem; overflow-x: auto; }
        .row { padding: .6rem 0; border-top: 1px solid #eee; }
        .row:first-of-type { border-top: 0; padding-top: 0; }
        .muted { color: #666; font-size: .85rem; }
        .bad { color: #a33; }
        .good { color: #1a7a3c; }
    </style>
</head>
<body>

    <h1>One-to-One</h1>
    <p class="sub">1 User has 1 Profile. Linked by <code>profiles.user_id</code>.</p>

    <section>
        <h2>Try these routes</h2>
        <p><a href="{{ route('one-to-one.index') }}">/one-to-one</a> — this page</p>
        <p><a href="{{ route('one-to-one.users') }}">/one-to-one/users</a> — all users + profile</p>
        <p><a href="{{ route('one-to-one.profiles') }}">/one-to-one/profiles</a> — all profiles + user</p>
        <p><a href="{{ route('one-to-one.user', 1) }}">/one-to-one/user/1</a> — one user + profile</p>
        <p><a href="{{ route('one-to-one.profile', 1) }}">/one-to-one/profile/1</a> — one profile + user</p>
        <p><a href="{{ route('one-to-one.create', 1) }}">/one-to-one/create/1</a> — create profile for user 1</p>
    </section>

    <section>
        <h2>In simple words</h2>
        <pre>User Ali (id = 1)
   └── Profile (user_id = 1) phone, address...

Code:
  $user->profile;   // User hasOne Profile
  $profile->user;   // Profile belongsTo User</pre>
    </section>

    <section>
        <h2>Easy mistakes</h2>
        <p class="bad">❌ Table name <code>profile</code> → Laravel looks for <code>profiles</code></p>
        <p class="good">✅ Name the table <code>profiles</code></p>
        <p class="bad">❌ Forget <code>user_id</code> when creating a profile</p>
        <p class="good">✅ Use <code>$user->profile()->create([...])</code></p>
        <p class="bad">❌ Use <code>hasMany</code> for one profile</p>
        <p class="good">✅ Use <code>hasOne</code> on User, <code>belongsTo</code> on Profile</p>
    </section>

    <section>
        <h2>Users + their profile</h2>
        @forelse ($usersWithProfile as $user)
            <div class="row">
                <div class="muted">User #{{ $user->id }}</div>
                <strong>{{ $user->name }}</strong> — {{ $user->email }}
                @if ($user->profile)
                    <div>Phone: {{ $user->profile->phone }}</div>
                    <div>{{ $user->profile->city }}</div>
                @else
                    <div class="bad">No profile</div>
                @endif
            </div>
        @empty
            <p>No users.</p>
        @endforelse
    </section>

    <section>
        <h2>Profiles + their user</h2>
        @forelse ($profilesWithUser as $profile)
            <div class="row">
                <div class="muted">Profile #{{ $profile->id }}</div>
                <div>{{ $profile->phone }} · {{ $profile->city }}</div>
                @if ($profile->user)
                    <div>User: <strong>{{ $profile->user->name }}</strong></div>
                @else
                    <div class="bad">No user</div>
                @endif
            </div>
        @empty
            <p>No profiles.</p>
        @endforelse
    </section>

</body>
</html>
