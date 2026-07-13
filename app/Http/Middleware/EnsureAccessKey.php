<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Conditional middleware — runs ONLY on routes where you attach it.
 *
 * Register alias in bootstrap/app.php:
 *   $middleware->alias(['access.key' => EnsureAccessKey::class]);
 *
 * Apply on specific routes:
 *   Route::get('/edit', 'edit')->middleware('access.key');
 *
 * Test URLs:
 *   /edit                          → 403 Access denied
 *   /edit?access_key=learn123       → allowed
 *   /middleware-demo/protected      → 403 without key
 *   /middleware-demo/protected?access_key=learn123 → allowed
 */
class EnsureAccessKey
{
  private const VALID_KEY = 'learn123';

  public function handle(Request $request, Closure $next): Response
  {
    // Read key from query string (?access_key=...) OR custom header (X-Access-Key)
    $providedKey = $request->query('access_key') ?? $request->header('X-Access-Key');

    // CONDITION: block request when key is missing or wrong
    if ($providedKey !== self::VALID_KEY) {
      return response()->view('middleware.denied', [
        'message' => 'Access denied. Use ?access_key=learn123 in the URL.',
        'path' => $request->path(),
      ], 403);
    }

    // Condition passed — continue to controller / next middleware
    return $next($request);
  }
}
