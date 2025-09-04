<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('landing')->with('error', 'Please login to access this page.');
        }

        if (!$request->user()->isAdmin()) {
            abort(403, 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
