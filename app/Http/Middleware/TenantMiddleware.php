<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Skip for localhost/development environments
        if (in_array($host, ['localhost', '127.0.0.1', '::1']) || 
            app()->environment('local', 'development')) {
            // Set a default tenant for development
            $tenant = Tenant::where('is_active', true)->first();
            if ($tenant) {
                app()->instance('tenant', $tenant);
            }
            return $next($request);
        }
        
        // Skip for main domain (landing page, admin panel)
        if ($host === config('app.domain')) {
            return $next($request);
        }

        $tenant = Tenant::where('website', $host)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        if (!$tenant->is_active) {
            abort(403, 'Tenant is inactive');
        }

        // Set tenant context
        app()->instance('tenant', $tenant);
        
        return $next($request);
    }
} 