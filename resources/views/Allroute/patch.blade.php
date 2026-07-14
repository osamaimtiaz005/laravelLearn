<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setting Updated</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
    </style>
</head>
<body>
    <p><span class="tag">Route::patch · {{ $method }}</span></p>
    <h1>One setting updated</h1>
    <p>PATCH changed only email notifications. Name, phone, and city were not touched.</p>

    <div class="box">
        <p>Email notifications are now: <strong>{{ $notifyEmail ? 'ON' : 'OFF' }}</strong></p>
    </div>

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
