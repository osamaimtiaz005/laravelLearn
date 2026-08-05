<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * =============================================================================
 * RESOURCE CONTROLLER — full beginner lesson (API style)
 * =============================================================================
 *
 * WHAT is a Resource Controller?
 *   A controller with STANDARD method names for CRUD:
 *     index, create, store, show, edit, update, destroy
 *
 * WHY use it?
 *   - Laravel can register ALL CRUD routes in ONE line (Route::resource / apiResource)
 *   - Everyone knows where list/create/update/delete live
 *   - Matches REST URL conventions
 *
 * HOW to create one:
 *   php artisan make:controller PhotoController --resource
 *     → 7 methods (includes create + edit for HTML forms)
 *
 *   php artisan make:controller PhotoController --api
 *     → 5 methods (NO create/edit — this file)
 *     → Perfect for JSON APIs / Postman / mobile apps
 *
 * HOW routes are registered (see routes/api.php):
 *   Route::apiResource('rsc-students', StudentResourceController::class);
 *
 * That ONE line creates:
 *   GET    /api/rsc-students            → index
 *   POST   /api/rsc-students            → store
 *   GET    /api/rsc-students/{rsc_student} → show
 *   PUT/PATCH /api/rsc-students/{rsc_student} → update
 *   DELETE /api/rsc-students/{rsc_student} → destroy
 *
 * NOTE on parameter name:
 *   apiResource('rsc-students', ...) uses singular snake param: {rsc_student}
 *   We type-hint Student $rsc_student OR rename with ->parameters([...])
 *
 * WEB resource (Blade forms) would also add:
 *   GET /photos/create      → create()  (HTML form)
 *   GET /photos/{photo}/edit → edit()   (HTML form)
 *   Registered with: Route::resource('photos', PhotoController::class);
 *
 * Hub page: GET /resource-controller
 * =============================================================================
 */
class StudentResourceController extends Controller
{
    /**
     * GET /api/rsc-students
     *
     * index = LIST all (or paginated) resources.
     * Named route: rsc-students.index
     */
    public function index(): JsonResponse
    {
        $students = Student::query()
            ->orderBy('id')
            ->limit(50)
            ->get(['id', 'name', 'email', 'batch']);

        return response()->json([
            'resource' => 'index',
            'count' => $students->count(),
            'data' => $students,
            'tip' => 'This method came from Route::apiResource — not a hand-written Route::get.',
        ]);
    }

    /**
     * POST /api/rsc-students
     *
     * store = CREATE a new resource from request body.
     * Named route: rsc-students.store
     * Status: 201 Created
     *
     * Body (JSON):
     *   { "name": "Ali", "email": "ali@example.com", "batch": 2024 }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required', 'email', 'unique:students,email'],
            'batch' => ['required', 'integer', 'digits:4', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        $student = Student::create($validated);

        return response()->json([
            'resource' => 'store',
            'message' => 'Student created via resource controller',
            'data' => $student,
        ], 201);
    }

    /**
     * GET /api/rsc-students/{rsc_student}
     *
     * show = READ one resource.
     * Named route: rsc-students.show
     *
     * Route model binding: {rsc_student} → Student (by id by default).
     * We renamed the parameter to $student via parameters() in routes/api.php
     * so the variable reads naturally. If you keep the default name it would be
     * Student $rsc_student.
     */
    public function show(Student $student): JsonResponse
    {
        return response()->json([
            'resource' => 'show',
            'binding' => 'Route model binding injected this Student',
            'data' => $student->only(['id', 'name', 'email', 'batch']),
        ]);
    }

    /**
     * PUT|PATCH /api/rsc-students/{rsc_student}
     *
     * update = UPDATE an existing resource.
     * Named route: rsc-students.update
     *
     * Partial update: "sometimes" so you can send only changed fields.
     * Prefer Body → raw → JSON in Postman (not form-data on PUT).
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'min:2'],
            'email' => ['sometimes', 'required', 'email', 'unique:students,email,' . $student->id],
            'batch' => ['sometimes', 'required', 'integer', 'digits:4', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        if ($validated === []) {
            return response()->json([
                'message' => 'No fields to update. Send JSON body (not form-data on PUT).',
                'example' => ['name' => 'sams'],
            ], 422);
        }

        $student->update($validated);

        return response()->json([
            'resource' => 'update',
            'message' => 'Student updated via resource controller',
            'updated_fields' => array_keys($validated),
            'data' => $student->fresh()->only(['id', 'name', 'email', 'batch']),
        ]);
    }

    /**
     * DELETE /api/rsc-students/{rsc_student}
     *
     * destroy = DELETE a resource.
     * Named route: rsc-students.destroy
     *
     * Naming:
     *   Controller method = destroy()   ← HTTP DELETE action (resource convention)
     *   Model method      = delete()    ← Eloquent actually removes/marks the row
     */
    public function destroy(Student $student): JsonResponse
    {
        $deleted = $student->only(['id', 'name', 'email', 'batch']);
        $student->delete();

        return response()->json([
            'resource' => 'destroy',
            'message' => 'Student deleted via resource controller',
            'data' => $deleted,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | WEB-ONLY methods (NOT in --api controllers)
    |--------------------------------------------------------------------------
    | Full --resource controllers also have:
    |
    |   create()  → GET /photos/create       return view('photos.create');
    |   edit()    → GET /photos/{photo}/edit return view('photos.edit', compact('photo'));
    |
    | Those return HTML forms. APIs do not need them — the client (React/Postman)
    | already has its own UI for "create/edit".
    |
    | Remember:
    |   Route::resource('photos', ...)     → 7 routes (web)
    |   Route::apiResource('photos', ...)  → 5 routes (api)
    */

    /**
     * Learning hub (WEB route, not part of apiResource).
     * GET /resource-controller
     */
    public function learning()
    {
        $students = Student::query()
            ->select(['id', 'name', 'email', 'batch'])
            ->orderBy('id')
            ->limit(5)
            ->get();

        return view('resource_controller.index', compact('students'));
    }
}
