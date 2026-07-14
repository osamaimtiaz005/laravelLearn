<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Student Eloquent model → table: students
 *
 * These @method lines teach the IDE (Intelephense) the real Eloquent signatures
 * (with optional parameters). Without them, the analyzer falsely reports
 * "Expected 2. Found 1" on find(), and "Expected 4. Found 2" on where().
 *
 * @method static Student|null find(mixed $id, array|string $columns = ['*'])
 * @method static Collection|Student[] all(array|string $columns = ['*'])
 * @method static Builder|Student query()
 * @method static Builder|Student where(mixed $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Student create(array $attributes = [])
 */
class Student extends Model
{
    // Columns allowed for Student::create([...]) / mass assignment
    /**
     * The $fillable property tells Laravel which columns are "mass assignable".
     *
     * - "Mass assignment" means you can create or update a model by passing
     *   an array of key-value pairs, and only the columns listed here will be set.
     * - This protects your data from "mass assignment vulnerability", where attackers
     *   could overwrite unintended fields by submitting extra form data.
     * - For example, Student::create(['name' => ..., 'email' => ..., 'batch' => ...])
     *   will ONLY assign values to the fields listed in $fillable.
     * - Any incoming form/request fields NOT in this list will be ignored for mass assignment.
     *
     * In summary: ONLY 'name', 'email', and 'batch' columns can be set via mass assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'batch',
    ];

    /**
     * Delete this student row from the database.
     * Declared here so the IDE knows delete() needs 0 arguments
     * (Intelephense otherwise picks a different delete($arg) signature).
     */
    public function delete()
    {
        return parent::delete();
    }

    // Tell Eloquent: "Don't expect created_at/updated_at columns on this table."
    // By default, Eloquent expects all tables to have 'created_at' and 'updated_at' columns,
    // and will try to set them automatically on insert/update. 
    // If your table does NOT have them, you must set this property to false,
    // or you'll get SQL errors about missing columns during save/update.
    public $timestamps = false;
}
