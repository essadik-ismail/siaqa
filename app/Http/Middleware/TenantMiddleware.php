<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Skip for main domain (landing page, admin panel)
        if ($host === config('app.domain')) {
            return $next($request);
        }

        $tenant = Tenant::where('domain', $host)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        if (!$tenant->is_active) {
            abort(403, 'Tenant is inactive');
        }

        // Set tenant context
        app()->instance('tenant', $tenant);
        
        // Switch database connection for tenant
        $this->switchTenantDatabase($tenant);
        
        return $next($request);
    }

    protected function switchTenantDatabase(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        // Purge the connection to force a new connection with the new database
        DB::purge('tenant');
        DB::reconnect('tenant');
    }
} 