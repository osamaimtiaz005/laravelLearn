<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session theory</title>
    <style>
        body { font-family: Georgia, "Times New Roman", serif; max-width: 780px; margin: 2rem auto; padding: 0 1rem 3rem; line-height: 1.55; color: #1c1a16; }
        h1, h2 { margin-top: 1.6rem; }
        code, pre { background: #f3f0e8; }
        code { padding: 0.1rem 0.35rem; }
        pre { padding: 0.85rem; overflow: auto; font-size: 0.88rem; }
        .box { border: 1px solid #cfc6b6; padding: 0.85rem 1rem; margin: 1rem 0; }
        table { border-collapse: collapse; width: 100%; margin: 0.75rem 0; font-size: 0.95rem; }
        th, td { border: 1px solid #cfc6b6; padding: 0.4rem 0.55rem; text-align: left; vertical-align: top; }
        a { color: #0f4c5c; }
    </style>
</head>
<body>
    <h1>Session theory (learning notes)</h1>
    <p><a href="{{ route('sessions.index') }}">← Back to demos</a></p>

    <div class="box">
        <strong>This project right now</strong><br>
        Driver: <code>{{ $sessionDriver }}</code><br>
        Idle lifetime: <code>{{ $sessionLifetimeMinutes }}</code> minutes<br>
        Expire when browser closes: <code>{{ $expireOnClose ? 'true' : 'false' }}</code>
    </div>

    <h2>1) Auto forget after some time</h2>
    <table>
        <tr>
            <th>Level</th>
            <th>How</th>
            <th>Meaning</th>
        </tr>
        <tr>
            <td>Whole session</td>
            <td><code>config/session.php</code> → <code>lifetime</code><br>env: <code>SESSION_LIFETIME=120</code></td>
            <td>If user is idle that many minutes, Laravel expires the session (cookie still there but server data gone / rebuilt).</td>
        </tr>
        <tr>
            <td>One key only</td>
            <td>Store <code>otp_expires_at</code>, check with <code>now()-&gt;gt(...)</code>, then <code>Session::forget(...)</code></td>
            <td>OTP / coupon / temporary link style. Demo on hub: 20-second OTP.</td>
        </tr>
        <tr>
            <td>Manual</td>
            <td><code>Session::forget('key')</code> or <code>flush()</code></td>
            <td>Logout or clear cart without waiting for timer.</td>
        </tr>
    </table>

    <h2>2) Flash session — what / why / functions</h2>
    <p>
        Flash = session data meant for a short UI message after redirect
        (“Login successful”, “Item added”). Normal <code>put()</code> stays until you delete it;
        flash auto-deletes so old banners do not stick forever.
    </p>

    <table>
        <tr><th>Function</th><th>What it does</th><th>When to use</th></tr>
        <tr>
            <td><code>Session::flash('k', $v)</code><br>or <code>-&gt;with('k', $v)</code></td>
            <td>Available on the <strong>next</strong> request, then removed.</td>
            <td>After POST → redirect → show success once.</td>
        </tr>
        <tr>
            <td><code>Session::now('k', $v)</code></td>
            <td>Available on <strong>this same</strong> request only.</td>
            <td>Same request returns a view (no redirect).</td>
        </tr>
        <tr>
            <td><code>Session::keep(['k'])</code></td>
            <td>Keep listed flash keys for <strong>one more</strong> request.</td>
            <td>Wizard/multi-step: message must survive another redirect.</td>
        </tr>
        <tr>
            <td><code>Session::reflash()</code></td>
            <td>Keep <strong>all</strong> current flash keys for one more request.</td>
            <td>Same as keep, but you do not list keys.</td>
        </tr>
    </table>

    <p>Try it live: <a href="{{ route('sessions.flashStart') }}">Flash keep / reflash workshop</a></p>

<pre>// typical success flash (your login store method)
return redirect()->route('sessions.profile')
    ->with('success', 'Login successful');

// extend flash for another hop
Session::keep(['success']);
Session::reflash();
</pre>

    <h2>3) Sessions in APIs / microservices</h2>
    <p>
        Browser websites love cookie sessions. APIs and microservices usually do <strong>not</strong>,
        because mobile apps / other servers don’t naturally share Laravel cookies, and
        many services sit behind different domains.
    </p>

    <table>
        <tr><th>Style</th><th>How identity works</th><th>Laravel tools</th></tr>
        <tr>
            <td>Web (your /sessions demos)</td>
            <td>Cookie <code>laravel-session</code> → server session bag</td>
            <td><code>Session::put</code>, web middleware group</td>
        </tr>
        <tr>
            <td>API (SPA / mobile)</td>
            <td>Client sends <code>Authorization: Bearer TOKEN</code> each call</td>
            <td>Laravel Sanctum (or Passport)</td>
        </tr>
        <tr>
            <td>Microservices</td>
            <td>Auth service issues JWT / opaque token; each service validates it (or calls auth introspect). No shared PHP session file required.</td>
            <td>JWT libs, Sanctum, API gateway</td>
        </tr>
    </table>

<pre>// API idea (not cookie session):
// 1) POST /api/login  →  { "token": "1|abc..." }
// 2) Later requests:
//    Header: Authorization: Bearer 1|abc...
// 3) Laravel Auth::user() comes from the token, not Session::get('email')
</pre>

    <div class="box">
        <strong>Rule of thumb</strong><br>
        Website Blade pages → session cookie OK.<br>
        Mobile / public API / many microservices → tokens, not classic sessions.
    </div>

    <h2>4) Why store session in the database?</h2>
    <p>
        Your migration already has a <code>sessions</code> table
        (<code>id</code>, <code>user_id</code>, <code>ip_address</code>, <code>user_agent</code>, <code>payload</code>, <code>last_activity</code>).
        This project default driver is often <code>database</code> in <code>config/session.php</code>.
    </p>

    <table>
        <tr><th>Reason</th><th>Explanation</th></tr>
        <tr>
            <td>Many servers</td>
            <td>Load balancer sends user to server A then B. File sessions on A are invisible to B. DB (or Redis) is shared → login works on every node.</td>
        </tr>
        <tr>
            <td>Survive restart</td>
            <td>Restart PHP/container → local session files may vanish. DB rows stay.</td>
        </tr>
        <tr>
            <td>Admin / security</td>
            <td>You can list a user’s sessions, kick one device, audit IP / user agent.</td>
        </tr>
        <tr>
            <td>Cloud / Docker</td>
            <td>Containers are replaceable; sticky files on one instance are a bad fit.</td>
        </tr>
    </table>

    <p><strong>Drivers compared quickly</strong></p>
    <ul>
        <li><code>file</code> — simple local learning, one machine</li>
        <li><code>database</code> — shared, inspectable (your <code>sessions</code> table)</li>
        <li><code>redis</code> — fast shared store, common in production APIs + queues</li>
        <li><code>array</code> — memory only (tests; gone after request)</li>
    </ul>

<pre>// .env examples
SESSION_DRIVER=database
SESSION_LIFETIME=120

// switch for single-PC learning:
SESSION_DRIVER=file
</pre>

    <p><a href="{{ route('sessions.index') }}">← Practice on the hub</a></p>
</body>
</html>
