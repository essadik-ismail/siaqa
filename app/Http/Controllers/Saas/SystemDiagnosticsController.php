<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Agence;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemDiagnosticsController extends Controller
{
    /**
     * Display system diagnostics dashboard
     */
    public function index()
    {
        // Only super admins can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Access denied. Super Administrator privileges required.');
        }

        $diagnostics = $this->runSystemDiagnostics();
        
        return view('saas.system-diagnostics', compact('diagnostics'));
    }

    /**
     * Run comprehensive system diagnostics
     */
    private function runSystemDiagnostics()
    {
        return [
            'timestamp' => now(),
            'system_health' => $this->checkSystemHealth(),
            'tenant_statistics' => $this->getTenantStatistics(),
            'performance_metrics' => $this->getPerformanceMetrics(),
            'security_checks' => $this->runSecurityChecks(),
        ];
    }

    /**
     * Check overall system health
     */
    private function checkSystemHealth()
    {
        $health = [
            'status' => 'healthy',
            'checks' => []
        ];

        // Check database connectivity
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = ['status' => 'ok', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            $health['checks']['database'] = ['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()];
            $health['status'] = 'critical';
        }

        // Check cache system
        try {
            Cache::put('health_check', 'ok', 60);
            $cacheStatus = Cache::get('health_check');
            if ($cacheStatus === 'ok') {
                $health['checks']['cache'] = ['status' => 'ok', 'message' => 'Cache system working'];
            } else {
                $health['checks']['cache'] = ['status' => 'warning', 'message' => 'Cache system not responding'];
                $health['status'] = 'warning';
            }
        } catch (\Exception $e) {
            $health['checks']['cache'] = ['status' => 'error', 'message' => 'Cache system error: ' . $e->getMessage()];
            $health['status'] = 'critical';
        }

        return $health;
    }

    /**
     * Get tenant statistics
     */
    private function getTenantStatistics()
    {
        return [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->count(),
            'inactive_tenants' => Tenant::where('is_active', false)->count(),
            'trial_tenants' => Tenant::where('trial_ends_at', '>', now())->count(),
            'expired_trials' => Tenant::where('trial_ends_at', '<', now())->count(),
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        return [
            'total_users' => User::count(),
            'total_agencies' => Agence::count(),
            'total_reservations' => Reservation::count(),
            'total_clients' => Client::count(),
            'total_vehicles' => Vehicule::count(),
            'active_reservations' => Reservation::whereIn('statut', ['en_attente', 'confirmee'])->count(),
        ];
    }

    /**
     * Run security checks
     */
    private function runSecurityChecks()
    {
        $securityChecks = [
            'status' => 'secure',
            'checks' => []
        ];

        // Check for super admin accounts
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->count();

        $securityChecks['checks']['super_admin_count'] = [
            'status' => $superAdmins > 0 ? 'ok' : 'warning',
            'message' => "Found {$superAdmins} super admin accounts"
        ];

        return $securityChecks;
    }

    /**
     * Clear system cache (super admin only)
     */
    public function clearCache()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Access denied. Super Administrator privileges required.');
        }

        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            
            return redirect()->back()->with('success', 'System cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}
