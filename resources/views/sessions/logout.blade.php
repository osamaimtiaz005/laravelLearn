{{-- Rarely used: logout() redirects to login with flash, so this view is a fallback --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logged out</title>
</head>
<body>
    <h2>Logout page</h2>
    <p>You have been logged out. Session data was cleared with <code>Session::flush()</code>.</p>
    <a href="{{ route('sessions.login') }}">Login again</a>
    ·
    <a href="{{ route('sessions.index') }}">Session demos</a>
</body>
</html>
