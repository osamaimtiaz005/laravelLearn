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
use App\Http\Controllers\SanctumController;

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
| RESOURCE CONTROLLER demo (apiResource)
|--------------------------------------------------------------------------
| ONE line registers the full REST map (5 routes, no create/edit HTML):
|
|   Route::apiResource('rsc-students', StudentResourceController::class)
|       ->parameters(['rsc-students' => 'student']);
|
| → GET|POST /api/rsc-students
| → GET|PUT|PATCH|DELETE /api/rsc-students/{student}
|
| Why "rsc-students"? Avoids clashing with older hand-written /api/students
| routes in ApiLearningController. Real apps usually use apiResource('students').
|
| Hub page (web): GET /resource-controller
| Artisan: php artisan make:controller StudentResourceController --api
*/
Route::apiResource('rsc-students', \App\Http\Controllers\StudentResourceController::class)
    ->parameters(['rsc-students' => 'student']);

/*
|--------------------------------------------------------------------------
| SANCTUM AUTH APIs — User model only (token / Bearer)
|--------------------------------------------------------------------------
| Public (no token):
|   POST /api/auth/register  → SanctumController@register
|   POST /api/auth/login     → SanctumController@login
|
| Protected (header Authorization: Bearer {token}):
|   GET  /api/auth/me          → current user
|   GET  /api/auth/tokens      → list token names (not the secret)
|   POST /api/auth/logout      → delete THIS token
|   POST /api/auth/logout-all  → delete ALL tokens for this user
|
| middleware auth:sanctum:
|   1. Reads Bearer token
|   2. Finds hashed row in personal_access_tokens
|   3. Loads the related User (tokenable)
|   4. Sets $request->user()
|   Missing/invalid token → 401 JSON (see bootstrap/app.php)
|
| Hub (web): GET /sanctum
| Model:     User must use HasApiTokens
| Table:     personal_access_tokens  (php artisan migrate)
|
| This is TOKEN auth (Postman/mobile). It is not web session login (/sessions)
| and not SPA cookie auth (/sanctum/csrf-cookie).
*/
Route::post('/auth/register', [SanctumController::class, 'register'])
    ->name('api.auth.register');
Route::post('/auth/login', [SanctumController::class, 'login'])
    ->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [SanctumController::class, 'me'])
        ->name('api.auth.me');
    Route::get('/auth/tokens', [SanctumController::class, 'tokens'])
        ->name('api.auth.tokens');
    Route::post('/auth/logout', [SanctumController::class, 'logout'])
        ->name('api.auth.logout');
    Route::post('/auth/logout-all', [SanctumController::class, 'logoutAll'])
        ->name('api.auth.logoutAll');
});

/*
|--------------------------------------------------------------------------
| Optional: Route::apiResource explained (extra notes)
|--------------------------------------------------------------------------
|
| only / except:
|   Route::apiResource('photos', PhotoController::class)->only(['index', 'show']);
|   Route::apiResource('photos', PhotoController::class)->except(['destroy']);
|
| Nested:
|   Route::apiResource('users.posts', UserPostController::class);
|   → /api/users/{user}/posts ...
|
| Web (7 routes including create/edit forms):
|   Route::resource('photos', PhotoController::class);  // in web.php
|
*/
