<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class globalMid
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        echo "Hi this is global middleware";
        return $next($request);

    }
}
/*
Below, I will explain all the code above line by line and word by word, including classes, methods, parameters, and statements:

<?php
// Opening PHP tag to indicate the file contains PHP code.

namespace App\Http\Middleware;
// 'namespace' declares that this file belongs to the 'App\Http\Middleware' namespace,
// which helps in organizing code and avoiding class name conflicts.

use Closure;
// 'use' imports the 'Closure' class so it can be used without a fully qualified name.
// 'Closure' represents an anonymous function (callback) in PHP.

use Illuminate\Http\Request;
// Imports the 'Request' class from the 'Illuminate\Http' namespace.
// 'Request' represents an HTTP request in Laravel.

use Symfony\Component\HttpFoundation\Response;
// Imports the 'Response' class from Symfony's HTTP Foundation component.
// 'Response' represents HTTP responses in Laravel.

class globalMid
// 'class' keyword defines a new PHP class named 'globalMid' (the custom middleware).

{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
  //  public function handle(Request $request, Closure $next): Response
    // Defines a public method named 'handle' inside 'globalMid'.
    // - 'public' visibility allows it to be called from outside the class.
    // - 'function' is used to define a function/method.
    // - 'handle' is the method name required by Laravel middleware.
    // - 'Request $request' is a parameter: an instance of the 'Request' class representing the incoming HTTP request.
    // - 'Closure $next' is a parameter: a callback function that, when called, forwards the request to the next middleware.
    // - ': Response' specifies that the function returns a 'Response' object.

   // {
       // echo "Hi this is global middleware";
        // 'echo' outputs the string "Hi this is global middleware" to the response.
        // This will display the message whenever this middleware runs.

       // return $next($request);
        // 'return' sends back the result of calling '$next' with '$request' as an argument.
        // This passes the request deeper into the application (next middleware or controller) and returns its response.

    //}
    // End of 'handle' method.
//}
// End of 'globalMid' class.
//*/