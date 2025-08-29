<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $requiredRoles = array_merge([$role], $roles);

        if (!$request->user()->hasAnyRole($requiredRoles)) {
            abort(403, 'Unauthorized action. Insufficient role permissions.');
        }

        return $next($request);
    }
}
