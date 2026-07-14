<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.6; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
    </style>
</head>
<body>
    <p><span class="tag">Route::view</span></p>
    <h1>About First Learning Shop</h1>
    <p>
        This page is returned by <code>Route::view()</code> — Laravel loads the Blade file
        directly. No controller runs. Perfect for static pages: About, Privacy, Terms, FAQ.
    </p>
    <p><strong>Company:</strong> First Learning Electronics<br>
       <strong>City:</strong> Lahore<br>
       <strong>Email:</strong> hello@firstlearning.test</p>
    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
