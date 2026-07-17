{{--
ABOUT PAGE — same layout, different content (real-world: many pages, one shell)
--}}

@extends('layout-demo.layouts.app')

@section('title', 'About')

@section('subtitle', 'Same layout file — different @section content')

@section('content')
    <div class="card">
        <h1>About this demo</h1>
        <p>
            In real apps (blogs, dashboards, shops) you almost never duplicate
            <code>&lt;html&gt;</code>, nav, and footer on every page.
            You keep one layout and swap only the middle content.
        </p>
    </div>

    <div class="card">
        <h2>Key Blade directives</h2>
        <ul class="list">
            <li><code>@@extends('layout-demo.layouts.app')</code> — use the master layout</li>
            <li><code>@@section('content') ... @@endsection</code> — page body</li>
            <li><code>@@yield('content')</code> — layout prints that body</li>
            <li><code>@@push('styles') / @@stack('styles')</code> — page CSS</li>
            <li><code>@@push('scripts') / @@stack('scripts')</code> — page JS</li>
            <li><code>@@hasSection('subtitle')</code> — show block only if child defined it</li>
        </ul>
    </div>

    <div class="explain">
        Notice the header clock still runs — shared <code>app.js</code> is loaded by the layout
        on every page, including About.
    </div>
@endsection
