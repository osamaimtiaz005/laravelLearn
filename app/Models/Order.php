<?php

// ============================================================
// FILE: Order.php
// MANY-TO-ONE side = belongsTo(User)
// (same DB link as one-to-many, viewed from the child)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * MANY-TO-ONE vs ONE-TO-MANY (same relationship!)
 * ============================================================
 *
 * ONE-TO-MANY (parent view):
 *   One User has many Orders
 *   User::orders() → hasMany(Order::class)
 *
 * MANY-TO-ONE (child view):
 *   Many Orders belong to one User
 *   Order::user() → belongsTo(User::class)
 *
 * Same tables. Same foreign key: orders.user_id → users.id
 * Only the STARTING POINT changes:
 *   Start at User  → call it one-to-many
 *   Start at Order → call it many-to-one
 *
 * Picture:
 *   Order #101 ─┐
 *   Order #102 ─┼──► User Ali (id=1)
 *   Order #103 ─┘
 *   Many orders → one user  = MANY-TO-ONE
 *
 * TWO-WAY TEST (same as one-to-many):
 *   Forward (1→many): one user, many orders     True
 *   Backward (many→1): one order, only one user True
 *
 * Laravel method for many-to-one: belongsTo
 *
 * Useful belongsTo helpers:
 *   $order->user                         // get the one parent
 *   $order->user()->associate($user)     // point order to another user
 *   $order->user()->dissociate()         // clear user_id (needs nullable FK)
 *   Order::with('user')->get()           // eager load parents
 *   Order::where('user_id', 1)->get()    // all orders of user 1
 *   Order::whereBelongsTo($user)->get()  // same, nicer API
 */
class Order extends Model
{
    /**
     * Mass-assignable columns for create([...]).
     * user_id not listed — prefer associate() or $user->orders()->create()
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * ============================================================
     * MANY-TO-ONE method: user()
     * ============================================================
     *
     * return $this->belongsTo(User::class);
     *
     * Word by word:
     *   $this       = this Order (one of the "many")
     *   belongsTo   = "I belong to one parent"  ← many-to-one in English
     *   User::class = the "one" side
     *
     * Method name user() → Laravel looks for user_id column
     *
     * Examples:
     *   $order = Order::find(1);
     *   echo $order->user->name;     // the one owner
     *   echo $order->user_id;        // FK value
     */
    public function user()
    {
        return $this->belongsTo(User::class);
        // Full form: return $this->belongsTo(User::class, 'user_id', 'id');
        //                                          ↑ FK on orders     ↑ PK on users
    }
}
