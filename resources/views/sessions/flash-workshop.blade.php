<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flash workshop</title>
    <style>
        body { font-family: Georgia, serif; max-width: 680px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        a.btn, button { display: inline-block; padding: 0.45rem 0.8rem; background: #0f4c5c; color: #fff; text-decoration: none; border: 0; cursor: pointer; margin: 0.25rem 0.25rem 0.25rem 0; font: inherit; }
        code { background: #f3f3f3; padding: 0.1rem 0.35rem; }
        .muted { color: #555; }
    </style>
</head>
<body>
    <h1>Flash workshop (step 1)</h1>
    <p class="muted">
        You arrived here via <code>Session::flash()</code> + redirect.
        Laravel will delete flash keys after THIS page finishes — unless you call
        <code>keep()</code> or <code>reflash()</code> before the next redirect.
    </p>

    <div class="box">
        <p><strong>flash_demo:</strong> {{ $flashDemo ?: '(empty)' }}</p>
        <p><strong>flash_extra:</strong> {{ $flashExtra ?: '(empty)' }}</p>
    </div>

    <p>Choose what happens on the next page:</p>

    <form action="{{ route('sessions.flashKeep') }}" method="post">
        @csrf
        <button type="submit">Session::keep(['flash_demo']) only</button>
    </form>

    <form action="{{ route('sessions.flashReflash') }}" method="post">
        @csrf
        <button type="submit">Session::reflash() — keep ALL flash</button>
    </form>

    <form action="{{ route('sessions.flashSkipKeep') }}" method="post">
        @csrf
        <button type="submit">Continue WITHOUT keep/reflash</button>
    </form>

    <p><a href="{{ route('sessions.index') }}">← Hub</a></p>
</body>
</html>
