<?php

use App\Http\Middleware\countryCheck;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\globalMid;
use App\Http\Middleware\EnsureAccessKey;

/*
|--------------------------------------------------------------------------
| Application Bootstrap (Laravel 12)
|--------------------------------------------------------------------------
| This file configures and boots your Laravel application.
|
| Key application configuration steps:
|   - Application::configure: sets up core app behavior and base path
|   - withRouting():    registers routes for web, api, console, and health endpoints
|   - withMiddleware(): configures global and route/group middleware
|   - withExceptions(): registers exception handling behavior
|
| Route groups:
|   web:      Blade views, forms, sessions   → routes/web.php
|   api:      JSON APIs (auto /api prefix)   → routes/api.php
|   commands: Artisan CLI commands           → routes/console.php
|   health:   /up health check endpoint
|
| Having "web" and "api" in the same project is common. Examples:
|   GET /api-learning   (web hub page)
|   GET /api/hello      (JSON from routes/api.php)
*/
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
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
        /*
        |------------------------------------------------------------------
        | API 404 / errors as JSON (not HTML)
        |------------------------------------------------------------------
        | Route Model Binding IS fine for APIs.
        | When {student} is missing, Laravel throws NotFound (404).
        |
        | Format depends on the Accept header by default:
        |   Accept: application/json     → JSON 404  (good)
        |   Accept: star/star (Postman)  → HTML page (looks wrong for APIs)
        |
        | This forces JSON for every /api/... URL, so binding 404s stay JSON
        | even if Postman sends a wildcard Accept header.
        */
        $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
