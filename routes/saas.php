<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BillingController;

/*
|--------------------------------------------------------------------------
| SaaS Routes
|--------------------------------------------------------------------------
|
| Routes for SaaS platform management (super admin)
|
*/

Route::middleware(['auth', 'role:super_admin'])->prefix('saas')->name('saas.')->group(function () {
    // SaaS Dashboard
    Route::get('dashboard', function () {
        $stats = [
            'total_tenants' => \App\Models\Tenant::count(),
            'active_tenants' => \App\Models\Tenant::where('is_active', true)->count(),
            'trial_tenants' => \App\Models\Tenant::where('trial_ends_at', '>', now())->count(),
            'revenue' => \App\Models\Invoice::where('status', 'paid')->sum('amount'),
        ];
        
        return view('saas.dashboard', compact('stats'));
    })->name('dashboard');
    
    // Global Users Management
    Route::resource('global-users', \App\Http\Controllers\GlobalUserManagementController::class)->parameters(['global-users' => 'user']);
    Route::patch('global-users/{user}/toggle-status', [\App\Http\Controllers\GlobalUserManagementController::class, 'toggleStatus'])->name('global-users.toggle-status');
    Route::post('global-users/{user}/launch', [\App\Http\Controllers\GlobalUserManagementController::class, 'launchAsUser'])->name('global-users.launch');
    
    // System Diagnostics (Super Admin Only)
    Route::get('system-diagnostics', [\App\Http\Controllers\SystemDiagnosticsController::class, 'index'])->name('system-diagnostics');
    Route::post('system-diagnostics/clear-cache', [\App\Http\Controllers\SystemDiagnosticsController::class, 'clearCache'])->name('system-diagnostics.clear-cache');
});

/*
|--------------------------------------------------------------------------
| Tenant Routes (Multi-tenant)
|--------------------------------------------------------------------------
|
| Routes for individual tenant applications
|
*/

Route::middleware(['tenant'])->group(function () {
    // Billing Routes
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('dashboard', [BillingController::class, 'dashboard'])->name('dashboard');
        Route::get('plans', [BillingController::class, 'plans'])->name('plans');
        Route::post('subscribe', [BillingController::class, 'subscribe'])->name('subscribe');
        Route::post('payment-method', [BillingController::class, 'updatePaymentMethod'])->name('payment-method');
        Route::post('cancel', [BillingController::class, 'cancelSubscription'])->name('cancel');
        Route::get('invoices', [BillingController::class, 'invoices'])->name('invoices');
        Route::get('invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');
        Route::get('usage', [BillingController::class, 'usage'])->name('usage');
    });
}); 