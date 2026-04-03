<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
| Route::get      — register a route for HTTP GET (browser address bar, links).
| '/'            — path: site root (nothing after the domain).
| function ()    — closure = code that runs when this URL + method match.
| return view()  — response: render a Blade template from resources/views.
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Named route (custom path + name)
|--------------------------------------------------------------------------
| '/dashboard'   — the URL path users visit (e.g. http://127.0.0.1:8000/dashboard).
| ->name(...)   — "->" calls a method on the Route object Laravel just built.
| 'dashboard'    — route name: use route('dashboard') or redirect()->route('dashboard')
|                  in PHP/Blade so you can rename the URL later without hunting strings.
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return <<<'HTML'
        <h1>Dashboard</h1>
        <p>This route is <strong>named</strong> <code>dashboard</code>.</p>
        <p>In Blade you could link: <code>{{ route('dashboard') }}</code></p>
        <p><a href="/">Back home</a></p>
    HTML;
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Query string: /search?q=your+text&page=2
|--------------------------------------------------------------------------
| The path is only '/search'. Everything after "?" is the query string (not in Route::get).
|
| Symbols in URLs:
|   ?   starts query parameters
|   =   separates name and value (q=laravel)
|   &   separates pairs (q=laravel&page=2)
|
| Request $request — Laravel injects the current HTTP request into the closure.
| $request->query('q')     — read parameter "q"; null if missing.
| $request->query('page', 1) — read "page", or default 1 if absent.
| rawurlencode($q) — safe for putting search text inside a URL (href), unlike e() which is for HTML body.
|--------------------------------------------------------------------------
*/
Route::get('/search', function (Request $request) {
    $q = $request->query('q');
    $page = (int) $request->query('page', 1);

    if ($q === null || $q === '') {
        return <<<'HTML'
            <h1>Search</h1>
            <p>Add a query, e.g. <code>/search?q=laravel&amp;page=1</code></p>
            <p><a href="/">Back home</a> · <a href="/dashboard">Dashboard</a></p>
        HTML;
    }

    $safeQ = e($q);
    $qForUrl = rawurlencode($q);
    $prevPage = max(1, $page - 1);
    $nextPage = $page + 1;

    return <<<HTML
        <h1>Search results</h1>
        <p>Query (<code>q</code>): <strong>{$safeQ}</strong></p>
        <p>Page (<code>page</code>): <strong>{$page}</strong></p>
        <p><a href="/search?q={$qForUrl}&page={$prevPage}">Previous page</a>
           · <a href="/search?q={$qForUrl}&page={$nextPage}">Next page</a></p>
        <p><a href="/">Back home</a> · <a href="/dashboard">Dashboard</a></p>
    HTML;
});

/*
|--------------------------------------------------------------------------
| Path parameter: /hello/Lina  (dynamic segment in the URL, not ?query=)
|--------------------------------------------------------------------------
| {name}     — placeholder: whatever is in the URL becomes $name in the closure.
| e($name)   — escape output for HTML (security when showing user-controlled text).
|--------------------------------------------------------------------------
*/
Route::get('/hello/{name}', function (string $name) {
    return '<h1>Hello, '.e($name).'</h1><p><a href="/">Back home</a></p>';
})->name('hello');
