<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->count(),
            'total_users' => User::count(),
            'total_vehicles' => Vehicule::count(),
            'total_reservations' => Reservation::count(),
            'monthly_growth' => $this->getMonthlyGrowth(),
        ];

        $tenantGrowth = $this->getTenantGrowth();
        $userActivity = $this->getUserActivity();
        $revenueTrends = $this->getRevenueTrends();

        return view('saas.analytics.index', compact('stats', 'tenantGrowth', 'userActivity', 'revenueTrends'));
    }

    public function tenants()
    {
        // Get basic tenant counts
        $total = Tenant::count();
        $active = Tenant::where('is_active', true)->count();
        $inactive = Tenant::where('is_active', false)->count();
        
        // Get trial tenants (active tenants with trial_ends_at in the future)
        $trial = Tenant::where('is_active', true)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now())
            ->count();
        
        // Get expired tenants (inactive tenants with trial_ends_at in the past)
        $expired = Tenant::where('is_active', false)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->count();

        $tenantStats = (object) [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'trial' => $trial,
            'expired' => $expired
        ];

        $tenantsByPlan = Tenant::join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
            ->selectRaw('subscriptions.plan_name as subscription_plan, COUNT(*) as count')
            ->groupBy('subscriptions.plan_name')
            ->get();

        $tenantsByMonth = Tenant::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('saas.analytics.tenants', compact('tenantStats', 'tenantsByPlan', 'tenantsByMonth'));
    }

    public function revenue()
    {
        $revenueStats = Reservation::selectRaw('
            SUM(prix_total) as total_revenue,
            COUNT(*) as total_reservations,
            AVG(prix_total) as avg_reservation_value
        ')->first();

        $monthlyRevenue = Reservation::selectRaw('
            MONTH(created_at) as month,
            SUM(prix_total) as revenue,
            COUNT(*) as reservations
        ')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $topPerformingTenants = Tenant::withSum('reservations as total_revenue', 'prix_total')
            ->whereHas('reservations')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return view('saas.analytics.revenue', compact('revenueStats', 'monthlyRevenue', 'topPerformingTenants'));
    }

    private function getMonthlyGrowth()
    {
        $currentMonth = Tenant::whereMonth('created_at', now()->month)->count();
        $lastMonth = Tenant::whereMonth('created_at', now()->subMonth()->month)->count();
        
        if ($lastMonth == 0) return 100;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private function getTenantGrowth()
    {
        return Tenant::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getUserActivity()
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getRevenueTrends()
    {
        return Reservation::selectRaw('DATE(created_at) as date, SUM(prix_total) as revenue')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
