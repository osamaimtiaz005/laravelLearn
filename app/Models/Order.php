<?php

// ============================================================
// FILE: Order.php — BACKWARD side of the Two-Way Test
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * TWO-WAY TEST — BACKWARD (this model)
 * ============================================================
 *
 * Backward question:
 *   Does a specific order belong to only ONE user?
 *   True → belongsTo(User::class)
 *
 * Forward lives on User:
 *   Can one user place many orders? → hasMany(Order::class)
 *
 * Both True = strict 1-to-many.
 *
 * Why NOT User–Product shopping?
 *   Backward fails: one product can be bought by many users
 *   → that is many-to-many, not one-to-many.
 *
 * Table: orders
 * FK:    user_id → users.id  (single value per order row)
 */
class Order extends Model
{
    /**
     * Mass-assignable columns for create([...]).
     *
     * user_id is NOT listed:
     *   set it safely with $user->orders()->create([...])
     *   so the form/request cannot pick any user_id freely.
     */
    protected $fillable = [
        'name',         // short order label / demo name
        'description',  // order notes
        'price',        // demo price text (e.g. "430 USD")
    ];

    /**
     * BACKWARD relationship: Order → User
     *
     * Word by word:
     *   public function user()
     *     method name "user" → Laravel looks for column user_id
     *
     *   return $this->belongsTo(User::class);
     *     $this      = this one Order row
     *     belongsTo  = "I belong to one parent"
     *     User::class = App\Models\User
     *
     * Two-Way Test (Backward):
     *   $order->user;     // ONE user object (not a list)
     *   // An order cannot belong to multiple users.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
        // Full form: return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
