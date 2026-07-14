<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| AllrouteController — real-life HTTP method examples
|--------------------------------------------------------------------------
|
| Imagine a tiny shop / account area (no database — we use session):
|
|   GET     → browse product catalog
|   POST    → place a new order
|   PUT     → replace the whole user profile
|   PATCH   → change only one setting (email notifications)
|   DELETE  → remove an item from the shopping cart
|   MATCH   → contact form (same URL for show + submit)
|   ANY     → payment gateway webhook (any method)
|   VIEW    → static About Us page (no controller needed)
|
|--------------------------------------------------------------------------
*/
class AllrouteController extends Controller
{
    // Starter cart for the DELETE demo
    private function defaultCart(): array
    {
        return [
            ['id' => 1, 'name' => 'Wireless Mouse', 'price' => 1200],
            ['id' => 2, 'name' => 'USB Cable', 'price' => 350],
            ['id' => 3, 'name' => 'Laptop Stand', 'price' => 2500],
        ];
    }

    // Hub page: every real-life example in one place
    public function index()
    {
        // First visit → put starter cart in session
        if (! session()->has('shop_cart')) {
            session(['shop_cart' => $this->defaultCart()]);
        }

        return view('Allroute.index', [
            'profile' => session('shop_profile', [
                'name' => 'Ali Khan',
                'email' => 'ali@example.com',
                'phone' => '0300-1234567',
                'city' => 'Lahore',
            ]),
            'notifyEmail' => session('shop_notify_email', true),
            'cart' => session('shop_cart', []),
            'orders' => session('shop_orders', []),
            'webhookLog' => session('shop_webhook_log', []),
        ]);
    }

    // Helper button on the hub page: restore starter cart items
    public function resetCart()
    {
        session(['shop_cart' => $this->defaultCart()]);

        return redirect()->route('allroute.index');
    }

    /*
    |--------------------------------------------------------------------------
    | GET — Browse product catalog (read-only, safe for links / address bar)
    | Real life: Amazon product list, YouTube video page, Google search results
    |--------------------------------------------------------------------------
    */
    public function getExample()
    {
        $products = [
            ['id' => 1, 'name' => 'Wireless Mouse', 'price' => 1200, 'stock' => 40],
            ['id' => 2, 'name' => 'USB Cable', 'price' => 350, 'stock' => 120],
            ['id' => 3, 'name' => 'Laptop Stand', 'price' => 2500, 'stock' => 15],
            ['id' => 4, 'name' => 'Mechanical Keyboard', 'price' => 8500, 'stock' => 8],
        ];

        return view('Allroute.get', [
            'method' => request()->method(),
            'products' => $products,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | POST — Place a new order (create something new)
    | Real life: signup form, checkout, "Add to cart", create blog post
    |--------------------------------------------------------------------------
    */
    public function postExample(Request $request)
    {
        $request->validate([
            'product' => 'required|string|max:100',
            'qty' => 'required|integer|min:1|max:20',
            'customer' => 'required|string|max:80',
        ]);

        $orders = session('shop_orders', []);
        $orders[] = [
            'id' => count($orders) + 1,
            'product' => $request->product,
            'qty' => (int) $request->qty,
            'customer' => $request->customer,
            'placed_at' => now()->format('Y-m-d H:i:s'),
        ];
        session(['shop_orders' => $orders]);

        return view('Allroute.post', [
            'method' => $request->method(),
            'order' => end($orders),
            'orders' => $orders,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT — Replace the WHOLE user profile
    | Real life: Edit Profile page where every field is sent together
    |--------------------------------------------------------------------------
    */
    public function putExample(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:50',
        ]);

        $profile = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
        ];
        session(['shop_profile' => $profile]);

        return view('Allroute.put', [
            'method' => $request->method(),
            'profile' => $profile,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PATCH — Update ONLY one setting (email notifications on/off)
    | Real life: toggle "dark mode", change password only, mark order "shipped"
    |--------------------------------------------------------------------------
    */
    public function patchExample(Request $request)
    {
        $request->validate([
            'notify_email' => 'required|in:0,1',
        ]);

        $notify = $request->notify_email === '1';
        session(['shop_notify_email' => $notify]);

        return view('Allroute.patch', [
            'method' => $request->method(),
            'notifyEmail' => $notify,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE — Remove an item from the shopping cart
    | Real life: delete account, remove comment, cancel booking
    |--------------------------------------------------------------------------
    */
    public function deleteExample(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $cart = session('shop_cart', []);
        $removed = null;
        $cart = array_values(array_filter($cart, function ($item) use ($request, &$removed) {
            if ((int) $item['id'] === (int) $request->id) {
                $removed = $item;
                return false;
            }
            return true;
        }));
        session(['shop_cart' => $cart]);

        return view('Allroute.delete', [
            'method' => $request->method(),
            'removed' => $removed,
            'cart' => $cart,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MATCH (GET + POST) — Contact form on ONE URL
    | Real life: /contact shows form (GET), same /contact saves message (POST)
    |--------------------------------------------------------------------------
    */
    public function matchExample(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('Allroute.match', [
                'method' => $request->method(),
                'submitted' => false,
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:80',
            'email' => 'required|email',
            'message' => 'required|string|max:500',
        ]);

        return view('Allroute.match', [
            'method' => $request->method(),
            'submitted' => true,
            'contact' => [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ANY — Payment / WhatsApp style webhook (accepts any HTTP method)
    | Real life: Stripe/JazzCash callback, Slack bot, health check endpoint
    |--------------------------------------------------------------------------
    */
    public function anyExample(Request $request)
    {
        $log = session('shop_webhook_log', []);
        $entry = [
            'method' => $request->method(),
            'event' => $request->input('event', 'ping'),
            'payload' => $request->except(['_token', '_method']),
            'received_at' => now()->format('Y-m-d H:i:s'),
        ];
        $log[] = $entry;
        // keep last 5 only
        $log = array_slice($log, -5);
        session(['shop_webhook_log' => $log]);

        return view('Allroute.any', [
            'method' => $request->method(),
            'entry' => $entry,
            'webhookLog' => $log,
        ]);
    }
}
