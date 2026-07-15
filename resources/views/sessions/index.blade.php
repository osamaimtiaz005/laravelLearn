<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session demos</title>
    <style>
        :root { --bg: #f7f3ea; --ink: #1c1a16; --line: #cfc6b6; --accent: #0f4c5c; }
        body.dark { --bg: #1c1a16; --ink: #f2ebe0; --line: #3a342c; --accent: #7eb8c9; }
        body { font-family: Georgia, "Times New Roman", serif; max-width: 820px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; background: var(--bg); color: var(--ink); }
        h1, h2 { margin: 0 0 0.4rem; }
        .intro { opacity: 0.85; margin-bottom: 1.25rem; }
        section { margin-bottom: 1.5rem; padding-bottom: 1.1rem; border-bottom: 1px solid var(--line); }
        .tag { display: inline-block; font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase; background: var(--accent); color: #fff; padding: 0.15rem 0.45rem; margin-right: 0.35rem; }
        form { display: flex; flex-wrap: wrap; gap: 0.45rem; align-items: center; margin-top: 0.4rem; }
        input, select, button { padding: 0.4rem 0.55rem; font: inherit; }
        a.btn, button { display: inline-block; padding: 0.4rem 0.8rem; background: var(--accent); color: #fff; text-decoration: none; border: 0; cursor: pointer; margin: 0.15rem 0.15rem 0.15rem 0; }
        .ok { color: #0a7a2f; } body.dark .ok { color: #7dcea0; }
        .err { color: #a11818; } body.dark .err { color: #f5a6a6; }
        table { border-collapse: collapse; width: 100%; margin-top: 0.5rem; font-size: 0.92rem; }
        th, td { border: 1px solid var(--line); padding: 0.35rem 0.5rem; text-align: left; }
        pre { background: rgba(0,0,0,0.06); padding: 0.75rem; overflow: auto; font-size: 0.82rem; }
        code { font-size: 0.9em; }
        .muted { opacity: 0.8; font-size: 0.92rem; }
        .meta { font-size: 0.9rem; padding: 0.6rem 0.75rem; border: 1px dashed var(--line); margin-bottom: 1rem; }
    </style>
</head>
<body class="{{ $theme === 'dark' ? 'dark' : '' }}">
    <h1>Laravel Session — real world demos</h1>
    <p class="intro">
        Your login flow + flash / auto-expire / cart.
        Read theory: <a href="{{ route('sessions.theory') }}">flash · keep · reflash · API · DB sessions</a>
    </p>

    <div class="meta">
        Driver: <code>{{ $sessionDriver }}</code> ·
        Whole-session idle lifetime: <code>{{ $sessionLifetimeMinutes }}</code> minutes
        (<code>config/session.php</code> → <code>SESSION_LIFETIME</code>) ·
        Visits this session: <strong>{{ $visits }}</strong>
    </div>

    @if(session('success'))
        <p class="ok">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="err">{{ session('error') }}</p>
    @endif

    <section>
        <h2><span class="tag">auth</span> Your login example</h2>
        <p class="muted">
            @if($loggedIn)
                Logged in as <strong>{{ $email }}</strong> —
                <a href="{{ route('sessions.profile') }}">Open profile</a> ·
                <a href="{{ route('sessions.logout') }}">Logout (flush)</a>
            @else
                Not logged in —
                <a class="btn" href="{{ route('sessions.login') }}">Go to login</a>
            @endif
        </p>
    </section>

    <section>
        <h2><span class="tag">expire</span> Auto-forget after time (OTP demo)</h2>
        <p class="muted">
            Key-level expiry: store <code>otp_expires_at</code>, then <code>Session::forget()</code> when time is up.
            Status: <strong>{{ $otpStatus }}</strong>
        </p>
        @if($otp)
            <p>Code: <strong>{{ $otp }}</strong> · expires at {{ $otpExpiresAt }}</p>
            <p class="muted">Wait ~20 seconds, refresh this page — code should vanish automatically.</p>
        @endif
        <a class="btn" href="{{ route('sessions.startOtp') }}">Start 20s OTP in session</a>
    </section>

    <section>
        <h2><span class="tag">flash</span> Flash · keep · reflash · now</h2>
        <p class="muted">Flash = temporary session data for one (or a few) requests, then auto-removed.</p>
        <a class="btn" href="{{ route('sessions.flashDemo') }}">Basic flash (with)</a>
        <a class="btn" href="{{ route('sessions.flashNowDemo') }}">Session::now() same request</a>
        <a class="btn" href="{{ route('sessions.flashStart') }}">Workshop: keep / reflash</a>
    </section>

    <section>
        <h2><span class="tag">cart</span> Shopping cart in session</h2>
        <p class="muted">Real life: Daraz/Amazon guest cart — lives until logout/flush or session lifetime.</p>
        <form action="{{ route('sessions.addToCart') }}" method="post">
            @csrf
            <input type="text" name="product" value="Wireless Mouse" required>
            <input type="number" name="price" value="1200" min="1" required>
            <input type="number" name="qty" value="1" min="1" max="20" required>
            <button type="submit">Add to cart</button>
        </form>
        @if(count($cart))
            <table>
                <tr><th>Product</th><th>Price</th><th>Qty</th><th>Added</th></tr>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['product'] }}</td>
                        <td>Rs {{ $item['price'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>{{ $item['added_at'] }}</td>
                    </tr>
                @endforeach
            </table>
            <form action="{{ route('sessions.clearCart') }}" method="post" style="margin-top:0.6rem;">
                @csrf
                <button type="submit">Clear cart only (Session::forget)</button>
            </form>
        @else
            <p class="muted">Cart is empty.</p>
        @endif
        @if($lastProduct)
            <p class="muted">Last product key: <strong>{{ $lastProduct }}</strong>
                <a href="{{ route('sessions.forgetLastProduct') }}">forget this key</a>
            </p>
        @endif
    </section>

    <section>
        <h2><span class="tag">pref</span> Theme preference</h2>
        <form action="{{ route('sessions.saveTheme') }}" method="post">
            @csrf
            <select name="theme">
                <option value="light" @selected($theme === 'light')>Light</option>
                <option value="dark" @selected($theme === 'dark')>Dark</option>
            </select>
            <button type="submit">Save theme</button>
        </form>
    </section>

    <section>
        <h2><span class="tag">dump</span> Session::all() right now</h2>
        <pre>{{ json_encode($allSession, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    </section>
</body>
</html>
