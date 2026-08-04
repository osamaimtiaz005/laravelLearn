<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * API Learning Controller
 *
 * WHAT: Controllers that return JSON (data) instead of Blade HTML (pages).
 * WHY:  Mobile apps, SPAs (React/Vue), Postman, and other servers talk in JSON —
 *       they do not need full HTML pages.
 *
 * WEB vs API in this same project:
 *   Web route  → return view('...');     → browser shows HTML
 *   API route  → return response()->json([...]); → client gets JSON
 *
 * Both share: models, database, validation rules, route model binding.
 *
 * HOW Laravel finds these methods:
 *   routes/api.php  →  Route::get('/users', [ApiLearningController::class, 'users'])
 *   Full URL        →  /api/users   (because api.php gets an automatic /api prefix)
 *
 * Hub page (HTML explanation): GET /api-learning
 */
class ApiLearningController extends Controller
{
    /**
     * Learning hub — HTML page (registered in web.php, NOT api.php).
     * Lists every API endpoint with copy-paste curl / Postman tips.
     */
    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->orderBy('id')
            ->limit(5)
            ->get();

        return view('api_learning.index', compact('users'));
    }

    /**
     * ---------------------------------------------------------------------
     * 1) SIMPLE JSON — no database
     * ---------------------------------------------------------------------
     * Route: GET /api/hello
     *
     * response()->json($data, $status)
     *   $data   = array/object → encoded to JSON automatically
     *   $status = HTTP status code (default 200)
     *
     * Laravel also sets header: Content-Type: application/json
     */
    public function hello(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'API works in the same Laravel project as your Blade pages.',
            'tip' => 'Open this URL in the browser or Postman — you see JSON, not HTML.',
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 2) LIST resources (READ many) — GET
     * ---------------------------------------------------------------------
     * Route: GET /api/users
     *
     * Safe fields only: never return password / remember_token.
     * User model already has $hidden = ['password', 'remember_token'],
     * so toArray()/json will hide them even if you forget.
     */
    public function users(): JsonResponse
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at'])
            ->orderBy('id')
            ->limit(20)
            ->get();

        return response()->json([
            'count' => $users->count(),
            'data' => $users,
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 3) SHOW one resource + ROUTE MODEL BINDING
     * ---------------------------------------------------------------------
     * Route: GET /api/users/{user}
     *
     * Same binding idea as /rmb/implicit/{user}:
     *   {user} + User $user → Laravel loads the row (or 404).
     *
     * For APIs, a missing model returns JSON 404 (when Accept: application/json
     * or when the request hits the api middleware group).
     */
    public function showUser(User $user): JsonResponse
    {
        return response()->json([
            'binding' => 'Implicit route model binding works on API routes too',
            'data' => $user->only(['id', 'name', 'email', 'created_at']),
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 4) CREATE resource — POST
     * ---------------------------------------------------------------------
     * Route: POST /api/users
     *
     * Body (JSON or form-data):
     *   { "name": "Ali", "email": "ali@example.com", "password": "secret123" }
     *
     * IMPORTANT differences from web forms:
     *   - No Blade @csrf needed for pure API JSON clients (api middleware has no CSRF).
     *   - Still VALIDATE input — never trust the client.
     *   - Return 201 Created on success (REST convention).
     *
     * Try with Postman:
     *   Method: POST
     *   URL:    /api/users
     *   Header: Accept: application/json
     *   Header: Content-Type: application/json
     *   Body → raw → JSON
     */
    public function storeUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'User created',
            'data' => $user->only(['id', 'name', 'email']),
        ], 201);
    }

    /**
     * ---------------------------------------------------------------------
     * 5) UPDATE resource — PUT / PATCH
     * ---------------------------------------------------------------------
     * Route: PUT|PATCH /api/users/{user}
     *
     * PUT   = often treated as "replace / full update"
     * PATCH = "partial update" (only send changed fields)
     *
     * In practice many apps use either and validate with "sometimes".
     *
     * Example body:
     *   { "name": "New Name" }
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User updated',
            'data' => $user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 6) DELETE resource — DELETE
     * ---------------------------------------------------------------------
     * Route: DELETE /api/users/{user}
     *
     * Status 204 No Content = success with empty body (common REST style).
     * Here we also return a short JSON message for easier learning in Postman.
     */
    public function destroyUser(User $user): JsonResponse
    {
        $id = $user->id;
        $user->delete();

        return response()->json([
            'message' => 'User deleted',
            'deleted_id' => $id,
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 7) STATUS CODES demo
     * ---------------------------------------------------------------------
     * Route: GET /api/status/{code}
     *
     * Common API status codes:
     *   200 OK              — success (GET/PUT/PATCH)
     *   201 Created         — success creating (POST)
     *   204 No Content      — success, no body (often DELETE)
     *   400 Bad Request     — malformed request
     *   401 Unauthorized    — not logged in / bad token
     *   403 Forbidden       — logged in but not allowed
     *   404 Not Found       — resource missing
     *   422 Unprocessable   — validation failed (Laravel default for validate())
     *   429 Too Many        — rate limited
     *   500 Server Error    — bug on server
     */
    public function statusDemo(int $code): JsonResponse
    {
        $allowed = [200, 201, 400, 401, 403, 404, 422, 500];

        if (! in_array($code, $allowed, true)) {
            return response()->json([
                'error' => 'Use one of: ' . implode(', ', $allowed),
            ], 400);
        }

        return response()->json([
            'requested_status' => $code,
            'meaning' => match ($code) {
                200 => 'OK — request succeeded',
                201 => 'Created — new resource saved',
                400 => 'Bad Request — client sent something invalid',
                401 => 'Unauthorized — authentication required',
                403 => 'Forbidden — authenticated but not permitted',
                404 => 'Not Found — no matching resource',
                422 => 'Unprocessable Entity — validation errors',
                500 => 'Server Error — unexpected failure',
            },
        ], $code);
    }

    /**
     * ---------------------------------------------------------------------
     * 8) VALIDATION ERROR shape (API)
     * ---------------------------------------------------------------------
     * Route: POST /api/validate-demo
     *
     * When Accept: application/json (or X-Requested-With: XMLHttpRequest),
     * failed $request->validate() returns JSON like:
     *
     * {
     *   "message": "The email field is required. (and 1 more error)",
     *   "errors": {
     *     "email": ["The email field is required."],
     *     "name": ["The name field is required."]
     *   }
     * }
     *
     * Status: 422
     *
     * Try with empty body in Postman to see this automatically.
     */
    public function validateDemo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required', 'email'],
        ]);

        return response()->json([
            'message' => 'Validation passed',
            'data' => $validated,
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 9) READING the request (API style)
     * ---------------------------------------------------------------------
     * Route: POST /api/echo
     *
     * Same Request class as web forms:
     *   $request->input('key')
     *   $request->all()
     *   $request->header('Accept')
     *   $request->bearerToken()   // Authorization: Bearer xxx (auth later)
     *   $request->ip()
     */
    public function echoRequest(Request $request): JsonResponse
    {
        return response()->json([
            'method' => $request->method(),
            'path' => $request->path(),
            'query' => $request->query(),
            'body' => $request->except(['password']),
            'headers_sample' => [
                'accept' => $request->header('Accept'),
                'content_type' => $request->header('Content-Type'),
                'user_agent' => $request->userAgent(),
            ],
            'client_ip' => $request->ip(),
            'wants_json' => $request->wantsJson(),
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * 10) Manual error JSON (when you throw / catch yourself)
     * ---------------------------------------------------------------------
     * Route: GET /api/users-by-email/{email}
     *
     * Prefer route model binding when possible.
     * When you look up manually, return a clear JSON error + status.
     */
    public function findByEmail(string $email): JsonResponse
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'error' => 'User not found',
                'email' => $email,
            ], 404);
        }

        return response()->json([
            'data' => $user->only(['id', 'name', 'email']),
        ]);
    }
    
    public function getStudents(){
        $students = Student::all();
        return response()->json([
            'data' => $students,
        ]);
    }
    /**
     * POST /api/add-student
     *
     * NOTE: Laravel has NO built-in rule named "year".
     *   Wrong:  'batch' => ['required', 'year', 'min:2020']
     *   Right:  integer + digits:4 + numeric min/max
     *
     * Also: min/max on a string = character LENGTH.
     *       min/max on an integer/numeric = numeric VALUE.
     * So use integer (or numeric) before min:2020.
     */

    /**
     * POST /api/add-student
     *
     * Handles the creation of a new student.
     * - Validates the incoming request data.
     * - Creates a new student record in the database.
     * - Returns a JSON response with the created student data.
     *
     * Note: Uses Eloquent's create() method to insert the student.
     */

    public function addStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required', 'email', 'unique:students,email'],
            'batch' => ['required', 'integer', 'digits:4', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Student created',
            'data' => $student,
        ], 201);
    }

    /**
     * ---------------------------------------------------------------------
     * UPDATE student — PUT|PATCH /api/students/{student}
     * ---------------------------------------------------------------------
     *
     * =====================================================================
     * 1) WHY we do NOT find() the student first
     * =====================================================================
     *
     * Route:  Route::put('/students/{student}', [..., 'updateStudent']);
     * Method: public function updateStudent(Request $request, Student $student)
     *
     * Laravel Route Model Binding already:
     *   - reads {student} from the URL (usually the id)
     *   - runs Student::where('id', $value)->firstOrFail()
     *   - injects the full Student model into $student
     *   - returns 404 automatically if missing
     *
     * So this is REDUNDANT and wasteful (2nd query for the same row):
     *
     *   public function updateStudent(Request $request, Student $student) {
     *       $student = Student::find($student->id);   // ❌ unnecessary
     *       if (!$student) { return 404; }            // ❌ binding already 404'd
     *   }
     *
     * Use find()/findOrFail() ONLY when the route parameter is a plain id:
     *
     *   Route::put('/students/{id}', ...)
     *   public function updateStudent(Request $request, $id) {
     *       $student = Student::findOrFail($id); // ✅ needed — no model binding
     *   }
     *
     * =====================================================================
     * 2) HOW to use save() (Eloquent)
     * =====================================================================
     *
     * save() writes the CURRENT model attributes to the DB.
     * Flow: change properties on the object → then save().
     *
     *   $student->name  = $request->name;
     *   $student->email = $request->email;
     *   $student->batch = $request->batch;
     *   $student->save();   // INSERT if new model, UPDATE if existing
     *
     * Or with fill() + save():
     *
     *   $student->fill($validated);  // mass-assign using $fillable
     *   $student->save();            // one SQL UPDATE
     *
     * save() returns true/false (did the write succeed?).
     *
     * =====================================================================
     * 3) WHY this method uses update() instead of save()
     * =====================================================================
     *
     *   $student->update($validated);
     *
     * update($array) = fill($array) + save() in ONE call.
     *
     * Why prefer it here:
     *   ✅ Shorter — no manual property lines
     *   ✅ Respects $fillable (mass-assignment protection)
     *   ✅ Still runs mutators (setNameAttribute, etc.)
     *   ✅ Avoids IDE "readonly property" warnings from getNameAttribute()
     *   ✅ Clear intent: "update this existing row with these fields"
     *
     * When save() is still useful:
     *   - You change ONE field after other logic:
     *       $student->batch = 2026;
     *       $student->save();
     *   - You need to check true/false from save()
     *   - You build the model gradually before writing
     *
     * Both update() and save() are ELOQUENT — both talk to MySQL.
     * Prefer update($validated) for request-driven API updates.
     *
     * =====================================================================
     * 4) Eloquent vs Query Builder (DB::) vs raw — which & why
     * =====================================================================
     *
     * A) ELOQUENT (what we use here) — Student model
     *    Student::create([...])
     *    $student->update([...])
     *    $student->save()
     *    $student->delete()
     *
     *    Pros:
     *      - Uses the Model (Student)
     *      - Runs accessors / mutators / casts
     *      - Respects $fillable / $hidden
     *      - Events (creating, updating, …) can fire
     *      - Route model binding works with models
     *    Cons:
     *      - Slightly more overhead than raw SQL
     *
     * B) QUERY BUILDER — DB::table('students')
     *    use Illuminate\Support\Facades\DB;
     *    DB::table('students')->where('id', $id)->update([...]);
     *    DB::table('students')->insert([...]);
     *
     *    Pros:
     *      - Fast, flexible queries / joins / aggregates
     *      - No model required
     *    Cons:
     *      - NO mutators / accessors / $fillable / model events
     *      - You work with arrays/stdClass, not Student objects
     *      - Easy to forget mass-assignment safety
     *
     *    Example (works, but skips Student mutator that ucfirst's name):
     *      DB::table('students')->where('id', $student->id)->update($validated);
     *
     * C) RAW SQL — DB::update('UPDATE students SET ...')
     *    Use rarely (complex reports). Harder to maintain; no model features.
     *
     * RULE OF THUMB for this learning project:
     *   Single-row CRUD on a model  → Eloquent (create / update / save / delete)
     *   Complex joins / reports     → Query Builder (DB::table)
     *   Never needed for beginners  → raw SQL first
     *
     * =====================================================================
     * 5) Side-by-side: same update, three styles
     * =====================================================================
     *
     * // Eloquent update() — BEST for this API (what we use)
     * $student->update($validated);
     *
     * // Eloquent save() — same result, more lines
     * $student->fill($validated);
     * $student->save();
     *
     * // Eloquent property + save() — fine for one field; IDE may warn on accessors
     * $student->batch = $validated['batch'];
     * $student->save();
     *
     * // Query Builder — bypasses model layer
     * DB::table('students')->where('id', $student->id)->update($validated);
     *
     * =====================================================================
     * Request example: PUT /api/students/1
     *   { "name": "Ali", "email": "ali@example.com", "batch": 2024 }
     * =====================================================================
     */
    public function updateStudent(Request $request, Student $student): JsonResponse
    {
        // $student is ALREADY loaded by route model binding — no Student::find() here.

        /*
        |------------------------------------------------------------------
        | Postman / PUT body gotcha (why you still see old data)
        |------------------------------------------------------------------
        | Validation uses "sometimes" = only validate a field IF it is present.
        |
        | If Laravel receives NO body fields, $validated = [] and:
        |   $student->update([]);  → success, changes NOTHING
        | You get "Student updated" with the OLD row (e.g. "API VERIFY").
        |
        | Common cause in Postman with PUT/PATCH:
        |   Body → form-data  ❌  PHP often does NOT parse multipart body on PUT
        |
        | Use instead:
        |   Body → raw → JSON          ✅  Content-Type: application/json
        |   Body → x-www-form-urlencoded ✅
        |
        | Example JSON body (partial update — only name):
        |   { "name": "sams" }
        |
        | After save, accessor getNameAttribute() shows it UPPERCASE → "SAMS"
        |------------------------------------------------------------------
        */
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'min:2'],
            'email' => ['sometimes', 'required', 'email', 'unique:students,email,' . $student->id],
            'batch' => ['sometimes', 'required', 'integer', 'digits:4', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        // Empty body / unparsed form-data → nothing to update
        if ($validated === []) {
            return response()->json([
                'message' => 'No fields to update. For PUT/PATCH send JSON (Body → raw) or x-www-form-urlencoded — not form-data.',
                'hint' => [
                    'method' => 'PUT or PATCH',
                    'url' => '/api/students/' . $student->id,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'body_example' => ['name' => 'sams'],
                ],
                'received' => $request->all(),
            ], 422);
        }

        // Eloquent: fillable + mutators + one UPDATE query
        // Equivalent longer form: $student->fill($validated); $student->save();
        $student->update($validated);

        return response()->json([
            'message' => 'Student updated',
            // fresh() re-reads DB so response includes accessor formatting (e.g. NAME uppercased)
            'data' => $student->fresh(),
            'updated_fields' => array_keys($validated),
        ]);
    }
    /**
     * DELETE /api/delete-student/{id}
     *
     * CONTRAST with updateStudent():
     *   update uses {student} + Student $student  → binding (no find)
     *   delete uses {id} + plain $id              → you MUST findOrFail/find yourself
     *
     * Controller action name: often called destroy() in REST resources.
     * Model method stays delete(): $student->delete()
     *
     * Soft deletes (optional later): SoftDeletes trait + deleted_at column.
     * Then delete() marks the row instead of removing it.
     */
    public function deleteStudents($id): JsonResponse
    {
        $student = Student::find($id);

        if (! $student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        $deleted = $student->only(['id', 'name', 'email', 'batch']);
        $student->delete();

        return response()->json([
            'message' => 'Student deleted',
            'data' => $deleted,
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| Soft deletes (learning note — not enabled on Student yet)
|--------------------------------------------------------------------------
| Soft delete = mark deleted_at instead of removing the row.
|
| Migration:  $table->softDeletes();
| Model:      use SoftDeletes;
|
| Then:
|   $student->delete();        // sets deleted_at
|   $student->restore();       // clears deleted_at
|   $student->forceDelete();   // really remove
|   Student::withTrashed()...  // include soft-deleted
|   Student::onlyTrashed()...  // only soft-deleted
|
| Resource controller method name for HTTP DELETE is usually destroy(),
| while Eloquent's method on the model is delete().
*/
