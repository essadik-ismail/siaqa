<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Vehicule;
use App\Models\Contrat;
use App\Models\User;
use App\Models\Agence;
use App\Models\Agency;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        
        // Base query for tenant-specific data
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        // Calculate real statistics
        $stats = [
            'total_clients' => Client::where($tenantQuery)->count(),
            'total_reservations' => Reservation::where($tenantQuery)->count(),
            'total_vehicles' => Vehicule::where($tenantQuery)->count(),
            'total_revenue' => Reservation::where($tenantQuery)->sum('prix_total') ?? 0,
            'estimated_revenue' => $this->calculateEstimatedRevenue($tenantId),
            'actual_revenue' => Reservation::where($tenantQuery)->where('statut', 'confirmee')->sum('prix_total') ?? 0,
            'current_month_revenue' => Reservation::where($tenantQuery)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('prix_total') ?? 0,
            'current_clients_revenue' => Reservation::where($tenantQuery)
                ->whereDate('created_at', today())
                ->sum('prix_total') ?? 0,
        ];

        // Calculate utilization percentages
        $totalVehicles = $stats['total_vehicles'];
        $activeReservations = Reservation::where($tenantQuery)
            ->whereIn('statut', ['en_attente', 'confirmee'])
            ->count();
        
        $stats['estimated_utilization'] = $totalVehicles > 0 ? min(100, round(($stats['estimated_revenue'] / max($stats['actual_revenue'], 1)) * 100)) : 0;
        $stats['actual_utilization'] = $totalVehicles > 0 ? min(100, round(($activeReservations / $totalVehicles) * 100)) : 0;

        // Add admin statistics if user is super admin
        if ($user->isSuperAdmin()) {
            $stats = array_merge($stats, [
                'total_users' => User::count(),
                'total_agencies' => Agence::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'active_users' => User::where('is_active', true)->count(),
                'active_agencies' => Agence::where('is_active', true)->count(),
                'total_tenants' => Tenant::count(),
            ]);
        }

        // Get chart data
        $chartData = $this->getChartData($tenantId);
        
        // Get recent data for tabs (using paginate for consistency with AJAX calls)
        $recentVehicles = Vehicule::where($tenantQuery)
            ->with('marque')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $recentReservations = Reservation::where($tenantQuery)
            ->with(['client', 'vehicule.marque'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $recentContracts = Contrat::where($tenantQuery)
            ->with(['client', 'vehicule.marque'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard', compact('stats', 'chartData', 'recentVehicles', 'recentReservations', 'recentContracts'));
    }

    /**
     * Get data for a specific tab via AJAX
     */
    public function getTabData(Request $request)
    {
        $tab = $request->get('tab', 'vehicles');
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        switch ($tab) {
            case 'reservations':
                $data = Reservation::where($tenantQuery)
                    ->with(['client', 'vehicule.marque'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                    
            case 'contracts':
                $data = Contrat::where($tenantQuery)
                    ->with(['client', 'vehicule.marque'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                    
            case 'vehicles':
            default:
                $data = Vehicule::where($tenantQuery)
                    ->with('marque')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'html' => view('dashboard.partials.' . $tab, compact('data'))->render()
        ]);
    }

    /**
     * Calculate estimated revenue based on vehicle rental prices
     */
    private function calculateEstimatedRevenue($tenantId)
    {
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        $totalVehicles = Vehicule::where($tenantQuery)->count();
        $avgDailyRate = Vehicule::where($tenantQuery)->avg('prix_location_jour') ?? 0;
        
        // Estimate based on 30 days and 70% utilization
        return $totalVehicles * $avgDailyRate * 30 * 0.7;
    }

    /**
     * Get chart data for revenue and reservations
     */
    private function getChartData($tenantId)
    {
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        // Get last 6 months of data
        $months = [];
        $revenueData = [];
        $reservationData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $revenue = Reservation::where($tenantQuery)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('statut', 'confirmee')
                ->sum('prix_total') ?? 0;
            $revenueData[] = $revenue;
            
            $reservations = Reservation::where($tenantQuery)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $reservationData[] = $reservations;
        }

        return [
            'months' => $months,
            'revenue' => $revenueData,
            'reservations' => $reservationData
        ];
    }
}

