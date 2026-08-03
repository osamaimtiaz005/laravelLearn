<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Model Binding</title>
    <style>
        body { max-width: 1000px; margin: 30px auto; padding: 0 18px; font: 16px/1.55 Arial, sans-serif; color: #1f2937; }
        h1, h2 { color: #111827; }
        section { margin: 24px 0; padding: 18px; border: 1px solid #d1d5db; border-radius: 8px; }
        code, pre { background: #f3f4f6; border-radius: 5px; }
        code { padding: 2px 5px; }
        pre { padding: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 9px; border: 1px solid #d1d5db; text-align: left; }
        th { background: #f3f4f6; }
        a { color: #0369a1; }
        .error { padding: 12px; color: #991b1b; background: #fee2e2; border-radius: 6px; }
        .note { color: #92400e; background: #fef3c7; padding: 10px; border-radius: 6px; }
    </style>
</head>
<body>
    <h1>Route Model Binding — Laravel 12</h1>

    <p>
        Route Model Binding automatically converts a route parameter into an Eloquent model.
        Instead of receiving an ID and calling <code>find()</code> or <code>findOrFail()</code>,
        Laravel retrieves the model and injects it into the controller. A missing record
        automatically produces a <strong>404 response</strong>.
    </p>

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <section>
        <h2>Without vs with model binding</h2>
@verbatim
<pre>// Without binding: manual query
public function show($id)
{
    $user = User::findOrFail($id);
}

// With binding: Laravel queries and injects User
public function show(User $user)
{
    return $user;
}</pre>
@endverbatim
        <p>
            The route parameter name <code>{user}</code> must match the controller variable
            <code>$user</code>, and <code>User</code> tells Laravel which model to resolve.
        </p>
    </section>

    <section>
        <h2>1. Implicit Model Binding (most common)</h2>
        <p>Laravel figures out the model automatically from <code>{user}</code> and <code>User $user</code>.</p>
@verbatim
<pre>Route::get('/rmb/implicit/{user}', [Controller::class, 'showImplicit']);

public function showImplicit(User $user)
{
    return $user; // already loaded by id
}</pre>
@endverbatim
        <p>By default, Laravel searches by the model's primary key: <code>users.id</code>.</p>
    </section>

    <section>
        <h2>2. Custom-key binding</h2>
        <p>Add <code>:column</code> after the model parameter to search by another column.</p>
@verbatim
<pre>// Finds: User::where('name', $value)->firstOrFail()
Route::get('/rmb/custom/{user:name}', [Controller::class, 'show']);</pre>
@endverbatim
        <p class="note">
            Use a unique column such as <code>slug</code>, <code>uuid</code>, or unique
            <code>email</code>. A non-unique name can match the first row.
        </p>
    </section>

    <section>
        <h2>3. Explicit Model Binding</h2>
        <p>You register exactly which model a route parameter should resolve in <code>AppServiceProvider::boot()</code>.</p>
@verbatim
<pre>use App\Models\User;
use Illuminate\Support\Facades\Route;

public function boot(): void
{
    Route::model('explicitUser', User::class);
}

// {explicitUser} now resolves to App\Models\User
Route::get('/rmb/explicit/{explicitUser}', ...);</pre>
@endverbatim

        <p>For completely custom lookup logic, explicit binding can also use a closure:</p>
@verbatim
<pre>Route::bind('userByEmail', function (string $value) {
    return User::where('email', $value)->firstOrFail();
});</pre>
@endverbatim
    </section>

    <section>
        <h2>Working examples from your database</h2>

        @if ($users->isEmpty())
            <p>No users exist yet. Add or seed a user, then reload this page.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Implicit (ID)</th>
                        <th>Custom key (name)</th>
                        <th>Explicit (ID)</th>
                        <th>Extra methods</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>#{{ $user->id }} — {{ $user->name }}</td>
                            <td><a href="{{ route('rmb.implicit', $user) }}">Open</a></td>
                            <td><a href="{{ route('rmb.custom', ['user' => $user->name]) }}">Open</a></td>
                            <td><a href="{{ route('rmb.explicit', ['explicitUser' => $user->id]) }}">Open</a></td>
                            <td><a href="{{ route('rmb.details', $user) }}">Open</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <p>
            <a href="{{ route('rmb.missing', ['user' => 999999999]) }}">
                Test a missing user and custom <code>missing()</code> handling
            </a>
        </p>
    </section>

    <section>
        <h2>Extra methods and features</h2>
        <table>
            <thead>
                <tr><th>Method / feature</th><th>Purpose</th></tr>
            </thead>
            <tbody>
                <tr><td><code>getRouteKeyName()</code></td><td>Column Laravel uses globally; default is <code>id</code>.</td></tr>
                <tr><td><code>getRouteKey()</code></td><td>Current model's value for its route key.</td></tr>
                <tr><td><code>resolveRouteBinding()</code></td><td>Customize how one route value resolves.</td></tr>
                <tr><td><code>resolveRouteBindingQuery()</code></td><td>Add conditions to the binding query.</td></tr>
                <tr><td><code>resolveChildRouteBinding()</code></td><td>Customize nested child-model resolution.</td></tr>
                <tr><td><code>->missing(...)</code></td><td>Custom redirect/response when no record is found.</td></tr>
                <tr><td><code>->scopeBindings()</code></td><td>Ensure a nested child belongs to its parent.</td></tr>
                <tr><td><code>->withTrashed()</code></td><td>Allow soft-deleted models for that route.</td></tr>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Use one route key globally (optional)</h2>
        <p>You can override this method on <code>User</code>. It is shown only as an example; your model was not changed.</p>
@verbatim
<pre>public function getRouteKeyName(): string
{
    return 'slug'; // then {user} searches users.slug everywhere
}</pre>
@endverbatim
        <p>
            Prefer inline <code>{user:slug}</code> when only one route needs the custom key.
            Override <code>getRouteKeyName()</code> when every route for that model should use it.
        </p>
    </section>

    <section>
        <h2>Scoped nested binding example</h2>
@verbatim
<pre>Route::get('/users/{user}/posts/{post:slug}', function (User $user, Post $post) {
    return $post;
})->scopeBindings();</pre>
@endverbatim
        <p>
            Laravel only resolves a post that belongs to the bound user. This prevents accessing
            another user's child record through a changed URL.
        </p>
    </section>
</body>
</html>
