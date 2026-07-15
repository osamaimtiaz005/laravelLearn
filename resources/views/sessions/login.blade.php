{{--
  sessions/login.blade.php

  YOUR form idea kept:
    POST → route('sessions.store')
    @csrf → security token Laravel requires for POST
    name="email" / name="password" → become $request->email / $request->password

  After submit, SessionsController@store runs Session::put(...) then redirects to profile.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Login</title>
    <style>
        body { font-family: Georgia, serif; max-width: 480px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        form { display: grid; gap: 0.55rem; margin-top: 1rem; }
        input, button { padding: 0.45rem 0.6rem; font: inherit; }
        button { background: #0f4c5c; color: #fff; border: 0; cursor: pointer; width: fit-content; }
        .ok { color: #0a7a2f; } .err { color: #a11818; }
        .errors { color: #a11818; font-size: 0.92rem; }
    </style>
</head>
<body>
    <h2>Login — save data in session</h2>
    <p>Password is validated (your rules) but <strong>not stored</strong> in session. Only email + logged_in flag are saved.</p>

    {{-- Flash from logout / guard redirects --}}
    @if(session('success'))
        <p class="ok">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="err">{{ session('error') }}</p>
    @endif

    {{-- Validation errors from $request->validate(...) --}}
    @if($errors->any())
        <ul class="errors">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('sessions.store') }}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Your name (optional)" value="{{ old('name') }}">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password (8–20 letters/numbers)" required>
        <button type="submit">Login (Session::put)</button>
    </form>

    <p><a href="{{ route('sessions.index') }}">← All session demos</a></p>
</body>
</html>
