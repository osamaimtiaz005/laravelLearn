<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // If URL has {locale}, save it to session
        $routeLocale = $request->route('locale');

        if ($routeLocale && in_array($routeLocale, ['en', 'ur'], true)) {
            session(['locale' => $routeLocale]);
        }

        // Always apply locale from session (fallback to config)
        $locale = session('locale', config('app.locale'));

        if (in_array($locale, ['en', 'ur'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
