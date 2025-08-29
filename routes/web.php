<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\VidangeController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\RetourContratController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

// Public landing page routes
Route::get('/landing', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::get('/cars', [App\Http\Controllers\LandingController::class, 'cars'])->name('landing.cars');
Route::get('/cars/{vehicule}', [App\Http\Controllers\LandingController::class, 'showCar'])->name('landing.car.show');
Route::post('/reservations/public', [App\Http\Controllers\LandingController::class, 'storeReservation'])->name('landing.reservation.store');
Route::post('/login', [App\Http\Controllers\LandingController::class, 'login'])->name('landing.login');
Route::post('/landing/logout', [App\Http\Controllers\LandingController::class, 'logout'])->name('landing.logout');
Route::get('/register', [App\Http\Controllers\LandingController::class, 'showRegister'])->name('landing.register');
Route::post('/register', [App\Http\Controllers\LandingController::class, 'register'])->name('landing.register.post');

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Generic logout route for dashboard
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Authentication routes - handled by LandingController

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Management Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin Overview
        Route::get('/', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_agencies' => \App\Models\Agency::count(),
                'total_roles' => \App\Models\Role::count(),
                'total_permissions' => \App\Models\Permission::count(),
                'total_tenants' => \App\Models\Tenant::count(),
                'total_revenue' => \App\Models\Reservation::sum('prix_total'),
            ];
            return view('admin.overview', compact('stats'));
        })->name('overview');

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
        Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/{user}/permissions', [\App\Http\Controllers\Admin\UserManagementController::class, 'permissions'])->name('users.permissions');
        Route::post('users/{user}/permissions', [\App\Http\Controllers\Admin\UserManagementController::class, 'updatePermissions'])->name('users.permissions.update');

        // Agency Management
        Route::resource('agencies', \App\Http\Controllers\Admin\AgencyManagementController::class);
        Route::post('agencies/{agency}/toggle-status', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'toggleStatus'])->name('agencies.toggle-status');
        Route::get('agencies/{agency}/users', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'users'])->name('agencies.users');
        Route::get('agencies/{agency}/statistics', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'statistics'])->name('agencies.statistics');

        // Role Management
        Route::resource('roles', \App\Http\Controllers\Admin\RoleManagementController::class);
        Route::get('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'updatePermissions'])->name('roles.permissions.update');
        Route::post('roles/{role}/duplicate', [\App\Http\Controllers\Admin\RoleManagementController::class, 'duplicate'])->name('roles.duplicate');
        Route::get('roles/{role}/users', [\App\Http\Controllers\Admin\RoleManagementController::class, 'users'])->name('roles.users');

        // Permission Management
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionManagementController::class);
        Route::get('permissions/bulk-create', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'showBulkCreate'])->name('bulk-create');
        Route::post('permissions/bulk-create', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'bulkCreate'])->name('bulk-create.store');
        Route::get('permissions/{permission}/roles', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'roles'])->name('roles');
        Route::get('permissions/{permission}/users', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'users'])->name('users');
        
        // Tenant Car Selection Management
        Route::get('tenant-car-selection', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'index'])->name('car-selection.index');
        Route::get('tenant-car-selection/{tenant}', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'show'])->name('car-selection.show');
        Route::post('tenant-car-selection/{tenant}/update', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'updateLandingDisplay'])->name('car-selection.update');
        Route::post('tenant-car-selection/{tenant}/bulk-update', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'bulkUpdate'])->name('car-selection.bulk-update');
    });
    
    Route::group([], function () { // Temporarily removed 'tenant' middleware
        // Agencies
        Route::resource('agences', AgenceController::class);
        Route::get('agences/{agence}/toggle-status', [AgenceController::class, 'toggleStatus'])->name('agences.toggle-status');
        Route::get('agences/active', [AgenceController::class, 'active'])->name('agences.active');

        // Brands
        Route::resource('marques', MarqueController::class);
        Route::get('marques/{marque}/toggle-status', [MarqueController::class, 'toggleStatus'])->name('marques.toggle-status');
        Route::get('marques/active', [MarqueController::class, 'active'])->name('marques.active');

        // Clients
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/toggle-blacklist', [ClientController::class, 'toggleBlacklist'])->name('clients.toggle-blacklist');
        Route::get('clients/statistics', [ClientController::class, 'statistics'])->name('clients.statistics');
        Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');

        // Vehicles
        Route::resource('vehicules', VehiculeController::class);
        Route::get('vehicules/{vehicule}/toggle-status', [VehiculeController::class, 'toggleStatus'])->name('vehicules.toggle-status');
        Route::get('vehicules/available', [VehiculeController::class, 'available'])->name('vehicules.available');

        // Reports
        Route::get('reports/customers', [App\Http\Controllers\ReportsController::class, 'customers'])->name('reports.customers');
        Route::get('reports/maintenance', [App\Http\Controllers\ReportsController::class, 'maintenance'])->name('reports.maintenance');
        Route::get('reports/seasonal', [App\Http\Controllers\ReportsController::class, 'seasonal'])->name('reports.seasonal');
        Route::get('reports/export/financial', [App\Http\Controllers\ReportsController::class, 'exportFinancial'])->name('reports.export.financial');
        Route::get('reports/export/operational', [App\Http\Controllers\ReportsController::class, 'exportOperational'])->name('reports.export.operational');
        Route::get('reports/export/customers', [App\Http\Controllers\ReportsController::class, 'exportCustomers'])->name('reports.export.customers');
        Route::get('reports/export/maintenance', [App\Http\Controllers\ReportsController::class, 'exportMaintenance'])->name('reports.export.maintenance');

        // Reservations
        Route::resource('reservations', ReservationController::class);
        Route::post('reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::get('reservations/statistics', [ReservationController::class, 'statistics'])->name('reservations.statistics');

        // Contracts
        Route::resource('contrats', ContratController::class);
        Route::get('contrats/{contrat}/print', [ContratController::class, 'print'])->name('contrats.print');

        // Insurance
        Route::resource('assurances', AssuranceController::class);
        Route::get('assurances/expiring', [AssuranceController::class, 'expiring'])->name('assurances.expiring');
        Route::post('assurances/{assurance}/renew', [AssuranceController::class, 'renew'])->name('assurances.renew');

        // Maintenance
        Route::resource('vidanges', VidangeController::class);
        Route::get('vidanges/due', [VidangeController::class, 'due'])->name('vidanges.due');
        Route::post('vidanges/{vidange}/complete', [VidangeController::class, 'complete'])->name('vidanges.complete');

        Route::resource('visites', VisiteController::class);
        Route::get('visites/due', [VisiteController::class, 'due'])->name('visites.due');
        Route::post('visites/{visite}/complete', [VisiteController::class, 'complete'])->name('visites.complete');

        Route::resource('interventions', InterventionController::class);
        Route::post('interventions/{intervention}/start', [InterventionController::class, 'start'])->name('interventions.start');
        Route::post('interventions/{intervention}/complete', [InterventionController::class, 'complete'])->name('interventions.complete');
        Route::get('interventions/statistics', [InterventionController::class, 'statistics'])->name('interventions.statistics');

        // Contract Returns
        Route::resource('retour-contrats', RetourContratController::class);
        Route::post('retour-contrats/{retourContrat}/process', [RetourContratController::class, 'process'])->name('retour-contrats.process');
        Route::get('retour-contrats/contract/{contrat}/details', [RetourContratController::class, 'getContractDetails'])->name('retour-contrats.contract-details');

        // Charges
        Route::resource('charges', ChargeController::class);
        Route::get('charges/export', [ChargeController::class, 'export'])->name('charges.export');

        // Notifications
        Route::resource('notifications', NotificationController::class);
        Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
        Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
        Route::put('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.security.update');
        Route::put('/settings/billing', [SettingsController::class, 'updateBilling'])->name('settings.billing.update');
    });
});

// Include SaaS routes
require __DIR__.'/saas.php';
