<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One-to-Many — Two-Way Test</title>
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
        .box { background: #f8faf8; border: 1px solid #cfe3d4; border-radius: 8px; padding: 1rem; margin: .75rem 0; }
        .box.back { background: #f8f9fb; border-color: #c9d3e3; }
        .pass { background: #e8f6ec; border: 1px solid #b7dfc3; border-radius: 8px; padding: .9rem 1rem; }
        ul { margin: .4rem 0 .8rem; padding-left: 1.2rem; }
    </style>
</head>
<body>

    <h1>One-to-Many</h1>
    <p class="sub">User → Orders. Prove it with the <strong>Two-Way Test</strong>.</p>

    {{-- ============================================================
         TWO-WAY TEST (main teaching block)
         ============================================================ --}}
    <section>
        <h2>The Two-Way Test</h2>
        <p>Look at the relationship in <strong>both</strong> directions. If both answers are True, it is a strict <strong>1-to-many</strong>.</p>

        <div class="box">
            <h3>Forward test (User → Orders)</h3>
            <p><strong>Question:</strong> Can one user place many orders over time?</p>
            <p class="good">True</p>
            <p>One customer can place Order #1 today, Order #2 tomorrow, Order #3 next week.</p>
            <pre>// FORWARD in code (hasMany)
$user = User::with('orders')->find(1);
$user->orders;           // Collection: many orders
$user->orders->count();  // 0, 1, 2, 3...</pre>
            @if ($sampleUser)
                <p class="muted">Live check:</p>
                <p>
                    User <strong>{{ $sampleUser->name }}</strong> (#{{ $sampleUser->id }})
                    has <strong>{{ $sampleUser->orders_count }}</strong> order(s).
                </p>
                @if ($sampleUser->orders_count > 1)
                    <p class="good">Forward PASS — one user, many orders.</p>
                @elseif ($sampleUser->orders_count === 1)
                    <p class="good">Forward PASS — one order so far; create more to see “many”.</p>
                    <p><a href="{{ route('one-to-many.create', $sampleUser->id) }}">Create another order for this user</a></p>
                @else
                    <p><a href="{{ route('one-to-many.create', $sampleUser->id) }}">Create first order</a> to prove Forward.</p>
                @endif
            @endif
        </div>

        <div class="box back">
            <h3>Backward test (Order → User)</h3>
            <p><strong>Question:</strong> Does a specific order (e.g. Invoice #10025) belong to only one specific user?</p>
            <p class="good">True</p>
            <p>That order cannot belong to multiple users. It has one <code>user_id</code>.</p>
            <pre>// BACKWARD in code (belongsTo)
$order = Order::with('user')->find(1);
$order->user;       // one User model (not a list)
$order->user_id;    // single foreign key value</pre>
            @if ($sampleOrder && $sampleOrder->user)
                <p class="muted">Live check:</p>
                <p>
                    Order <strong>#{{ $sampleOrder->id }}</strong> ({{ $sampleOrder->name }})
                    belongs only to
                    <strong>{{ $sampleOrder->user->name }}</strong> (user #{{ $sampleOrder->user->id }}).
                </p>
                <p class="good">Backward PASS — one order → one user.</p>
            @elseif ($sampleOrder)
                <p class="bad">Backward FAIL — order has no user (broken FK).</p>
            @else
                <p class="muted">No orders yet — create one first.</p>
            @endif
        </div>

        <div class="pass">
            <strong>Result:</strong> Forward True + Backward True →
            <span class="good">strict 1-to-many relationship</span>.
            <pre>Forward:  One user can place many orders over time. (True)
Backward: A specific order belongs to only one user.
          It cannot belong to multiple users. (True)

Because it passes this test, it is a strict 1-to-many relationship.</pre>
        </div>
    </section>

    <section>
        <h2>How to test it yourself</h2>
        <ol>
            <li>
                <strong>Forward:</strong> open
                <a href="{{ route('one-to-many.user', $sampleUser->id ?? 1) }}">/one-to-many/user/{{ $sampleUser->id ?? 1 }}</a>
                — see many <code>orders</code> under one user.
            </li>
            <li>
                Click
                <a href="{{ route('one-to-many.create', $sampleUser->id ?? 1) }}">/one-to-many/create/{{ $sampleUser->id ?? 1 }}</a>
                a few times — same user, more orders (Forward stays True).
            </li>
            <li>
                <strong>Backward:</strong> open
                <a href="{{ route('one-to-many.order', $sampleOrder->id ?? 1) }}">/one-to-many/order/{{ $sampleOrder->id ?? 1 }}</a>
                — see only one <code>user</code> on that order.
            </li>
            <li>
                Full lists:
                <a href="{{ route('one-to-many.users') }}">/users</a> (forward) ·
                <a href="{{ route('one-to-many.orders') }}">/orders</a> (backward)
            </li>
        </ol>
    </section>

    <section>
        <h2>What FAILS the Two-Way Test</h2>
        <p class="bad">❌ User ↔ Product (shopping / “bought items”)</p>
        <ul>
            <li>Forward: one user buys many products — looks like 1-to-many…</li>
            <li>Backward: one product is bought by many users — NOT “only one user”</li>
            <li>Result: <strong>many-to-many</strong> (needs pivot / order items later)</li>
        </ul>
        <p class="good">✅ User → Orders passes both sides → one-to-many</p>
    </section>

    <section>
        <h2>All demo routes</h2>
        <p><a href="{{ route('one-to-many.index') }}">/one-to-many</a> — this page</p>
        <p><a href="{{ route('one-to-many.users') }}">/one-to-many/users</a> — FORWARD JSON</p>
        <p><a href="{{ route('one-to-many.orders') }}">/one-to-many/orders</a> — BACKWARD JSON</p>
        <p><a href="{{ route('one-to-many.user', 1) }}">/one-to-many/user/1</a> — forward for one user</p>
        <p><a href="{{ route('one-to-many.order', 1) }}">/one-to-many/order/1</a> — backward for one order</p>
        <p><a href="{{ route('one-to-many.create', 1) }}">/one-to-many/create/1</a> — add order (prove forward)</p>
        <p><a href="{{ route('one-to-many.count', 1) }}">/one-to-many/count/1</a> — count / has / whereHas</p>
    </section>

    {{-- FORWARD live list --}}
    <section>
        <h2>Forward view — Users + their many orders</h2>
        <p class="muted">Code: <code>$user-&gt;orders</code> (hasMany)</p>
        @forelse ($users as $user)
            <div class="row">
                <div class="muted">User #{{ $user->id }} · {{ $user->orders_count }} order(s)</div>
                <strong>{{ $user->name }}</strong>
                @forelse ($user->orders as $order)
                    <div>— Order #{{ $order->id }}: {{ $order->name }} ({{ $order->price }})</div>
                @empty
                    <div class="bad">No orders yet</div>
                @endforelse
            </div>
        @empty
            <p>No users.</p>
        @endforelse
    </section>

    {{-- BACKWARD live list --}}
    <section>
        <h2>Backward view — Each order → only one user</h2>
        <p class="muted">Code: <code>$order-&gt;user</code> (belongsTo)</p>
        @forelse ($orders as $order)
            <div class="row">
                <div class="muted">Order #{{ $order->id }}</div>
                <strong>{{ $order->name }}</strong> — {{ $order->price }}
                @if ($order->user)
                    <div>Belongs only to: <strong>{{ $order->user->name }}</strong> (user #{{ $order->user->id }})</div>
                @else
                    <div class="bad">No user — Backward would fail</div>
                @endif
            </div>
        @empty
            <p>No orders. Try <a href="{{ route('one-to-many.create', 1) }}">/one-to-many/create/1</a></p>
        @endforelse
    </section>

</body>
</html>
