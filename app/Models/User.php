<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * IDE stubs (Intelephense): optional Eloquent args look "required" without these.
 *
 * @method static Builder|User query()
 * @method static Builder|User inRandomOrder(string|null $seed = null)
 * @method static User|null first(array|string $columns = ['*'])
 * @method static User|null find(mixed $id, array|string $columns = ['*'])
 * @method static User create(array $attributes = [])
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * METHOD NAME: profile
     *
     * ROLE IN ONE-TO-ONE = PARENT side
     *
     * return $this->hasOne(Profile::class);
     *   $this     = this User row/object
     *   ->        = call method
     *   hasOne    = "I have exactly one related row"
     *   Profile::class = App\Models\Profile
     *
     * How Laravel finds the profile:
     *   SELECT * FROM profiles WHERE user_id = THIS_USER.id LIMIT 1
     *
     * Why method name "profile"?
     *   So you can write: $user->profile
     *   And with(): User::with('profile')
     *
     * EXAMPLE:
     *   $user = User::find(1);
     *   echo $user->profile->phone;
     *
     * MISTAKE:
     *   hasMany() = many profiles (one-to-many)
     *   hasOne()  = one profile  (one-to-one)  ← use this
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);

        // Full form (same meaning):
        // return $this->hasOne(Profile::class, 'user_id', 'id');
        //                                     ↑ FK on profiles
        //                                              ↑ PK on users
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * ============================================================
     * TWO-WAY TEST — FORWARD (this method)
     * ============================================================
     *
     * Forward question:
     *   Can one user place many orders over time?
     *   True → hasMany(Order::class)
     *
     * Backward lives on Order:
     *   Does one order belong to only one user? → belongsTo(User::class)
     *
     * Both True = strict 1-to-many.
     *
     * Other name of the SAME link (from Order side):
     *   MANY-TO-ONE → Order::user() belongsTo(User::class)
     *   Demo page: /many-to-one
     *
     * return $this->hasMany(Order::class);
     *   $this     = this User
     *   hasMany   = "I have many child rows"
     *   Order::class = App\Models\Order
     *
     * SQL: SELECT * FROM orders WHERE user_id = THIS_USER.id
     *
     * Usage (Forward):
     *   $user->orders;                 // list of orders
     *   $user->orders()->count();
     *   $user->orders()->create([...]); // insert + set user_id
     *   User::with('orders')->get();
     *   User::withCount('orders')->get();
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
        // Full form: return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * ============================================================
     * MANY-TO-MANY — User ↔ Role (belongsToMany)
     * ============================================================
     *
     * WHAT IS A PIVOT?
     *   The middle table (role_user) that stores each User–Role link.
     *   Without it, you cannot model “many users ↔ many roles”.
     *
     *   users          role_user (PIVOT)           roles
     *   id=1 Ali       user_id=1, role_id=1   →    Admin
     *                  user_id=1, role_id=2   →    Editor
     *   id=2 Sara      user_id=2, role_id=1   →    Admin
     *
     *   Each pivot row = one link. Extra columns = data about the link
     *   (is_active, notes) via withPivot / $role->pivot->notes
     *
     *   attach = insert pivot row | detach = delete pivot row
     *
     * Two-Way Test:
     *   Forward:  one user can have many roles?     True
     *   Backward: one role can have many users?     True
     *   → MANY-TO-MANY (pivot: role_user)
     *
     * Useful methods (practice on /many-to-many):
     *   $user->roles;                              // Collection
     *   $user->roles()->attach($roleId);           // add link
     *   $user->roles()->attach($roleId, ['notes' => 'vip']);
     *   $user->roles()->detach($roleId);           // remove link
     *   $user->roles()->detach();                  // remove all
     *   $user->roles()->sync([1, 2]);              // exact set (drops others)
     *   $user->roles()->syncWithoutDetaching([3]); // add without dropping
     *   $user->roles()->toggle([1, 2]);            // add if missing / remove if present
     *   $user->roles()->updateExistingPivot($id, ['is_active' => false]);
     *   $user->roles()->wherePivot('is_active', true)->get();
     *   User::with('roles')->get();
     *   User::withCount('roles')->get();
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot('is_active', 'notes')
            ->withTimestamps();

        // Full form:
        // return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
        //     ->withPivot('is_active', 'notes')
        //     ->withTimestamps();
    }
}
