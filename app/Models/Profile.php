<?php

// ============================================================
// FILE: Profile.php (MODEL)
// PURPOSE: Talk to the "profiles" table + define belongsTo User
// ============================================================

// namespace = App\Models means this file lives in app/Models
namespace App\Models;

// Model = Laravel Eloquent base class (gives find, create, with, etc.)
use Illuminate\Database\Eloquent\Model;

/**
 * CLASS: Profile
 * extends Model = this class is an Eloquent model
 *
 * ROLE IN ONE-TO-ONE = CHILD side
 *   - Table name Laravel expects: "profiles" (plural of Profile)
 *   - This table HAS the foreign key column: user_id
 *   - Relationship method: belongsTo(User::class)
 *
 * SIMPLE PICTURE:
 *   profiles.user_id = 1  means  "this profile belongs to user id 1"
 *
 * EXAMPLE:
 *   $profile = Profile::find(1);   // get profile with id 1
 *   echo $profile->user->name;    // print that user's name
 *
 * MISTAKE:
 *   Do NOT write hasOne() here.
 *   hasOne() belongs on User (parent).
 *   belongsTo() belongs on Profile (child that has user_id).
 * What is Mass Assignment?
 * Mass assignment is the process of assigning multiple model attributes at once using an array, such as with create() or update().
 * Why does Laravel use $fillable?
 * To protect against mass assignment vulnerabilities, 
 * where users could submit values for sensitive columns like is_admin, role, or salary.
 */
class Profile extends Model
{
    /**
     * $fillable = list of columns allowed in create() / update()
     *
     * Why needed?
     *   Laravel blocks mass assignment for safety.
     *   Only columns listed here can be set like:
     *     Profile::create(['phone' => '123', ...])
     *
     * Word meaning:
     *   protected = only this class (and children) can use this property
     *   $fillable = array of safe column names
     */
    protected $fillable = [
        'user_id',  // foreign key → users.id
        'phone',    // profile phone number
        'address',  // street address
        'city',     // city name
        'state',    // state / province
        'zip',      // postal code
    ];

    /**
     * METHOD NAME: user
     *
     * Why named "user"?
     *   Laravel uses the method name to guess the foreign key:
     *     user()  →  looks for column user_id
     *
     * return $this->belongsTo(User::class);
     *   $this       = this Profile row/object
     *   ->          = call method
     *   belongsTo   = "I belong to one parent record"
     *   User::class = full class name of User model (App\Models\User)
     *
     * USAGE:
     *   $profile->user;        // get User model (or null)
     *   $profile->user->email; // get that user's email
     */
    public function user()
    {
        return $this->belongsTo(User::class);

        // Full form (same meaning):
        // return $this->belongsTo(User::class, 'user_id', 'id');
        //                                      ↑ foreign key on profiles
        //                                               ↑ primary key on users
    }
}
