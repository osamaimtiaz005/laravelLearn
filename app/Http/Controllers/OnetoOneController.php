<?php

// ============================================================
// FILE: OnetoOneController.php
// PURPOSE: Handle all /one-to-one URLs for the learning demo
// ============================================================

// namespace = folder path of this class in PHP
// App\Http\Controllers = app/Http/Controllers folder
namespace App\Http\Controllers;

// use = import a class so we can write Profile instead of App\Models\Profile
use App\Models\Profile; // Profile model = rows in "profiles" table
use App\Models\Subscription;
use App\Models\User;    // User model = rows in "users" table
use Illuminate\Support\Facades\Hash; // Hash = tool to encrypt passwords

/**
 * CLASS NAME: OnetoOneController
 * extends Controller = inherit Laravel's base controller helpers
 *
 * ONE-TO-ONE MEANING (simple):
 *   One User has only ONE Profile.
 *   One Profile belongs to only ONE User.
 *
 * LINK IN DATABASE:
 *   users.id  <---  profiles.user_id
 *
 * ROUTES (prefix /one-to-one):
 *   GET /one-to-one              -> index()
 *   GET /one-to-one/users        -> users()
 *   GET /one-to-one/profiles     -> profiles()
 *   GET /one-to-one/user/{id}    -> userProfile($id)
 *   GET /one-to-one/profile/{id} -> profileUser($id)
 *   GET /one-to-one/create/{id}  -> createProfile($id)
 */
class OnetoOneController extends Controller
{
    // --------------------------------------------------------
    // METHOD: index
    // URL:    GET /one-to-one
    // RETURN: HTML page (Blade view)
    // --------------------------------------------------------
    public function index()
    {
        // $this = this controller object
        // -> = call a method on an object
        // seedDemoIfNeeded() = create sample data if table is empty
        $this->seedDemoIfNeeded();

        // return = send response back to browser
        // view('one_to_one.index', [...])
        //   'one_to_one.index' = file resources/views/one_to_one/index.blade.php
        //   second argument = array of data sent to the view
        return view('one_to_one.index', [

            // KEY 'usersWithProfile' = variable name inside Blade: $usersWithProfile
            // User:: = call static method on User model (no need to new User)
            // with('profile') = also load related profile in same request (eager load)
            //   'profile' matches method name public function profile() on User model
            // get() = run SQL SELECT and return ALL matching rows as a Collection
            'usersWithProfile' => User::with('profile')->get(),

            // Profile::with('user')->get()
            //   with('user') = load related user (matches Profile::user() method)
            //   get() = get all profiles
            'profilesWithUser' => Profile::with('user')->get(),
        ]);
    }

    // --------------------------------------------------------
    // METHOD: users
    // URL:    GET /one-to-one/users
    // RETURN: JSON list of users, each with nested profile
    // EXAMPLE CODE MEANING: $user->profile
    // --------------------------------------------------------
    public function users()
    {
        // Make sure demo data exists before showing list
        $this->seedDemoIfNeeded();

        // User::with('profile')->get()
        // Word by word:
        //   User      = model for users table
        //   ::         = static call (class method, not object method)
        //   with(...)  = join/load relationship in advance
        //   'profile'  = relationship method name on User
        //   get()      = fetch all rows
        // Laravel will auto-convert this Collection to JSON in browser
        return User::with('profile')->get();
    }

    // --------------------------------------------------------
    // METHOD: profiles
    // URL:    GET /one-to-one/profiles
    // RETURN: JSON list of profiles, each with nested user
    // EXAMPLE CODE MEANING: $profile->user
    // --------------------------------------------------------
    public function profiles()
    {
        $this->seedDemoIfNeeded();

        // Profile::with('user')->get()
        //   Profile = model for profiles table
        //   with('user') = also fetch the owner user for each profile
        //   get() = all profiles
        return Profile::with('user')->get();
    }

    // --------------------------------------------------------
    // METHOD: userProfile
    // URL:    GET /one-to-one/user/1   (1 is {id})
    // PARAM:  $id = number from URL, e.g. 1
    // RETURN: one user + their profile as JSON
    // --------------------------------------------------------
    public function userProfile($id)
    {
        // find($id) = SELECT * FROM users WHERE id = $id LIMIT 1
        // with('profile') = also load that user's profile
        // Result stored in $user variable (or null if not found)
        $user = User::with('profile')->find($id);

        // if (! $user) means: if $user is null / empty / not found
        // ! = NOT operator (true becomes false, false becomes true)
        if (! $user) {
            // response()->json(...) = return JSON response
            // first arg = data array
            // second arg = HTTP status code
            // 404 = Not Found
            return response()->json(['message' => 'User not found'], 404);
        }

        // return the User model (includes profile if loaded)
        return $user;
    }

    // --------------------------------------------------------
    // METHOD: profileUser
    // URL:    GET /one-to-one/profile/1
    // PARAM:  $id = profile id from URL
    // RETURN: one profile + their user as JSON
    // --------------------------------------------------------
    public function profileUser($id)
    {
        // Find profile by id, and load related user
        $profile = Profile::with('user')->find($id);

        // If no profile with that id exists
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        // Return profile object (user is nested inside because of with('user'))
        return $profile;
    }

    // --------------------------------------------------------
    // METHOD: createProfile
    // URL:    GET /one-to-one/create/1
    // PARAM:  $id = user id who will get a new profile
    // ACTION: create ONE profile for that user (if they don't have one)
    // --------------------------------------------------------
    public function createProfile($id)
    {
        // Find user by id (no with() needed yet)
        $user = User::find($id);

        // Stop if user does not exist
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // $user->profile
        //   ->profile (no parentheses) = Laravel calls profile() relationship
        //   and returns the related Profile model OR null
        // if ($user->profile) = if profile already exists
        if ($user->profile) {
            // One-to-one rule: do NOT create a second profile
            return response()->json([
                'message' => 'User already has a profile (one-to-one)',
                'profile' => $user->profile, // show existing profile
            ]);
        }

        // $user->profile()->create([...])
        // Word by word:
        //   $user          = the User model instance
        //   ->profile()    = WITH parentheses = relationship QUERY builder
        //                    (not the loaded model; used to create/save)
        //   ->create([...]) = INSERT a new row into profiles table
        // Laravel AUTOMATICALLY sets profiles.user_id = $user->id
        //
        // BAD example (beginner mistake):
        //   Profile::create(['phone' => '...']);  // forgot user_id!
        $profile = $user->profile()->create([
            'phone' => '+92-300-0000000',      // column phone
            'address' => '123 Learning Street', // column address
            'city' => 'Lahore',                 // column city
            'state' => 'Punjab',                // column state
            'zip' => '54000',                   // column zip
        ]);

        // Return success message + both models as JSON
        return response()->json([
            'message' => 'Profile created',
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    // --------------------------------------------------------
    // METHOD: seedDemoIfNeeded (PRIVATE)
    // private = only this class can call it (not from routes)
    // : void = this method returns nothing
    // PURPOSE: insert 1 sample user+profile if profiles table empty
    // --------------------------------------------------------
    private function seedDemoIfNeeded(): void
    {
        // Profile::query() = start a database query on profiles
        // ->exists() = true if at least 1 row exists, false if empty
        if (Profile::query()->exists()) {
            // return; = stop method here (do nothing else)
            return;
        }

        // User::query()->first() = get the first user row, or null
        $user = User::query()->first();

        // If no user exists in users table, create one
        if (! $user) {
            // create([...]) = INSERT into users table and return User model
            $user = User::query()->create([
                'name' => 'Demo User',                      // users.name
                'email' => 'demo-one-to-one@example.com',   // users.email
                // Hash::make('password') = encrypt plain text password
                // never store password as plain text
                'password' => Hash::make('password'),
            ]);
        }

        // Create related profile for that user
        // user_id is set automatically by Laravel
        $user->profile()->create([
            'phone' => '+92-300-0000000',
            'address' => '123 Learning Street',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'zip' => '54000',
        ]);
    }
    public function showSubscription()
    {
        $subscription = User::with('subscription')->get();
        return $subscription;
    }
}
