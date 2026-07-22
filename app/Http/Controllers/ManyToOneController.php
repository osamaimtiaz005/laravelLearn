<?php

// ============================================================
// FILE: ManyToOneController.php
// PURPOSE: Teach MANY-TO-ONE (Order belongsTo User)
// ============================================================

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

/**
 * ============================================================
 * MANY-TO-ONE (beginner)
 * ============================================================
 *
 * English:
 *   Many Orders belong to one User.
 *
 * Laravel:
 *   Order::user() → belongsTo(User::class)
 *
 * Important:
 *   Many-to-one is NOT a second table design.
 *   It is the OTHER NAME for the same User↔Order link
 *   when you start from the Order (child) side.
 *
 * Compare:
 *   /one-to-many  → start at User  (hasMany)   FORWARD
 *   /many-to-one  → start at Order (belongsTo) BACKWARD / many→one
 *
 * Routes (/many-to-one):
 *   GET /                      HTML lesson + live data
 *   GET /orders                all orders + their one user
 *   GET /order/{id}            one order → its user
 *   GET /for-user/{userId}     all orders that belong to that user
 *   GET /associate/{orderId}/{userId}  change which user owns an order
 */
class ManyToOneController extends Controller
{
    /**
     * GET /many-to-one
     * Teaching page: what many-to-one is + live proof
     */
    public function index()
    {
        // Start from CHILD table (orders) — many-to-one mindset
        $orders = Order::with('user')->latest()->get();

        // Group mental picture: many order rows can share one user_id
        $users = User::withCount('orders')->orderByDesc('orders_count')->get();

        $sampleOrder = $orders->first();
        $sampleUser = $users->firstWhere(fn ($u) => $u->orders_count > 0) ?? $users->first();

        return view('many_to_one.index', [
            'orders' => $orders,
            'users' => $users,
            'sampleOrder' => $sampleOrder,
            'sampleUser' => $sampleUser,
        ]);
    }

    /**
     * GET /many-to-one/orders
     * Many orders, each with ONE user (JSON)
     */
    public function orders()
    {
        // with('user') = for every order, load the single parent user
        return Order::with('user')->get();
    }

    /**
     * GET /many-to-one/order/{id}
     * One order → exactly one user (many-to-one core demo)
     */
    public function order($id)
    {
        $order = Order::with('user')->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'concept' => 'MANY-TO-ONE',
            'meaning' => 'This order belongs to one user (belongsTo)',
            'code' => '$order->user',
            'order' => $order,
            'owner' => $order->user,
        ]);
    }

    /**
     * GET /many-to-one/for-user/{userId}
     * "Give me all orders that belong to this one user"
     * Still many-to-one thinking: filter children by parent id
     */
    public function forUser($userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Style A: where on FK column
        $ordersA = Order::with('user')->where('user_id', $user->id)->get();

        // Style B: whereBelongsTo (Laravel helper for belongsTo)
        $ordersB = Order::with('user')->whereBelongsTo($user)->get();

        return response()->json([
            'concept' => 'MANY-TO-ONE query: find many children of one parent',
            'user' => $user->only(['id', 'name', 'email']),
            'using_where_user_id' => $ordersA,
            'using_whereBelongsTo' => $ordersB,
            'count' => $ordersB->count(),
        ]);
    }

    /**
     * GET /many-to-one/associate/{orderId}/{userId}
     * Change the "one" side: point this order at another user
     *
     * associate() = set user_id from a User model (belongsTo helper)
     */
    public function associate($orderId, $userId)
    {
        $order = Order::find($orderId);
        $user = User::find($userId);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $oldUserId = $order->user_id;

        // MANY-TO-ONE write helper:
        //   associate($user) sets orders.user_id = $user->id
        //   save() writes it to the database
        $order->user()->associate($user);
        $order->save();

        $order->load('user');

        return response()->json([
            'concept' => 'belongsTo associate() — change which one user owns this order',
            'old_user_id' => $oldUserId,
            'new_user_id' => $order->user_id,
            'order' => $order,
            'note' => 'Still many-to-one: this order has exactly one user_id at a time',
        ]);
    }
}
