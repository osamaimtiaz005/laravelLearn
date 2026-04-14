{{--
    KEYWORDS / IDEAS ON THIS PAGE

    @extends, @section — child view wiring into the layout (see layout file for render order).

    {{ url('…') }} — absolute URL helper (uses APP_URL); good for links where you have a path string.

    route('name') — not used here but listed in glossary: resolves URL from route name in routes/web.php.

    config() — reads merged config values (config/*.php + env); mentioned for deploy/interview context.

    Interview: Request lifecycle?
    A: Request → bootstrap → HTTP Kernel + middleware → router → controller/closure → response → middleware out → client.

    Interview: Middleware?
    A: Pipeline filters before/after your action (auth, CSRF, throttling, etc.).

    Interview: Named routes?
    A: route('dashboard') survives path changes better than hard-coded strings.

    Interview: Service container?
    A: Binds and resolves classes; constructor type-hints get implementations automatically.
--}}
@extends('learn.layout')

@section('title', 'Interview checklist — Laravel views')

@section('content')
    <h1>Laravel views &amp; HTTP — quick checklist</h1>
    <p>Use this page as a memory aid; the <strong>layout file</strong> explains how @@yield / @@section compose the final HTML. Source comments live under <code>resources/views/learn/</code>.</p>

    <ol>
        <li><strong>MVC:</strong> Model (data), View (presentation), Controller (coordinates). Routes map URLs to controllers or closures.</li>
        <li><strong>Blade:</strong> Compiled templates; <code>@{{ $variable }}</code> echoes escaped HTML; directives include <code>@@if</code>, <code>@@foreach</code>, <code>@@csrf</code> (written with @@ here so Blade does not run them).</li>
        <li><strong>Layouts:</strong> <code>@@extends</code>, <code>@@section</code>, <code>@@yield</code>, optional <code>@@stack</code> / <code>@@push</code> for scripts or styles.</li>
        <li><strong>Security:</strong> Escape output; validate input; CSRF on state-changing forms; policies/gates for authorization.</li>
        <li><strong>Config:</strong> <code>env()</code> at boot / .env load; <code>config()</code> at runtime from cached config after <code>php artisan config:cache</code> in production.</li>
    </ol>

    <div class="card">
        <h2>Try these URLs on your machine</h2>
        <p>Replace host/port with your app (e.g. <kbd>http://127.0.0.1:8000</kbd>):</p>
        <ul>
            <li><a href="{{ url('/learn/blade-basics') }}">{{ url('/learn/blade-basics') }}</a></li>
            <li><a href="{{ url('/learn/blade-loops-data') }}">{{ url('/learn/blade-loops-data') }}</a></li>
        </ul>
        {{-- url(): Illuminate\Support\Facades\URL::to path helper; wrapped in {{ }} so href is escaped. --}}
        <p><code>url()</code> builds absolute URLs from paths — compare with <code>route()</code> when you assign names in routes.</p>
    </div>
@endsection
