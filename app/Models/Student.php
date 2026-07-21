<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Student Eloquent model → table: students
 *
 * =============================================================================
 * ACCESSORS vs MUTATORS (interview-ready summary)
 * =============================================================================
 *
 * ACCESSOR  = runs when you READ  a value  ($student->name)
 *             Method name: get{Column}Attribute
 *             Changes what PHP/Blade SEE — does NOT change the DB row by itself.
 *
 * MUTATOR   = runs when you WRITE a value  ($student->name = 'ali')
 *             Method name: set{Column}Attribute
 *             Changes what gets STORED into $this->attributes (and then into DB on save).
 *
 * Flow when saving:
 *   Form "aLi"  →  setNameAttribute  →  DB stores "Ali"
 *
 * Flow when reading:
 *   DB has "Ali"  →  getNameAttribute  →  Blade shows "ALI"
 *
 * So BOTH can be active on the same column:
 *   write: mutator formats for storage
 *   read:  accessor formats for display
 *
 * =============================================================================
 * Naming rule (Laravel convention)
 * =============================================================================
 * Column:  name
 * Accessor: getNameAttribute($value)
 * Mutator:  setNameAttribute($value)
 *
 * Column:  email
 * Accessor: getEmailAttribute($value)
 * Mutator:  setEmailAttribute($value)   ← not defined here yet
 *
 * Column with underscore: permanent_address
 * Accessor: getPermanentAddressAttribute($value)
 * Mutator:  setPermanentAddressAttribute($value)
 *
 * =============================================================================
 * IDE @method lines (Intelephense)
 * =============================================================================
 * Teach the IDE the real Eloquent signatures (optional parameters).
 * Without them, the analyzer may falsely report
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
    /**
     * Mass assignment whitelist.
     *
     * Only these keys are accepted by Student::create([...]) / $student->fill([...]) / update([...]).
     * Protects against attackers posting unexpected fields.
     *
     * Demo pages that use this model:
     * - /accessors  (accessor + mutator demo)
     * - elqQueryBuilder student list
     * - /paginate/list
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

    /**
     * 
     * students table has no created_at / updated_at columns.
     * If this stayed true (default), Eloquent would try to write those columns and SQL would fail.
     * Tell Eloquent: "Don't expect created_at/updated_at columns on this table."
     * By default, Eloquent expects all tables to have 'created_at' and 'updated_at' columns,
     * and will try to set them automatically on insert/update. 
     * If your table does NOT have them, you must set this property to false,
     * or you'll get SQL errors about missing columns during save/update.
     */
    public $timestamps = false;

    // =========================================================================
    // ACCESSORS — format OUTPUT when reading $student->column
    // =========================================================================

    /**
     * Accessor for "name".
     *
     * Called automatically whenever you read:
     *   $student->name
     *   {{ $student->name }} in Blade
     *
     * $value = the raw value currently in the model's attributes (usually from DB).
     *
     * Example:
     *   DB row: name = "Ali"
     *   {{ $student->name }} → "ALI"  (strtoupper)
     *
     * Important:
     * - This does NOT UPDATE the database.
     * - It only changes what you get when reading in PHP/views.
     * - To see the raw DB value without the accessor, use:
     *     $student->getRawOriginal('name')
     *   or (Laravel 9+ style Attribute API is different; this file uses classic accessors).
     */
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    /**
     * Accessor for "email".
     *
     * Example:
     *   DB row: email = "Ali@Mail.COM"   (or whatever was saved)
     *   {{ $student->email }} → "ali@mail.com"
     *
     * Useful for consistent display (emails are usually shown lowercase).
     */
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }

    // =========================================================================
    // MUTATORS — format INPUT when assigning $student->column = ...
    // =========================================================================

    /**
     * Mutator for "name".
     *
     * Called automatically whenever you write:
     *   $student->name = $request->name;
     *   Student::create(['name' => $request->name, ...]);  // also triggers mutators
     *
     * YOU MUST assign into $this->attributes['name'].
     * If you only "return" a value, nothing is stored (classic mutators work by writing attributes).
     *
     * Example (MutatorController):
     *   User types: "aLi khan"
     *   setNameAttribute runs → ucfirst("aLi khan") → "ALi khan"
     *   save() stores "ALi khan" in DB
     *
     * Then when listing:
     *   getNameAttribute runs → strtoupper("ALi khan") → "ALI KHAN" in the view
     *
     * Note: ucfirst only uppercases the FIRST character of the whole string,
     * not each word. For title-case words you'd use something like ucwords().
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    
     // --------------------------------------------------------------------------
     // Optional learning notes
     // --------------------------------------------------------------------------
     //
     // 1) batch has NO accessor/mutator → read/write exactly as stored.
     //
     // 2) Order of events on create:
     //      $student->name = 'ali';     // mutator runs NOW
     //      $student->save();           // SQL INSERT with mutated value
     //      later: $student->name       // accessor runs on READ
     //
     // 3) Interview Q: Difference between accessor and mutator?
     //    A: Accessor transforms attribute when retrieving; mutator transforms
     //       when setting. Accessor affects display; mutator affects stored data.
     //
     // 4) Laravel 9+ and 10+ also have Attribute::make(get:, set:) syntax as an alternative.
     //    In Laravel 12, you can use either the classic getXAttribute/setXAttribute methods
     //    (as shown above), or the newer Attribute::make like:
     //
     //    // Example for Laravel 12+ Attribute::make syntax:
     //    use Illuminate\Database\Eloquent\Casts\Attribute;
     //
     //    public function name(): Attribute
     //    {
     //        return Attribute::make(
     //            get: fn ($value) => strtoupper($value),
     //            set: fn ($value) => ucfirst($value),
     //        );
     //    }
     //
     //    // This is exactly equivalent to defining getNameAttribute/setNameAttribute.
     //
     //    // This project uses the classic getXAttribute/setXAttribute style for learning clarity.
}
