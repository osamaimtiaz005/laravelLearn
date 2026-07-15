{{--
  sessions/profile.blade.php

  YOUR original:
    Session::has('success') / Session::get('success')
    Session::get('email')
    link to logout

  Also shows more session keys set in store().
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Profile</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .ok { color: #0a7a2f; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        pre { background: #f5f5f5; padding: 0.75rem; overflow: auto; font-size: 0.82rem; }
        a.btn { display: inline-block; padding: 0.4rem 0.75rem; background: #0f4c5c; color: #fff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Profile page (reads session)</h2>

    {{-- Your pattern: Session facade in Blade (needs facade alias; session() helper also works) --}}
    @if(Session::has('success'))
        <p class="ok">{{ Session::get('success') }}</p>
    @endif

    <div class="box">
        <p>Welcome <strong>{{ Session::get('name', 'Guest') }}</strong></p>
        <p>Email from session: <strong>{{ Session::get('email') }}</strong></p>
        <p>Logged in at: {{ $loginAt }}</p>
        <p>Login count this browser: {{ $loginCount }}</p>
        <p>Theme preference: {{ $theme }}</p>
    </div>

    <p>
        <a class="btn" href="{{ route('sessions.logout') }}">Logout (Session::flush)</a>
        <a href="{{ route('sessions.index') }}">Session demos hub</a>
    </p>

    <h3>Session::all() (your print_r idea)</h3>
    <pre>{{ json_encode($allSession, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
</body>
</html>
