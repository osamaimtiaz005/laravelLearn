<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Updated</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
    </style>
</head>
<body>
    <p><span class="tag">Route::put · {{ $method }}</span></p>
    <h1>Full profile replaced</h1>
    <p>PUT replaced the entire profile object. All fields were required and saved together.</p>

    <div class="box">
        <p><strong>Name:</strong> {{ $profile['name'] }}</p>
        <p><strong>Email:</strong> {{ $profile['email'] }}</p>
        <p><strong>Phone:</strong> {{ $profile['phone'] }}</p>
        <p><strong>City:</strong> {{ $profile['city'] }}</p>
    </div>

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
