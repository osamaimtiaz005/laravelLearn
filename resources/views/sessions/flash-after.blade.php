<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flash after</title>
    <style>
        body { font-family: Georgia, serif; max-width: 680px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        .ok { color: #0a7a2f; } .muted { color: #555; }
        a.btn { display: inline-block; padding: 0.45rem 0.8rem; background: #0f4c5c; color: #fff; text-decoration: none; }
        code { background: #f3f3f3; padding: 0.1rem 0.35rem; }
    </style>
</head>
<body>
    <h1>Flash workshop (step 2 — after redirect)</h1>

    @if($note)
        <p class="ok">{{ $note }}</p>
    @endif

    <div class="box">
        <p><strong>flash_demo:</strong> {{ $flashDemo ?: '(empty — forgotten)' }}</p>
        <p><strong>flash_extra:</strong> {{ $flashExtra ?: '(empty — forgotten)' }}</p>
    </div>

    <p class="muted">
        Expected:
        <br>• <code>keep(['flash_demo'])</code> → demo filled, extra empty
        <br>• <code>reflash()</code> → both filled
        <br>• no keep/reflash → both empty
    </p>

    <a class="btn" href="{{ route('sessions.flashStart') }}">Run workshop again</a>
    <a href="{{ route('sessions.index') }}">Hub</a>
    ·
    <a href="{{ route('sessions.theory') }}">Theory</a>
</body>
</html>
