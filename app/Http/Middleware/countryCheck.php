<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;
class countryCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->country != "pakistan")
        {
           die("you are not allowed to access this page out of Pakistan ");
        }
        return $next($request);
    }
}
?>