<?php

use App\Http\Middleware\countryCheck;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\globalMid;
use App\Http\Middleware\EnsureAccessKey;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * GLOBAL middleware (append) → runs on EVERY request.
         * ROUTE middleware (alias)   → runs ONLY where you use ->middleware('alias')
         *
         * To restrict globalMid to specific routes:
         *   1. Do NOT use append() for globalMid
         *   2. Register it as an alias below
         *   3. Add ->middleware('global.mid') only on the routes you want
         */

        // $middleware->append(globalMid::class); // removed — was running on every URL

        // Apply saved locale from session on every web request
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->alias([
            'access.key' => EnsureAccessKey::class,
            'global.mid' => globalMid::class,
            'locale' => SetLocale::class,
        ]);
        //appendToGroup is used to add Mutiple middlewares to a group alias 
        $middleware->appendToGroup('groupCheck', [EnsureAccessKey::class, countryCheck::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
