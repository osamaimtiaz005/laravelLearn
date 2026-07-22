<?php

// ============================================================
// FILE: OnetoManyController.php
// PURPOSE: Demo + teach ONE-TO-MANY (User → Orders)
// ============================================================

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * ============================================================
 * THE TWO-WAY TEST (prove it is 1-to-many)
 * ============================================================
 *
 * FORWARD test (User → Orders):
 *   Can one user place many orders over time?
 *   YES → $user->orders  (hasMany) returns a list
 *
 * BACKWARD test (Order → User):
 *   Does one specific order belong to only one user?
 *   YES → $order->user  (belongsTo) returns one user
 *   An order cannot belong to multiple users.
 *
 * Both TRUE = strict one-to-many.
 *
 * FAIL examples:
 *   Forward false + Backward false → many-to-many (User ↔ Product shopping)
 *   Forward false + Backward true  → one-to-one (User ↔ Profile)
 *
 * Code map:
 *   User::orders() → hasMany(Order::class)   // FORWARD
 *   Order::user()  → belongsTo(User::class)  // BACKWARD
 *   DB: orders.user_id → users.id (NOT unique)
 */
class OnetoManyController extends Controller
{
    /**
     * GET /one-to-many
     *
     * Loads data for the Blade "Two-Way Test" page.
     * with('orders') / with('user') = eager load both directions.
     */
    public function index()
    {
        // FORWARD data: each user + their many orders
        $users = User::with('orders')->withCount('orders')->get();

        // BACKWARD data: each order + its one user
        $orders = Order::with('user')->get();

        // One clear example for the Two-Way Test box (if data exists)
        $sampleUser = $users->firstWhere(fn ($u) => $u->orders_count > 0) ?? $users->first();
        $sampleOrder = $orders->first();

        return view('one_to_many.index', [
            'users' => $users,
            'orders' => $orders,
            'sampleUser' => $sampleUser,
            'sampleOrder' => $sampleOrder,
        ]);
    }

    /**
     * GET /one-to-many/users
     * FORWARD JSON: one user → many orders
     */
    public function oneToMany()
    {
        // with('orders') = load child rows for the forward direction
        return User::with('orders')->withCount('orders')->get();
    }

    /**
     * GET /one-to-many/orders
     * BACKWARD JSON: many orders → each has one user
     */
    public function manyToOne()
    {
        // with('user') = load parent for the backward direction
        return Order::with('user')->get();
    }

    /**
     * GET /one-to-many/user/{id}
     * FORWARD for ONE user: show all orders that user placed
     */
    public function userOrders($id)
    {
        // find = one row by primary key; with = also load orders
        $user = User::with('orders')->withCount('orders')->find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Two-Way Test (forward) in the JSON response
        return response()->json([
            'two_way_test' => [
                'direction' => 'FORWARD',
                'question' => 'Can one user place many orders?',
                'answer' => $user->orders_count >= 1
                    ? 'True — this user has '.$user->orders_count.' order(s)'
                    : 'True in theory — this user has 0 orders so far (create more)',
            ],
            'user' => $user,
        ]);
    }

    /**
     * GET /one-to-many/order/{id}
     * BACKWARD for ONE order: show the single owning user
     */
    public function orderUser($id)
    {
        $order = Order::with('user')->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Two-Way Test (backward) in the JSON response
        return response()->json([
            'two_way_test' => [
                'direction' => 'BACKWARD',
                'question' => 'Does this order belong to only one user?',
                'answer' => $order->user
                    ? 'True — only user #'.$order->user->id.' ('.$order->user->name.')'
                    : 'False / broken — missing user_id',
            ],
            'order' => $order,
        ]);
    }

    /**
     * GET /one-to-many/create/{id}
     * Helps FORWARD test: same user can get another order row
     */
    public function createOrder($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // orders() with () = relationship builder; create() inserts + sets user_id
        $order = $user->orders()->create([
            'name' => 'Demo '.Str::random(4),
            'description' => 'Created from one-to-many demo',
            'price' => rand(100, 1000).' USD',
        ]);

        return response()->json([
            'two_way_test_forward' => 'True — same user can keep placing more orders',
            'message' => 'Order created',
            'user_id' => $user->id,
            'order' => $order,
            'total_orders' => $user->orders()->count(),
        ]);
    }

    /**
     * GET /one-to-many/count/{id}
     * Useful hasMany helpers while learning
     */
    public function orderCount($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user->name,
            // count() = how many related orders (forward)
            'orders_count' => $user->orders()->count(),
            // pluck = list of one column only
            'order_names' => $user->orders()->pluck('name'),
            // whereHas = users filtered by related order rules
            'users_with_demo_orders' => User::whereHas('orders', function ($q) {
                $q->where('name', 'like', '%Demo%');
            })->pluck('name'),
            // has = at least one related row
            'users_with_any_order' => User::has('orders')->pluck('name'),
            // doesntHave = zero related rows
            'users_with_no_order' => User::doesntHave('orders')->pluck('name'),
        ]);
    }
}
