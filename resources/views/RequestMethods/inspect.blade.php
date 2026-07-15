<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Inspection</title>
    <style>
        body { font-family: Georgia, serif; max-width: 820px; margin: 2rem auto; padding: 0 1rem; line-height: 1.45; color: #222; }
        .tag { background: #1a3a5c; color: #fff; padding: 0.15rem 0.45rem; font-size: 0.75rem; text-transform: uppercase; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; font-size: 0.92rem; }
        th, td { border: 1px solid #ccc; padding: 0.45rem 0.55rem; vertical-align: top; text-align: left; }
        th { background: #f4f4f4; width: 38%; }
        pre { margin: 0; white-space: pre-wrap; word-break: break-word; font-family: Consolas, monospace; font-size: 0.85rem; }
        .box { border: 1px solid #ccc; padding: 0.85rem; margin: 1rem 0; background: #fafafa; }
        a { color: #1a3a5c; }
    </style>
</head>
<body>
    <p><span class="tag">{{ $method }}</span> {{ $routeLabel }}</p>
    <h1>Request class dump</h1>
    <p>These values came from the current <code>Illuminate\Http\Request</code> object injected into the controller.</p>

    @if($uploaded)
        <div class="box">
            <strong>Uploaded file (from <code>file('photo')</code>)</strong>
            <pre>{{ json_encode($uploaded, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif

    <table>
        <tr>
            <th>Request method</th>
            <th>Result</th>
        </tr>
        @foreach($demo as $label => $value)
            <tr>
                <td><code>$request->{{ $label }}</code></td>
                <td>
                    @if(is_array($value))
                        <pre>{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    @elseif(is_null($value))
                        <em>null</em>
                    @else
                        {{ $value }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    <p><a href="{{ route('requestMethods.index') }}">← Back to Request methods</a> · <a href="{{ route('allroute.index') }}">Allroute shop demos</a></p>
</body>
</html>
