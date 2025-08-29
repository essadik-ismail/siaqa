<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Vehicule;
use App\Models\Contrat;
use App\Models\User;
use App\Models\Agency;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_reservations' => Reservation::count(),
            'total_vehicles' => Vehicule::count(),
            'total_revenue' => Reservation::sum('prix_total'),
            'estimated_revenue' => Vehicule::sum('prix_location_jour') * 30, // Rough estimate
            'actual_revenue' => Reservation::where('statut', 'confirmee')->sum('prix_total'),
            'current_month_revenue' => Reservation::whereMonth('created_at', now()->month)->sum('prix_total'),
            'current_clients_revenue' => Reservation::whereDate('created_at', today())->sum('prix_total'),
        ];

        // Add admin statistics if user is admin
        if (auth()->user()->isAdmin()) {
            $stats = array_merge($stats, [
                'total_users' => User::count(),
                'total_agencies' => Agency::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'active_users' => User::where('is_active', true)->count(),
                'active_agencies' => Agency::where('is_active', true)->count(),
                'total_tenants' => \App\Models\Tenant::count(),
            ]);
        }

        return view('dashboard', compact('stats'));
    }
}

