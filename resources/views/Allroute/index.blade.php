<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Route Examples</title>
    <style>
        body { font-family: Georgia, "Times New Roman", serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; color: #222; }
        h1 { margin-bottom: 0.25rem; }
        .intro { color: #555; margin-bottom: 1.5rem; }
        section { margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid #ddd; }
        h2 { font-size: 1.15rem; margin: 0 0 0.25rem; }
        .tag { display: inline-block; font-size: 0.75rem; letter-spacing: 0.04em; text-transform: uppercase; background: #222; color: #fff; padding: 0.15rem 0.45rem; margin-right: 0.35rem; }
        .life { color: #555; font-size: 0.95rem; margin: 0.35rem 0 0.75rem; }
        code { background: #f3f3f3; padding: 0.1rem 0.35rem; }
        form { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; margin-top: 0.4rem; }
        input, select, button, textarea { padding: 0.4rem 0.55rem; font: inherit; }
        textarea { width: 100%; min-height: 70px; }
        a.btn, button { display: inline-block; padding: 0.4rem 0.8rem; background: #222; color: #fff; text-decoration: none; border: 0; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; margin-top: 0.5rem; font-size: 0.95rem; }
        th, td { border: 1px solid #ccc; padding: 0.35rem 0.5rem; text-align: left; }
        .ok { color: #0a7a2f; }
        .muted { color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    <h1>Real-life route examples</h1>
    <p class="intro">
        Mini shop / account demos. Data is stored in <strong>session</strong> (no database).
        PUT / PATCH / DELETE use <code>@@method</code> because browsers only send GET and POST.
        Also try <a href="{{ route('requestMethods.index') }}">Request class methods</a> (same verbs + <code>$request</code> dump).
    </p>

    {{-- 1. VIEW — static About page --}}
    <section>
        <h2><span class="tag">view</span> About Us (static page)</h2>
        <p class="life">Real life: Privacy Policy, Terms, FAQ — page that never needs PHP logic.</p>
        <a class="btn" href="{{ route('allroute.view') }}">Open About Us</a>
    </section>

    {{-- 2. GET — product catalog --}}
    <section>
        <h2><span class="tag">get</span> Browse product catalog</h2>
        <p class="life">Real life: Amazon product list, news feed, search results — read only.</p>
        <a class="btn" href="{{ route('allroute.get') }}">View catalog</a>
    </section>

    {{-- 3. POST — place order --}}
    <section>
        <h2><span class="tag">post</span> Place a new order</h2>
        <p class="life">Real life: checkout, signup, “create ticket” — creates new data.</p>
        <form action="{{ route('allroute.post') }}" method="post">
            @csrf
            <input type="text" name="customer" placeholder="Customer name" required>
            <input type="text" name="product" placeholder="Product name" value="Wireless Mouse" required>
            <input type="number" name="qty" min="1" max="20" value="1" required>
            <button type="submit">Place order</button>
        </form>
        @if(count($orders))
            <p class="muted ok">Orders in session: {{ count($orders) }}</p>
        @endif
    </section>

    {{-- 4. PUT — replace full profile --}}
    <section>
        <h2><span class="tag">put</span> Replace full profile</h2>
        <p class="life">Real life: Edit Profile — every field is sent and saved together.</p>
        <form action="{{ route('allroute.put') }}" method="post">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ $profile['name'] }}" required>
            <input type="email" name="email" value="{{ $profile['email'] }}" required>
            <input type="text" name="phone" value="{{ $profile['phone'] }}" required>
            <input type="text" name="city" value="{{ $profile['city'] }}" required>
            <button type="submit">Save full profile</button>
        </form>
    </section>

    {{-- 5. PATCH — one setting only --}}
    <section>
        <h2><span class="tag">patch</span> Toggle email notifications only</h2>
        <p class="life">Real life: change one switch (dark mode, “mark as shipped”) — not the whole record.</p>
        <p class="muted">Current: email notifications are <strong>{{ $notifyEmail ? 'ON' : 'OFF' }}</strong></p>
        <form action="{{ route('allroute.patch') }}" method="post">
            @csrf
            @method('PATCH')
            <select name="notify_email">
                <option value="1" @selected($notifyEmail)>Turn ON</option>
                <option value="0" @selected(!$notifyEmail)>Turn OFF</option>
            </select>
            <button type="submit">Update setting</button>
        </form>
    </section>

    {{-- 6. DELETE — remove from cart --}}
    <section>
        <h2><span class="tag">delete</span> Remove item from cart</h2>
        <p class="life">Real life: delete comment, cancel booking, remove uploaded file.</p>
        @if(count($cart))
            <table>
                <tr><th>ID</th><th>Item</th><th>Price</th><th></th></tr>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>Rs {{ $item['price'] }}</td>
                        <td>
                            <form action="{{ route('allroute.delete') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="muted">Cart is empty.</p>
            <form action="{{ route('allroute.resetCart') }}" method="post">
                @csrf
                <button type="submit">Reset demo cart</button>
            </form>
        @endif
    </section>

    {{-- 7. MATCH — contact form same URL --}}
    <section>
        <h2><span class="tag">match</span> Contact form (GET show + POST send)</h2>
        <p class="life">Real life: <code>/contact</code> opens the form; submit uses the same URL.</p>
        <a class="btn" href="{{ route('allroute.match') }}">Open contact page</a>
    </section>

    {{-- 8. ANY — payment webhook --}}
    <section>
        <h2><span class="tag">any</span> Payment webhook simulator</h2>
        <p class="life">Real life: Stripe / JazzCash / WhatsApp API callback — provider may use any method.</p>
        <a class="btn" href="{{ route('allroute.any') }}">Ping webhook (GET)</a>
        <form action="{{ route('allroute.any') }}" method="post">
            @csrf
            <input type="text" name="event" value="payment.success" required>
            <input type="text" name="order_id" value="ORD-1001" required>
            <input type="number" name="amount" value="2500" required>
            <button type="submit">Send webhook (POST)</button>
        </form>
        <form action="{{ route('allroute.any') }}" method="post">
            @csrf
            @method('PUT')
            <input type="text" name="event" value="payment.updated" required>
            <input type="text" name="status" value="refunded" required>
            <button type="submit">Send webhook (PUT)</button>
        </form>
        @if(count($webhookLog))
            <p class="muted">Last webhook hits in session: {{ count($webhookLog) }}</p>
        @endif
    </section>
</body>
</html>
