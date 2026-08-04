<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Learning — Laravel 12</title>
    <style>
        body { max-width: 1000px; margin: 30px auto; padding: 0 18px; font: 16px/1.55 Arial, sans-serif; color: #1f2937; }
        h1, h2 { color: #111827; }
        section { margin: 24px 0; padding: 18px; border: 1px solid #d1d5db; border-radius: 8px; }
        code, pre { background: #f3f4f6; border-radius: 5px; }
        code { padding: 2px 5px; }
        pre { padding: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 9px; border: 1px solid #d1d5db; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        a { color: #0369a1; }
        .note { color: #92400e; background: #fef3c7; padding: 10px; border-radius: 6px; }
        .ok { color: #065f46; background: #d1fae5; padding: 10px; border-radius: 6px; }
    </style>
</head>
<body>
    <h1>API in the same Laravel project</h1>

    <p>
        An <strong>API</strong> is a set of URLs that return <strong>JSON data</strong>
        instead of Blade HTML. You keep using the same models, database, and validation —
        only the response format changes.
    </p>

    <p class="ok">
        Learning hub (this page) is a <code>web</code> route.
        The real API endpoints live in <code>routes/api.php</code> and start with <code>/api</code>.
    </p>

    <section>
        <h2>Web vs API (same app)</h2>
        <table>
            <thead>
                <tr><th></th><th>Web (<code>routes/web.php</code>)</th><th>API (<code>routes/api.php</code>)</th></tr>
            </thead>
            <tbody>
                <tr><td>Returns</td><td>HTML (Blade)</td><td>JSON</td></tr>
                <tr><td>URL style</td><td><code>/rmb</code>, <code>/email</code></td><td><code>/api/users</code>, <code>/api/hello</code></td></tr>
                <tr><td>CSRF</td><td>Required for POST forms</td><td>Not used for pure JSON API clients</td></tr>
                <tr><td>Session</td><td>Yes (login, flash)</td><td>Usually no (stateless; tokens later)</td></tr>
                <tr><td>Clients</td><td>Browser pages</td><td>Postman, mobile app, React/Vue, other servers</td></tr>
            </tbody>
        </table>
@verbatim
<pre>// Web controller
return view('users.index', compact('users'));

// API controller
return response()->json(['data' => $users]);</pre>
@endverbatim
    </section>

    <section>
        <h2>How it is wired in this project</h2>
@verbatim
<pre>// bootstrap/app.php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',  // ← enables /api/*
    ...
)

// routes/api.php
Route::get('/hello', [ApiLearningController::class, 'hello']);
// Full URL → /api/hello  (prefix added automatically)</pre>
@endverbatim
        <p class="note">
            Related but different: <code>Http::get(...)</code> in <code>httpController</code>
            is your app <em>calling an external</em> API. This lesson is your app
            <em>providing</em> an API for others to call.
        </p>
    </section>

    <section>
        <h2>Try these GET endpoints in the browser</h2>
        <table>
            <thead>
                <tr><th>URL</th><th>What you learn</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="{{ url('/api/hello') }}" target="_blank">/api/hello</a></td>
                    <td>Simple JSON response</td>
                </tr>
                <tr>
                    <td><a href="{{ url('/api/users') }}" target="_blank">/api/users</a></td>
                    <td>List users as JSON</td>
                </tr>
                <tr>
                    <td><a href="{{ url('/api/status/404') }}" target="_blank">/api/status/404</a></td>
                    <td>Custom HTTP status codes</td>
                </tr>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <a href="{{ url('/api/users/'.$user->id) }}" target="_blank">
                                /api/users/{{ $user->id }}
                            </a>
                        </td>
                        <td>Show one user + route model binding (#{{ $user->id }} {{ $user->name }})</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No users yet — seed or create a user, then reload.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section>
        <h2>Full CRUD map (use Postman for POST/PUT/DELETE)</h2>
        <table>
            <thead>
                <tr><th>Method</th><th>URL</th><th>Controller method</th><th>Purpose</th></tr>
            </thead>
            <tbody>
                <tr><td>GET</td><td><code>/api/users</code></td><td><code>users</code></td><td>List</td></tr>
                <tr><td>GET</td><td><code>/api/users/{user}</code></td><td><code>showUser</code></td><td>Show one</td></tr>
                <tr><td>POST</td><td><code>/api/users</code></td><td><code>storeUser</code></td><td>Create (201)</td></tr>
                <tr><td>PUT / PATCH</td><td><code>/api/users/{user}</code></td><td><code>updateUser</code></td><td>Update</td></tr>
                <tr><td>DELETE</td><td><code>/api/users/{user}</code></td><td><code>destroyUser</code></td><td>Delete</td></tr>
                <tr><td>POST</td><td><code>/api/validate-demo</code></td><td><code>validateDemo</code></td><td>See 422 JSON errors</td></tr>
                <tr><td>POST</td><td><code>/api/echo</code></td><td><code>echoRequest</code></td><td>Inspect request JSON</td></tr>
            </tbody>
        </table>

        <h3>Postman checklist</h3>
        <ol>
            <li>Method + URL (example: <code>POST {{ url('/api/users') }}</code>)</li>
            <li>Headers: <code>Accept: application/json</code> and <code>Content-Type: application/json</code></li>
            <li>Body → raw → JSON</li>
        </ol>
@verbatim
<pre>// POST /api/users body example
{
  "name": "Ali Demo",
  "email": "ali.demo@example.com",
  "password": "secret123"
}

// PUT /api/users/1 body example
{
  "name": "Updated Name"
}</pre>
@endverbatim
    </section>

    <section>
        <h2>curl examples</h2>
@verbatim
<pre># List
curl -H "Accept: application/json" http://127.0.0.1:8000/api/users

# Create
curl -X POST http://127.0.0.1:8000/api/users \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Ali\",\"email\":\"ali2@example.com\",\"password\":\"secret123\"}"

# Update
curl -X PUT http://127.0.0.1:8000/api/users/1 \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"New Name\"}"

# Delete
curl -X DELETE http://127.0.0.1:8000/api/users/1 \
  -H "Accept: application/json"</pre>
@endverbatim
        <p class="note">
            If you use XAMPP without <code>php artisan serve</code>, replace the base URL
            with your project URL (for example <code>http://localhost/firstLearning/public</code>).
        </p>
    </section>

    <section>
        <h2>Status codes you should know</h2>
        <table>
            <thead>
                <tr><th>Code</th><th>Meaning</th><th>Try</th></tr>
            </thead>
            <tbody>
                <tr><td>200</td><td>OK</td><td><a href="{{ url('/api/status/200') }}">/api/status/200</a></td></tr>
                <tr><td>201</td><td>Created</td><td><a href="{{ url('/api/status/201') }}">/api/status/201</a></td></tr>
                <tr><td>401</td><td>Unauthorized</td><td><a href="{{ url('/api/status/401') }}">/api/status/401</a></td></tr>
                <tr><td>404</td><td>Not Found</td><td><a href="{{ url('/api/status/404') }}">/api/status/404</a></td></tr>
                <tr><td>422</td><td>Validation failed</td><td>POST /api/validate-demo with empty body</td></tr>
            </tbody>
        </table>
    </section>

    <section>
        <h2>update() vs save() vs DB:: — and why no find()</h2>
        <p>
            Used by <code>PUT/PATCH /api/students/{student}</code>
            (<code>ApiLearningController@updateStudent</code>).
        </p>

        <h3>Why we do NOT find() first</h3>
        <p>
            The route parameter <code>{student}</code> plus type-hint <code>Student $student</code>
            is <strong>route model binding</strong>. Laravel already loaded that row
            (or returned 404). Calling <code>Student::find($student->id)</code> again
            is a wasted second query.
        </p>
@verbatim
<pre>// ❌ redundant
public function updateStudent(Request $request, Student $student)
{
    $student = Student::find($student->id); // already loaded!
}

// ✅ binding did the find for you
public function updateStudent(Request $request, Student $student)
{
    $student->update($validated);
}

// ✅ find() ONLY when the route has a plain {id}, not a model
public function updateStudent(Request $request, $id)
{
    $student = Student::findOrFail($id);
}</pre>
@endverbatim

        <h3>How save() works</h3>
@verbatim
<pre>// Change attributes on the model object, then write to DB
$student->name  = $request->name;
$student->email = $request->email;
$student->batch = $request->batch;
$student->save();   // UPDATE existing row (or INSERT if new)

// Same idea, safer mass-assignment:
$student->fill($validated);
$student->save();</pre>
@endverbatim

        <h3>Why this API uses update() instead of save()</h3>
        <table>
            <thead>
                <tr><th>Method</th><th>What it does</th><th>When to use</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>$student->update($validated)</code></td>
                    <td><code>fill()</code> + <code>save()</code> in one call; uses <code>$fillable</code>; runs mutators</td>
                    <td>Best for API/form updates from request data (what we use)</td>
                </tr>
                <tr>
                    <td><code>$student->save()</code></td>
                    <td>Writes current model attributes to DB</td>
                    <td>After changing one/few fields in code, or when you build the model step by step</td>
                </tr>
                <tr>
                    <td><code>DB::table('students')->update(...)</code></td>
                    <td>Query Builder — raw table update, no Student model</td>
                    <td>Complex queries/joins; NOT for normal model CRUD (skips mutators, events, fillable)</td>
                </tr>
            </tbody>
        </table>

        <p class="note">
            <code>update()</code> and <code>save()</code> are both <strong>Eloquent</strong>.
            Prefer Eloquent for single-row Student CRUD so accessors/mutators and
            <code>$fillable</code> still apply. Use <code>DB::table()</code> when you need
            advanced SQL without a model.
        </p>
@verbatim
<pre>// Eloquent (recommended here)
$student->update($validated);

// Eloquent save() equivalent
$student->fill($validated);
$student->save();

// Query Builder (bypasses Student mutators / $fillable)
DB::table('students')->where('id', $student->id)->update($validated);</pre>
@endverbatim
    </section>

    <section>
        <h2>API 404: binding is OK — JSON vs HTML is the Accept header</h2>
        <p>
            Route model binding works on APIs. A missing <code>{student}</code> throws 404.
            By default, Postman&apos;s <code>Accept: */*</code> can show an HTML error page.
            This project forces JSON for all <code>/api/*</code> errors in
            <code>bootstrap/app.php</code> (<code>shouldRenderJsonWhen</code>).
        </p>
        <p class="ok">
            Full write-up: README section <strong>AC. Building an API</strong>
            (Postman form-data gotcha, update vs save, Sanctum, status codes).
        </p>
    </section>

    <section>
        <h2>Key files</h2>
        <ul>
            <li><code>bootstrap/app.php</code> — registers <code>api:</code> routing</li>
            <li><code>routes/api.php</code> — all API URLs + detailed comments</li>
            <li><code>app/Http/Controllers/ApiLearningController.php</code> — JSON actions + comments</li>
            <li><code>resources/views/api_learning/index.blade.php</code> — this page</li>
        </ul>
    </section>

    <section>
        <h2>Next step (optional): API auth with Sanctum</h2>
@verbatim
<pre>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn (\Illuminate\Http\Request $r) => $r->user());
});

// Client header:
// Authorization: Bearer {token}</pre>
@endverbatim
        <p>These demos work without Sanctum so you can learn JSON/CRUD first.</p>
    </section>
</body>
</html>
