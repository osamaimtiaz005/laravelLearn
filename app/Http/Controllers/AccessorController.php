<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

/**
 * AccessorController — DEMO: how accessors run on READ / DISPLAY
 *
 * Route (see routes/web.php):
 *   GET /accessors  →  AccessorController@list  →  name: accessor_mutator.index
 *   View: resources/views/accessor_mutator/index.blade.php
 *
 * Related:
 *   POST /save → MutatorController@save (writes data; mutators run there)
 *   Model: App\Models\Student
 *
 * =============================================================================
 * What is an accessor? (in this request flow)
 * =============================================================================
 *
 * When the Blade view does:
 *   {{ $student->name }}
 *   {{ $student->email }}
 *
 * Eloquent looks for getNameAttribute() / getEmailAttribute() on Student.
 * If found, the returned value is what Blade prints — not necessarily the raw DB text.
 *
 * Example for one row:
 *   DB:   name = "ALi",  email = "Ali@Mail.COM"
 *   View: name = "ALI",  email = "ali@mail.com"
 *
 * Accessors do NOT rewrite the database. They only change the value you get in PHP/views.
 *
 * =============================================================================
 * Why list() is so short
 * =============================================================================
 *
 * Controllers should stay thin:
 *   1) load data
 *   2) return a view
 *
 * Formatting rules live on the MODEL (accessors/mutators), so every place that
 * reads $student->name gets the same formatting automatically
 * (this page, pagination page, Eloquent list page, etc.).
 */
class AccessorController extends Controller
{
    /**
     * Show the demo page: form (for mutators) + student list (for accessors).
     *
     * Student::all()
     *   - Runs: SELECT * FROM students
     *   - Returns a Collection of Student models
     *   - Accessors do NOT run during the SQL query
     *   - Accessors run later, when the view reads each attribute
     *
     * compact('students')
     *   - Same as: ['students' => $students]
     *   - Makes $students available inside the Blade file
     */
    public function list()
    {
        // SQL loads raw rows into models. Display formatting happens on attribute access.
        $students = Student::all();

        return view('accessor_mutator.index', compact('students'));
    }
}
