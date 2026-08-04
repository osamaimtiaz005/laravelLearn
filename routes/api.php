<?php

/*
|--------------------------------------------------------------------------
| API ROUTES — beginner learning file
|--------------------------------------------------------------------------
|
| WHAT is an API in Laravel?
|   A set of URLs that return DATA (usually JSON), not HTML pages.
|   Same project, same models/DB — different response format.
|
| WHERE do API routes live?
|   This file: routes/api.php
|   Registered in bootstrap/app.php with:  api: __DIR__.'/../routes/api.php'
|
| AUTOMATIC PREFIX:
|   Every route below is prefixed with /api
|   Route::get('/hello', ...)  →  full URL = /api/hello
|
| AUTOMATIC MIDDLEWARE GROUP "api":
|   - Stateless by default (no session / no CSRF like web forms)
|   - Rate limiting (throttle) — protects from too many requests
|   - Good for Postman, mobile apps, SPA fetch/axios
|
| WEB vs API (same app):
|   routes/web.php  → Blade HTML, cookies, CSRF, sessions
|   routes/api.php  → JSON, tokens later (Sanctum), no CSRF for pure API
|
| HOW TO TEST:
|   1) Browser address bar  → only GET endpoints (easy)
|   2) Postman / Insomnia   → GET/POST/PUT/PATCH/DELETE + JSON body
|   3) curl in terminal
|   4) Learning hub page   → GET /api-learning  (HTML guide in web.php)
|
| REST idea (common pattern):
|   GET    /api/users       → list
|   POST   /api/users       → create
|   GET    /api/users/{id}  → show one
|   PUT    /api/users/{id}  → update
|   DELETE /api/users/{id}  → delete
|
| Always send this header when testing APIs in Postman:
|   Accept: application/json
| so Laravel returns JSON validation errors (422) instead of redirecting.
|
*/

use App\Http\Controllers\ApiLearningController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tiny health check
|--------------------------------------------------------------------------
| GET /api/hello
*/
Route::get('/hello', [ApiLearningController::class, 'hello'])
    ->name('api.hello');

/*
|--------------------------------------------------------------------------
| Users API — CRUD-style examples (same User model as Blade demos)
|--------------------------------------------------------------------------
|
| GET    /api/users
| GET    /api/users/{user}          ← route model binding
| POST   /api/users
| PUT    /api/users/{user}
| PATCH  /api/users/{user}
| DELETE /api/users/{user}
|
| apiResource() would register the same map in one line:
|   Route::apiResource('users', ApiLearningController::class);
| We write them out here so each method is obvious while learning.
|
*/
Route::get('/users', [ApiLearningController::class, 'users'])
    ->name('api.users.index');

Route::get('/users/{user}', [ApiLearningController::class, 'showUser'])
    ->name('api.users.show');

Route::post('/users', [ApiLearningController::class, 'storeUser'])
    ->name('api.users.store');

Route::put('/users/{user}', [ApiLearningController::class, 'updateUser'])
    ->name('api.users.update');

Route::patch('/users/{user}', [ApiLearningController::class, 'updateUser'])
    ->name('api.users.patch');

Route::delete('/users/{user}', [ApiLearningController::class, 'destroyUser'])
    ->name('api.users.destroy');

/*
|--------------------------------------------------------------------------
| Extra learning endpoints
|--------------------------------------------------------------------------
*/
Route::get('/status/{code}', [ApiLearningController::class, 'statusDemo'])
    ->whereNumber('code')
    ->name('api.status');

Route::post('/validate-demo', [ApiLearningController::class, 'validateDemo'])
    ->name('api.validate');

Route::post('/echo', [ApiLearningController::class, 'echoRequest'])
    ->name('api.echo');

Route::get('/users-by-email/{email}', [ApiLearningController::class, 'findByEmail'])
    ->name('api.users.byEmail');

Route::get('/students', [ApiLearningController::class, 'getStudents']);
Route::post('/add-student', [ApiLearningController::class, 'addStudent']);

/*
|--------------------------------------------------------------------------
| UPDATE student — why no find(), and update() vs save() vs DB::
|--------------------------------------------------------------------------
| Route: PUT|PATCH /api/students/{student}
|
| {student} + Student $student = Route Model Binding.
| Laravel already loaded the row (or 404). Do NOT call Student::find() again.
|
| Inside controller we use Eloquent:
|   $student->update($validated);     // preferred for request data
| Same idea with save():
|   $student->fill($validated);
|   $student->save();
|
| Query Builder alternative (skips mutators / $fillable / model events):
|   DB::table('students')->where('id', $id)->update([...]);
|
| Full comparison comments: ApiLearningController::updateStudent()
*/
Route::put('/students/{student}', [ApiLearningController::class, 'updateStudent']);
Route::patch('/students/{student}', [ApiLearningController::class, 'updateStudent']);

/*
|--------------------------------------------------------------------------
| DELETE student — manual find (contrast with binding on update)
|--------------------------------------------------------------------------
| Route uses {id}, not {student}, so the controller calls Student::find($id).
| Missing id → JSON 404 we write ourselves.
| Prefer {student} + Student $student in real apps (same as update).
*/
Route::delete('/delete-student/{id}', [ApiLearningController::class, 'deleteStudents']);

/*
|--------------------------------------------------------------------------
| Optional: Route::apiResource explained (commented — do not enable twice)
|--------------------------------------------------------------------------
|
| Route::apiResource('posts', PostController::class);
|
| Creates (no create/edit HTML form routes — those are web-only):
|   GET    /api/posts           → index
|   POST   /api/posts           → store
|   GET    /api/posts/{post}    → show
|   PUT/PATCH /api/posts/{post} → update
|   DELETE /api/posts/{post}    → destroy
|
| Controller method names must match: index, store, show, update, destroy
|
| Nested example:
|   Route::apiResource('users.posts', UserPostController::class);
|   → /api/users/{user}/posts ...
|
*/

/*
|--------------------------------------------------------------------------
| Auth note (next step — not required for these demos)
|--------------------------------------------------------------------------
|
| Public APIs (hello, list) can stay open.
| For protected APIs later:
|   1) composer require laravel/sanctum
|   2) php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
|   3) migrate
|   4) Wrap routes:
|        Route::middleware('auth:sanctum')->group(function () {
|            Route::get('/me', fn (Request $r) => $r->user());
|        });
|
| Client then sends:  Authorization: Bearer {token}
|
*/
