<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        form { display: grid; gap: 0.6rem; margin-top: 1rem; }
        input, textarea, button { padding: 0.45rem 0.6rem; font: inherit; }
        button { background: #222; color: #fff; border: 0; cursor: pointer; width: fit-content; }
        .ok { color: #0a7a2f; }
    </style>
</head>
<body>
    <p><span class="tag">Route::match · {{ $method }}</span></p>
    <h1>Contact us</h1>
    <p>Same URL <code>/allroute/match</code> handles both:</p>
    <ul>
        <li><strong>GET</strong> — show this form</li>
        <li><strong>POST</strong> — save / thank the visitor</li>
    </ul>

    @if($submitted)
        <div class="box ok">
            <p><strong>Message received. Thank you!</strong></p>
            <p>From: {{ $contact['name'] }} ({{ $contact['email'] }})</p>
            <p>{{ $contact['message'] }}</p>
        </div>
        <p><a href="{{ route('allroute.match') }}">Send another message</a></p>
    @else
        <form action="{{ route('allroute.match') }}" method="post">
            @csrf
            <input type="text" name="name" placeholder="Your name" required>
            <input type="email" name="email" placeholder="Your email" required>
            <textarea name="message" placeholder="How can we help?" required></textarea>
            <button type="submit">Send message</button>
        </form>
    @endif

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
