{{--
    KEYWORDS USED ON THIS PAGE (quick map — see learn/layout glossary for layout flow)

    @extends('learn.layout')
    — Declares the parent Blade file (resources/views/learn/layout.blade.php).
    — The child view is merged into that layout; this line should be near the top.

    @section('title', '…')
    — One-line section: defines "title" for the layout’s @yield('title').

    @section('content') … @endsection
    — Block section: all HTML/Blade here fills the layout’s @yield('content').

    @php … @endphp
    — Inline PHP block in Blade (use sparingly; prefer passing data from route/controller).

    @if / @else / @endif
    — Blade conditionals; compile to plain PHP if/else.

    {{ $var }}
    — Echo with HTML escaping (calls e()).

    ??  (null coalescing operator, PHP)
    — $a ?? $b means “use $a if it exists and is not null, else $b”.

    @csrf
    — Inserts a hidden _token field + is validated by VerifyCsrfToken middleware on POST.

    @push('head') … @endpush (below)
    — Pushes HTML into the layout’s @stack('head') (order: multiple pushes append).

    Interview: What does {{ $var }} do vs {!! $var !!}?
    A: {{ }} runs through e() (HTML-escaped). {!! !!} outputs raw HTML — only for trusted content.

    Interview: What is CSRF and how does Laravel protect forms?
    A: Cross-Site Request Forgery; session token in forms; middleware verifies on state-changing verbs.
--}}
@extends('learn.layout')

@section('title', 'Blade basics — learning')

{{-- Page-only head fragment: merged into layout’s @stack('head'). --}}
@push('head')
    <style>
        /* Example only: this block was injected via the push/stack directives on the layout. */
        .learn-page-blade-basics h1 { border-left: 4px solid #2563eb; padding-left: .5rem; }
    </style>
@endpush

@section('content')
    <div class="learn-page-blade-basics">
    <h1>Blade basics</h1>
    {{-- In visible text, @@ prints a single @ so Blade does not run directives. --}}
    <p>This page extends <code>learn.layout</code> using <code>@@extends</code> and <code>@@section</code>.</p>

    <div class="card">
        <h2>Escaped output (safe default)</h2>
        {{-- Interview: Why escape by default? A: Prevents XSS when showing DB or user text in HTML. --}}
        {{-- @php / @endphp: inline PHP in the compiled view; prefer passing variables from the route. --}}
        @php
            $unsafe = '<script>alert("xss")</script>Hello';
        @endphp
        <p>Raw string in PHP: contains a script tag.</p>
        {{-- @{{ ... }}: Blade prints literal {{ so you can document syntax on a Blade page. --}}
        <p><strong>With <code>@{{ $unsafe }}</code> (shown as text here):</strong> {{ $unsafe }}</p>
        <p>The script is shown as text, not executed — that is the point of escaping.</p>
    </div>

    <div class="card">
        <h2>Conditionals</h2>
        {{-- Interview: combine if with isset? Use PHP isset() or Blade isset / endisset pair. --}}
        @if(($user['role'] ?? '') === 'student')
            {{-- ?? : if $user['role'] missing or null, compare against '' instead of erroring. --}}
            <p>Role check: <strong>{{ $user['name'] ?? 'Guest' }}</strong> is a student (example of <code>@@if</code>).</p>
        @else
            <p>Not a student in this demo dataset.</p>
        @endif
    </div>

    <div class="card">
        <h2>Form snippet (CSRF)</h2>
        {{-- Demo form: action="#" — no real handler; @csrf still shows the token field in HTML source. --}}
        <form action="#" method="post">
            @csrf
            <label>Demo field <input type="text" name="demo" value=""></label>
            <button type="button">Read-only demo</button>
        </form>
    </div>
    </div>
@endsection
