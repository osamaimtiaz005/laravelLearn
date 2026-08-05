<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Controller — Laravel 12</title>
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
    <h1>Resource Controller — Laravel 12</h1>

    <p>
        A <strong>resource controller</strong> is a controller with standard CRUD method names.
        Laravel then registers matching REST routes with one line:
        <code>Route::resource</code> (web) or <code>Route::apiResource</code> (API).
    </p>

    <p class="ok">
        Working API demo (this project):
        <code>/api/rsc-students</code>
        via <code>StudentResourceController</code> + <code>Route::apiResource</code>.
    </p>

    <section>
        <h2>Artisan commands</h2>
@verbatim
<pre># Full web resource (7 methods: + create + edit for Blade forms)
php artisan make:controller PhotoController --resource

# API resource (5 methods: NO create/edit) ← used in this project
php artisan make:controller StudentResourceController --api

# Resource + model type-hints in method signatures
php artisan make:controller PhotoController --resource --model=Photo</pre>
@endverbatim
    </section>

    <section>
        <h2>Web resource vs API resource</h2>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th><code>Route::resource</code> (web)</th>
                    <th><code>Route::apiResource</code> (api)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>File</td>
                    <td><code>routes/web.php</code></td>
                    <td><code>routes/api.php</code></td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td>index, create, store, show, edit, update, destroy</td>
                    <td>index, store, show, update, destroy</td>
                </tr>
                <tr>
                    <td>Missing</td>
                    <td>—</td>
                    <td>create + edit (HTML form pages)</td>
                </tr>
                <tr>
                    <td>Returns</td>
                    <td>Usually Blade views</td>
                    <td>Usually JSON</td>
                </tr>
                <tr>
                    <td>Register</td>
                    <td><code>Route::resource('photos', PhotoController::class);</code></td>
                    <td><code>Route::apiResource('rsc-students', StudentResourceController::class);</code></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Route map for this project (<code>apiResource('rsc-students')</code>)</h2>
        <table>
            <thead>
                <tr><th>HTTP</th><th>URL</th><th>Method</th><th>Named route</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>GET</td>
                    <td><a href="{{ url('/api/rsc-students') }}" target="_blank">/api/rsc-students</a></td>
                    <td><code>index</code></td>
                    <td><code>rsc-students.index</code></td>
                </tr>
                <tr>
                    <td>POST</td>
                    <td><code>/api/rsc-students</code></td>
                    <td><code>store</code></td>
                    <td><code>rsc-students.store</code></td>
                </tr>
                <tr>
                    <td>GET</td>
                    <td>
                        @forelse ($students as $student)
                            <a href="{{ url('/api/rsc-students/'.$student->id) }}" target="_blank">/api/rsc-students/{{ $student->id }}</a><br>
                        @empty
                            <code>/api/rsc-students/{id}</code> (no students yet)
                        @endforelse
                    </td>
                    <td><code>show</code></td>
                    <td><code>rsc-students.show</code></td>
                </tr>
                <tr>
                    <td>PUT / PATCH</td>
                    <td><code>/api/rsc-students/{id}</code></td>
                    <td><code>update</code></td>
                    <td><code>rsc-students.update</code></td>
                </tr>
                <tr>
                    <td>DELETE</td>
                    <td><code>/api/rsc-students/{id}</code></td>
                    <td><code>destroy</code></td>
                    <td><code>rsc-students.destroy</code></td>
                </tr>
            </tbody>
        </table>
        <p class="note">
            We used the prefix <code>rsc-students</code> so it does not clash with the older
            hand-written <code>/api/students</code> routes in <code>ApiLearningController</code>.
            In a real app you usually keep one clean <code>apiResource('students', ...)</code>.
        </p>
    </section>

    <section>
        <h2>Method cheat sheet</h2>
        <table>
            <thead>
                <tr><th>Method</th><th>Job</th><th>Typical return</th></tr>
            </thead>
            <tbody>
                <tr><td><code>index</code></td><td>List many</td><td>JSON collection / Blade list</td></tr>
                <tr><td><code>create</code></td><td>Show create form (web only)</td><td>Blade form</td></tr>
                <tr><td><code>store</code></td><td>Save new row</td><td>201 JSON / redirect</td></tr>
                <tr><td><code>show</code></td><td>Show one</td><td>JSON one / Blade detail</td></tr>
                <tr><td><code>edit</code></td><td>Show edit form (web only)</td><td>Blade form</td></tr>
                <tr><td><code>update</code></td><td>Save changes</td><td>JSON / redirect</td></tr>
                <tr><td><code>destroy</code></td><td>Delete</td><td>JSON / redirect</td></tr>
            </tbody>
        </table>
        <p>
            <code>destroy()</code> (controller) calls <code>$model->delete()</code> (Eloquent).
            Different names on purpose.
        </p>
    </section>

    <section>
        <h2>Useful extras</h2>
@verbatim
<pre>// Only some actions
Route::apiResource('photos', PhotoController::class)->only(['index', 'show']);

// All except some
Route::apiResource('photos', PhotoController::class)->except(['destroy']);

// Nested resources: /api/users/{user}/posts/{post}
Route::apiResource('users.posts', UserPostController::class);

// Rename route parameter for cleaner binding
Route::apiResource('rsc-students', StudentResourceController::class)
    ->parameters(['rsc-students' => 'student']);
// {rsc_student} becomes {student} → Student $student

// Soft-deleted models on a route
Route::apiResource('photos', PhotoController::class)->withTrashed();

// List generated routes
php artisan route:list --path=rsc-students</pre>
@endverbatim
    </section>

    <section>
        <h2>Postman examples</h2>
@verbatim
<pre>GET    /api/rsc-students
POST   /api/rsc-students
       Body raw JSON: {"name":"Ali","email":"ali@example.com","batch":2024}

GET    /api/rsc-students/1
PUT    /api/rsc-students/1
       Body raw JSON: {"name":"sams"}

DELETE /api/rsc-students/1

Headers always:
  Accept: application/json
  Content-Type: application/json   (for POST/PUT/PATCH)</pre>
@endverbatim
        <p class="note">
            Related API lesson (manual routes, update vs save, 404 JSON):
            <a href="{{ url('/api-learning') }}">/api-learning</a>
            · README sections <strong>AC</strong> (API) and <strong>AD</strong> (Resource Controller).
        </p>
    </section>
</body>
</html>
