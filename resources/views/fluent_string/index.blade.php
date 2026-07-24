{{--
  Fluent Strings demo page.
  Shown by: FluentStringController@index  (GET /fluent-string)
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluent Strings — Laravel</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #38bdf8;
            --ok: #4ade80;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: ui-monospace, Consolas, monospace;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 100%);
            color: var(--text);
            min-height: 100vh;
            padding: 2rem 1.25rem 3rem;
            line-height: 1.5;
        }
        h1 { font-size: 1.75rem; margin: 0 0 0.35rem; color: var(--accent); }
        .lead { color: var(--muted); max-width: 52rem; margin-bottom: 1.5rem; }
        .box {
            background: var(--card);
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            max-width: 52rem;
        }
        .box h2 { margin: 0 0 0.75rem; font-size: 1.05rem; color: var(--ok); }
        .row { display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; margin: 0.35rem 0; }
        .key { color: var(--accent); min-width: 8rem; }
        .val { color: #f8fafc; word-break: break-all; }
        .problem { color: #fbbf24; }
        .solve { color: var(--ok); }
        code { background: #0f172a; padding: 0.1rem 0.35rem; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { text-align: left; padding: 0.45rem 0.5rem; border-bottom: 1px solid #334155; vertical-align: top; }
        th { color: var(--muted); font-weight: 600; }
        a { color: var(--accent); }
    </style>
</head>
<body>
    <h1>Fluent Strings (Stringable)</h1>
    <p class="lead">
        Chain string methods left → right with <code>Str::of()</code> or <code>str()</code>.
        Solves nested PHP string functions that read inside-out.
    </p>

    <div class="box">
        <h2>Why / what problem</h2>
        <p class="problem">Plain PHP (hard to read):</p>
        <p><code>strtolower(str_replace(' ', '-', trim($title)))</code></p>
        <p class="solve">Fluent (left → right):</p>
        <p><code>Str::of($title)->trim()->lower()->replace(' ', '-')</code></p>
        <div class="row"><span class="key">raw</span><span class="val">"{{ $raw }}"</span></div>
        <div class="row"><span class="key">slug (chain)</span><span class="val">{{ $slugFromTitle }}</span></div>
        <div class="row"><span class="key">str()->slug()</span><span class="val">{{ $withHelper }}</span></div>
    </div>

    <div class="box">
        <h2>Useful method results</h2>
        <table>
            <thead>
                <tr><th>Method / key</th><th>Result</th></tr>
            </thead>
            <tbody>
                @foreach ($examples as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>
                            @if (is_bool($value))
                                {{ $value ? 'true' : 'false' }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p><a href="/">← Home</a> · See README section <strong>AA. Fluent Strings</strong></p>
</body>
</html>
