{{--
================================================================================
HOME PAGE (child view)
File: resources/views/layout-demo/pages/home.blade.php

@extends → wrap this page inside the master layout
@section → fill layout holes (@yield)
@push    → add EXTRA css/js only for THIS page
================================================================================
--}}

@extends('layout-demo.layouts.app')

@section('title', 'Home')

@section('subtitle', 'Shared layout + shared CSS/JS + page-only extras')

{{-- Page-only CSS (goes into layout @stack('styles')) --}}
@push('styles')
<style>
    /* Only loaded on Home — does not affect About/Dashboard */
    .home-hero {
        background: linear-gradient(135deg, #ccfbf1, #e0f2fe);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
    <div class="home-hero">
        <h1>Welcome to Layout Demo</h1>
        <p class="muted">
            This page uses <code>@@extends('layout-demo.layouts.app')</code>.
            Nav, footer, global CSS, and global JS come from the layout automatically.
        </p>
    </div>

    <div class="card">
        <h2>What you are seeing</h2>
        <ul class="list">
            <li><strong>Layout</strong> → header, nav, footer, flash area</li>
            <li><strong>Shared CSS</strong> → <code>public/css/layout-demo/app.css</code></li>
            <li><strong>Shared JS</strong> → live clock in the header (from <code>app.js</code>)</li>
            <li><strong>Page CSS</strong> → green/blue hero box (pushed with <code>@@push('styles')</code>)</li>
            <li><strong>Page JS</strong> → alert button below (pushed with <code>@@push('scripts')</code>)</li>
        </ul>
    </div>

    <div class="card">
        <h2>Try shared JS</h2>
        <p>
            Likes: <strong id="like-count">0</strong>
            <button type="button" class="btn" id="like-btn">Like</button>
        </p>
        <p class="muted">The Like button is wired in <code>public/js/layout-demo/app.js</code>.</p>
    </div>

    <div class="explain">
        <strong>Flow:</strong>
        Route → <code>LayoutDemoController@home</code> →
        view <code>layout-demo.pages.home</code> →
        extends <code>layout-demo.layouts.app</code> →
        browser gets one full HTML page with CSS + JS.
    </div>
@endsection

{{-- Page-only JS (goes into layout @stack('scripts')) --}}
@push('scripts')
<script>
    // Only runs on Home page
    console.log('[layout-demo] home page script loaded');
</script>
@endpush
