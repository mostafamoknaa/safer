<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        $response->headers->set('Vary', 'Cookie');

        // Anti-Proxy Caching headers (Nginx/LiteSpeed/Cloudflare)
        $response->headers->set('X-Accel-Expires', '0');
        $response->headers->set('Cache-Control', 'private, max-age=0, no-cache');
        $response->headers->set('Surrogate-Control', 'no-store');

        return $response;
    }
}
