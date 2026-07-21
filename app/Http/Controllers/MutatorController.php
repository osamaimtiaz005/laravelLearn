<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

/**
 * MutatorController — DEMO: how mutators run on WRITE / SAVE
 *
 * Route (see routes/web.php):
 *   POST /save  →  MutatorController@save  →  name: accessor_mutator.save
 *
 * Related:
 *   GET  /accessors → AccessorController@list → shows students AFTER accessors format them
 *   Model: App\Models\Student  (setNameAttribute + getNameAttribute / getEmailAttribute)
 *
 * =============================================================================
 * What is a mutator? (in this request flow)
 * =============================================================================
 *
 * When this controller does:
 *   $student->name = $request->name;
 *
 * Eloquent does NOT put the raw form value into the DB immediately.
 * It first looks for setNameAttribute() on the Student model.
 * If found, THAT method decides what gets stored in $this->attributes['name'].
 *
 * So the mutator is the "gatekeeper" for incoming attribute values.
 *
 * =============================================================================
 * Full request lifecycle for this demo
 * =============================================================================
 *
 * 1) User opens /accessors (list page with form)
 * 2) User types name="aLi", email="Ali@Mail.COM", batch="2024"
 * 3) Form POSTs to /save (this method)
 * 4) @csrf is validated by Laravel middleware
 * 5) We assign attributes → mutator runs on name
 * 6) save() writes INSERT into students
 * 7) Redirect back to list with flash message
 * 8) List reads $student->name / email → accessors format for display
 */
class MutatorController extends Controller
{
    /**
     * Save a new student and demonstrate mutators.
     *
     * Try typing mixed-case name like "aLi" then check:
     * - DB (phpMyAdmin): name should be "ALi" because of setNameAttribute + ucfirst
     * - Blade list: name should show "ALI" because of getNameAttribute + strtoupper
     */
    public function save(Request $request)
    {
        // new Student() = create an empty model instance in memory (no DB row yet)
        $student = new Student();

        /*
         | MUTATOR TRIGGER
         | ---------------
         | Assigning to ->name calls Student::setNameAttribute($request->name)
         | which does: $this->attributes['name'] = ucfirst($value);
         |
         | So if form sends "aLi", attributes['name'] becomes "ALi" BEFORE save().
         */
        $student->name = $request->name;

        /*
         | No setEmailAttribute mutator exists on Student.
         | So email is stored exactly as typed (e.g. "Ali@Mail.COM").
         | Later, getEmailAttribute will still lowercase it when READING in the view.
         */
        $student->email = $request->email;

        /*
         | batch also has no mutator/accessor → stored and shown as-is.
         | Form uses type="number", but DB column is string — MySQL will still accept it.
         */
        $student->batch = $request->batch;

        /*
         | save() builds INSERT (or UPDATE if the model already had an id).
         | For a brand-new model, this creates a new row using $this->attributes.
         |
         | Alternative styles (also trigger mutators):
         |   Student::create($request->only(['name', 'email', 'batch']));
         |   $student->fill([...])->save();
         */
        $student->save();

        /*
         | wasRecentlyCreated = true only if THIS save() just inserted a new row.
         | Useful flash feedback for the next page (redirect).
         |
         | with('success', ...) stores a one-request flash message in the session.
         */
        if ($student->wasRecentlyCreated) {
            return redirect()
                ->route('accessor_mutator.index')
                ->with('success', 'Student created successfully (mutator may have changed name before save)');
        }

        return redirect()
            ->route('accessor_mutator.index')
            ->with('error', 'Student not created');
    }
}
