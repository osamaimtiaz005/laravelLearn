{{--
================================================================================
LAYOUT DEMO — Master layout (real-world pattern)
File: resources/views/layout-demo/layouts/app.blade.php
================================================================================

FOLDER STRUCTURE (this lesson):
  resources/views/layout-demo/
    layouts/app.blade.php     ← THIS FILE (HTML shell: head, nav, footer)
    pages/home.blade.php      ← child pages @extends this layout
    pages/about.blade.php
    pages/dashboard.blade.php ← dynamic data from controller
  public/css/layout-demo/app.css
  public/js/layout-demo/app.js

HOW IT WORKS:
  1) Controller: return view('layout-demo.pages.home', $data);
  2) Child starts with: @extends('layout-demo.layouts.app')
  3) Child fills sections: @section('title') / @section('content')
  4) Optional page-only CSS/JS: @push('styles') / @push('scripts')
  5) Layout prints them with @yield and @stack

REAL-WORLD RULE:
  Put SHARED chrome (nav, footer, global CSS/JS) in the layout.
  Put PAGE-ONLY stuff in the child via @section / @push.
================================================================================
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Child can override with @section('title', 'My page') --}}
    <title>@yield('title', 'Layout Demo') — FirstLearning</title>

    {{-- SHARED CSS (every page that extends this layout) --}}
    <link rel="stylesheet" href="{{ asset('css/layout-demo/app.css') }}">

    {{--
        @stack('styles') — page-specific CSS pushed from child views.
        Example in a child:
          @push('styles')
              <style>.hero { color: red; }</style>
              <link rel="stylesheet" href="{{ asset('css/layout-demo/home.css') }}">
          @endpush
    --}}
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('layout-demo.home') }}">Layout Demo</a>

        <nav class="nav" aria-label="Main">
            {{--
                request()->routeIs('name') — true if current route name matches.
                Real-world: highlight the active menu item.
            --}}
            <a href="{{ route('layout-demo.home') }}"
               class="{{ request()->routeIs('layout-demo.home') ? 'is-active' : '' }}">Home</a>

            <a href="{{ route('layout-demo.about') }}"
               class="{{ request()->routeIs('layout-demo.about') ? 'is-active' : '' }}">About</a>

            <a href="{{ route('layout-demo.dashboard') }}"
               class="{{ request()->routeIs('layout-demo.dashboard') ? 'is-active' : '' }}">Dashboard</a>
        </nav>

        {{-- Shared JS updates this clock (see public/js/layout-demo/app.js) --}}
        <span class="muted" id="live-clock">--:--:--</span>
    </header>

    <main class="site-main">
        {{--
            Flash messages (session) — real-world after form redirect:
              return redirect()->route(...)->with('success', 'Saved!');
            Then session('success') is available for one request.
        --}}
        @if (session('success'))
            <div class="flash flash-success">
                {{ session('success') }}
                <button type="button" data-dismiss-flash style="float:right;border:0;background:transparent;cursor:pointer;">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="flash flash-error">
                {{ session('error') }}
                <button type="button" data-dismiss-flash style="float:right;border:0;background:transparent;cursor:pointer;">✕</button>
            </div>
        @endif

        {{-- Optional subtitle from child: @section('subtitle') ... @endsection --}}
        @hasSection('subtitle')
            <p class="muted">@yield('subtitle')</p>
        @endif

        {{-- MAIN PAGE BODY from child @section('content') --}}
        @yield('content')
    </main>

    <footer class="site-footer">
        {{--
            Dynamic year — Blade can run PHP expressions.
            Real-world footers often show year, app name, version.
        --}}
        &copy; {{ date('Y') }} FirstLearning — Layout / CSS / JS demo
        |
        Locale: {{ app()->getLocale() }}
    </footer>

    {{-- SHARED JS (defer = after HTML parse) --}}
    <script src="{{ asset('js/layout-demo/app.js') }}" defer></script>

    {{--
        @stack('scripts') — page-specific JS from child @push('scripts')
        Put scripts at the BOTTOM so DOM exists before they run.
    --}}
    @stack('scripts')
</body>
</html>
