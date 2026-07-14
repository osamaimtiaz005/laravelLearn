<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webhook Received</title>
    <style>
        body { font-family: Georgia, serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .tag { background: #222; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        pre { background: #f5f5f5; padding: 0.75rem; overflow: auto; }
        table { border-collapse: collapse; width: 100%; font-size: 0.9rem; }
        th, td { border: 1px solid #ccc; padding: 0.35rem 0.5rem; text-align: left; }
    </style>
</head>
<body>
    <p><span class="tag">Route::any · {{ $method }}</span></p>
    <h1>Payment webhook received</h1>
    <p>
        <code>Route::any()</code> accepts GET, POST, PUT, PATCH, DELETE on one URL.
        Payment gateways often call your callback with different methods.
    </p>

    <div class="box">
        <p><strong>Latest hit</strong></p>
        <p>Method: {{ $entry['method'] }}</p>
        <p>Event: {{ $entry['event'] }}</p>
        <p>Time: {{ $entry['received_at'] }}</p>
        <pre>{{ json_encode($entry['payload'], JSON_PRETTY_PRINT) }}</pre>
    </div>

    <h2>Recent webhook log (session)</h2>
    <table>
        <tr><th>Time</th><th>Method</th><th>Event</th></tr>
        @foreach(array_reverse($webhookLog) as $row)
            <tr>
                <td>{{ $row['received_at'] }}</td>
                <td>{{ $row['method'] }}</td>
                <td>{{ $row['event'] }}</td>
            </tr>
        @endforeach
    </table>

    <p><a href="{{ route('allroute.index') }}">← Back to examples</a></p>
</body>
</html>
