<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission, string ...$permissions): Response
    {
        if (!$request->user()) {
            return redirect()->route('landing')->with('error', 'Please login to access this page.');
        }

        $requiredPermissions = array_merge([$permission], $permissions);

        if (!$request->user()->hasAnyPermission($requiredPermissions)) {
            abort(403, 'Unauthorized action. Insufficient permissions.');
        }

        return $next($request);
    }
}
