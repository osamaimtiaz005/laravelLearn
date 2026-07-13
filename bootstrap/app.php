<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\globalMid;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * To add global middleware in Laravel using the bootstrap/app.php file:
         *
         * 1. The `$middleware` parameter is an instance of the Middleware configuration class for the Application.
         * 2. To register a global middleware (runs for every HTTP request), use the `append()` method on `$middleware`.
         *    Example: $middleware->append(YourMiddlewareClass::class);
         * 3. You can chain multiple `append()` calls or pass arrays if you want to register more than one middleware.
         * 4. Global middleware are executed in the order you append them.
         * 5. Make sure your middleware class is properly imported at the top of this file with a `use` statement.
         * 6. Example below adds a custom middleware called 'globalMid' that is run for every route in the application.
         */
  
        $middleware->append(globalMid::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
