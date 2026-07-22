<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Many-to-One</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 820px; margin: 2rem auto; padding: 0 1rem; line-height: 1.55; color: #222; }
        h1 { margin-bottom: .25rem; }
        .sub { color: #666; margin-bottom: 1.5rem; }
        section { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.2rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 .6rem; font-size: 1.15rem; }
        h3 { margin: 1rem 0 .4rem; font-size: 1rem; }
        code, pre { background: #f4f4f4; border-radius: 4px; }
        code { padding: 0 .25rem; }
        pre { padding: .75rem; overflow-x: auto; }
        .row { padding: .6rem 0; border-top: 1px solid #eee; }
        .row:first-of-type { border-top: 0; padding-top: 0; }
        .muted { color: #666; font-size: .85rem; }
        .bad { color: #a33; }
        .good { color: #1a7a3c; font-weight: 600; }
        .box { background: #f8f9fb; border: 1px solid #c9d3e3; border-radius: 8px; padding: 1rem; margin: .75rem 0; }
        .box.alt { background: #f8faf8; border-color: #cfe3d4; }
        .pass { background: #eef2f8; border: 1px solid #c9d3e3; border-radius: 8px; padding: .9rem 1rem; }
        ul, ol { margin: .4rem 0 .8rem; padding-left: 1.2rem; }
        table { width: 100%; border-collapse: collapse; font-size: .95rem; }
        th, td { border: 1px solid #ddd; padding: .5rem .6rem; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

    <h1>Many-to-One</h1>
    <p class="sub">Many Orders → one User. Same link as one-to-many, starting from the <strong>child</strong>.</p>

    <section>
        <h2>What is many-to-one?</h2>
        <p><strong>Many</strong> child rows point to <strong>one</strong> parent row.</p>
        <pre>Order #101 ─┐
Order #102 ─┼──► User Ali
Order #103 ─┘

English:  many orders belong to one user
Laravel:  $order->user   // belongsTo
SQL FK:   orders.user_id → users.id</pre>
        <p class="good">Many-to-one = the belongsTo side of your User ↔ Order relationship.</p>
    </section>

    <section>
        <h2>Many-to-one vs One-to-many</h2>
        <p>They are <strong>the same relationship</strong>, different direction / name:</p>
        <table>
            <tr>
                <th></th>
                <th>One-to-many</th>
                <th>Many-to-one</th>
            </tr>
            <tr>
                <td>Start from</td>
                <td>User (parent)</td>
                <td>Order (child)</td>
            </tr>
            <tr>
                <td>Method</td>
                <td><code>hasMany</code></td>
                <td><code>belongsTo</code></td>
            </tr>
            <tr>
                <td>Code</td>
                <td><code>$user-&gt;orders</code></td>
                <td><code>$order-&gt;user</code></td>
            </tr>
            <tr>
                <td>Returns</td>
                <td>many Orders</td>
                <td>one User</td>
            </tr>
            <tr>
                <td>Demo page</td>
                <td><a href="{{ route('one-to-many.index') }}">/one-to-many</a></td>
                <td><a href="{{ route('many-to-one.index') }}">/many-to-one</a></td>
            </tr>
        </table>
        <pre>// ONE-TO-MANY (parent → children)
$user->orders;   // hasMany

// MANY-TO-ONE (child → parent)
$order->user;    // belongsTo</pre>
    </section>

    <section>
        <h2>Two-Way Test (many-to-one focus)</h2>
        <div class="box">
            <h3>Many → One (this page)</h3>
            <p><strong>Question:</strong> Do many orders belong to one user, and does each order have only one user?</p>
            <p class="good">True</p>
            <pre>$order->user;           // one User
$order->user_id;        // one foreign key value
// Order cannot have many users at once</pre>
            @if ($sampleOrder && $sampleOrder->user)
                <p>
                    Live: Order <strong>#{{ $sampleOrder->id }}</strong>
                    → only <strong>{{ $sampleOrder->user->name }}</strong>
                </p>
            @endif
        </div>
        <div class="box alt">
            <h3>One → Many (pair side)</h3>
            <p>Same user can still have many orders — that is why we also call the pair <strong>one-to-many</strong>.</p>
            @if ($sampleUser)
                <p>
                    Live: User <strong>{{ $sampleUser->name }}</strong>
                    has <strong>{{ $sampleUser->orders_count }}</strong> order(s).
                </p>
            @endif
        </div>
        <div class="pass">
            Both True → same strict 1-to-many / many-to-one pair (two names, one design).
        </div>
    </section>

    <section>
        <h2>Useful belongsTo methods</h2>
        <pre>// Read the one parent
$order->user;
$order->user->email;

// Eager load (avoid N+1)
Order::with('user')->get();

// All children of one parent
Order::where('user_id', 1)->get();
Order::whereBelongsTo($user)->get();

// Change the parent (re-assign owner)
$order->user()->associate($otherUser);
$order->save();

// Clear parent (only if user_id is nullable)
// $order->user()->dissociate();
// $order->save();</pre>
        <p class="muted">Try associate live below (pick real ids from the lists).</p>
    </section>

    <section>
        <h2>How to test many-to-one</h2>
        <ol>
            <li>Open <a href="{{ route('many-to-one.order', $sampleOrder->id ?? 1) }}">/many-to-one/order/{{ $sampleOrder->id ?? 1 }}</a> — see <strong>one</strong> user.</li>
            <li>Open <a href="{{ route('many-to-one.for-user', $sampleUser->id ?? 1) }}">/many-to-one/for-user/{{ $sampleUser->id ?? 1 }}</a> — see <strong>many</strong> orders for that one user.</li>
            <li>List all: <a href="{{ route('many-to-one.orders') }}">/many-to-one/orders</a></li>
            <li>Optional: <a href="{{ route('many-to-one.associate', [$sampleOrder->id ?? 1, $sampleUser->id ?? 1]) }}">associate</a> — move an order to another user (still one user_id).</li>
        </ol>
    </section>

    <section>
        <h2>All many-to-one routes</h2>
        <p><a href="{{ route('many-to-one.index') }}">/many-to-one</a> — this page</p>
        <p><a href="{{ route('many-to-one.orders') }}">/many-to-one/orders</a> — all orders + user</p>
        <p><a href="{{ route('many-to-one.order', 1) }}">/many-to-one/order/1</a> — one order → one user</p>
        <p><a href="{{ route('many-to-one.for-user', 1) }}">/many-to-one/for-user/1</a> — orders for user 1</p>
        <p><a href="{{ route('many-to-one.associate', [1, 2]) }}">/many-to-one/associate/1/2</a> — order 1 → user 2</p>
        <p class="muted">Also see <a href="{{ route('one-to-many.index') }}">/one-to-many</a> for the parent-side lesson.</p>
    </section>

    <section>
        <h2>Live — each order belongs to one user</h2>
        @forelse ($orders as $order)
            <div class="row">
                <div class="muted">Order #{{ $order->id }}</div>
                <strong>{{ $order->name }}</strong> — {{ $order->price }}
                @if ($order->user)
                    <div>belongsTo → <strong>{{ $order->user->name }}</strong> (user #{{ $order->user->id }})</div>
                @else
                    <div class="bad">No user</div>
                @endif
            </div>
        @empty
            <p>No orders. Create some from <a href="{{ route('one-to-many.create', 1) }}">/one-to-many/create/1</a></p>
        @endforelse
    </section>

    <section>
        <h2>Live — one user can own many orders</h2>
        <p class="muted">Same data, parent counts (proves the pair is also one-to-many).</p>
        @forelse ($users as $user)
            @if ($user->orders_count > 0)
                <div class="row">
                    <strong>{{ $user->name }}</strong> — {{ $user->orders_count }} order(s)
                </div>
            @endif
        @empty
            <p>No users.</p>
        @endforelse
    </section>

</body>
</html>
