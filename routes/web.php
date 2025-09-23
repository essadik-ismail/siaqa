<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Include health check routes
require_once __DIR__.'/health.php';
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\VidangeController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

// Private media routes (signed URLs)
Route::get('/media/{type}/{id}', [App\Http\Controllers\MediaController::class, 'show'])
    ->name('media.show')
    ->middleware('signed');

// Public landing page routes
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

// Test route for debugging
Route::get('/test-create', function() {
    return 'Test create route works!';
});

// Test students create route
Route::get('/test-students-create-simple', function() {
    return 'Students create route works!';
});

// Test lessons create route
Route::get('/test-lessons-create', function() {
    try {
        $controller = new App\Http\Controllers\LessonController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Test direct lessons create route
Route::get('/test-lessons-create-direct', [LessonController::class, 'create'])->name('test.lessons.create');

// Create routes - defined outside route group to avoid 404 issues
Route::get('/students/create', function() {
    try {
        $controller = new App\Http\Controllers\StudentController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('students.create');

Route::get('/instructors/create', function() {
    try {
        $controller = new App\Http\Controllers\InstructorController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('instructors.create');

Route::get('/lessons/create', function() {
    try {
        $controller = new App\Http\Controllers\LessonController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('lessons.create');

Route::get('/exams/create', function() {
    try {
        $controller = new App\Http\Controllers\ExamController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('exams.create');

Route::get('/payments/create', function() {
    try {
        $controller = new App\Http\Controllers\PaymentController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('payments.create');

Route::get('/reports/create', function() {
    try {
        $controller = new App\Http\Controllers\ReportController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('reports.create');


// Test actual controller method
Route::get('/test-controller-create', function() {
    try {
        // Create a fake user for testing
        $user = new \App\Models\User();
        $user->id = 1;
        $user->tenant_id = 1;
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        
        auth()->login($user);
        
        $controller = new App\Http\Controllers\StudentController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Test auth status
Route::get('/test-auth', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return "User is authenticated: " . $user->name . " (ID: " . $user->id . ", Tenant ID: " . ($user->tenant_id ?? 'null') . ")";
    } else {
        return "User is NOT authenticated";
    }
});

// Test create page without auth
Route::get('/test-students-create', function() {
    // Create a fake user for testing
    $user = new \App\Models\User();
    $user->id = 1;
    $user->tenant_id = 1;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    
    auth()->login($user);
    
    $controller = new \App\Http\Controllers\StudentController();
    return $controller->create();
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [App\Http\Controllers\LandingController::class, 'login'])->name('login.post');


Route::post('/logout', [App\Http\Controllers\LandingController::class, 'logout'])->name('logout');

// Development route - Auto login for testing
Route::get('/dev-login', function () {
    if (app()->environment('local', 'development')) {
        $user = \App\Models\User::where('email', 'admin@auto-École-excellence.fr')->first();
        
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Auto-logged in as admin for testing!');
        } else {
            return redirect()->route('home')->with('error', 'Admin user not found. Please run seeders first.');
        }
    }
    
    return redirect()->route('home')->with('error', 'Auto-login only available in development environment.');
})->name('dev.login');

// Language switching route
// Language switching disabled - French only

// Development routes (migrations and seeders)
Route::get('/dev/migrate', function () {
    // Check if we're in development environment
    if (!app()->environment('local', 'development')) {
        abort(404, 'This route is only available in development environment');
    }
    
    // Increase execution time limit for long-running operations
    set_time_limit(300); // 5 minutes
    ini_set('memory_limit', '512M');
    
    try {
        \Artisan::call('migrate', ['--force' => true]);
        $output = \Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Migrations executed successfully',
            'output' => $output,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error executing migrations',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('dev.migrate');

Route::get('/dev/migrate-fresh', function () {
    // Check if we're in development environment
    if (!app()->environment('local', 'development')) {
        abort(404, 'This route is only available in development environment');
    }
    
    try {
        // Use the dedicated Artisan command with extended timeout
        $exitCode = \Artisan::call('dev:migrate-fresh', ['--timeout' => 600]);
        $output = \Artisan::output();
        
        if ($exitCode === 0) {
            return response()->json([
                'success' => true,
                'message' => 'Fresh migrations executed successfully',
                'output' => $output,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error executing fresh migrations',
                'output' => $output,
                'exit_code' => $exitCode,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error executing fresh migrations',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('dev.migrate-fresh');

Route::get('/dev/seed', function () {
    // Check if we're in development environment
    if (!app()->environment('local', 'development')) {
        abort(404, 'This route is only available in development environment');
    }
    
    try {
        \Artisan::call('db:seed', ['--force' => true]);
        $output = \Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Seeders executed successfully',
            'output' => $output,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error executing seeders',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('dev.seed');

Route::get('/dev/migrate-fresh-seed', function () {
    // Check if we're in development environment
    if (!app()->environment('local', 'development')) {
        abort(404, 'This route is only available in development environment');
    }
    
    try {
        // Use the dedicated Artisan command with extended timeout
        $exitCode = \Artisan::call('dev:migrate-fresh-seed', ['--timeout' => 600]);
        $output = \Artisan::output();
        
        if ($exitCode === 0) {
            return response()->json([
                'success' => true,
                'message' => 'Fresh migrations and seeders executed successfully',
                'output' => $output,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error executing fresh migrations and seeders',
                'output' => $output,
                'exit_code' => $exitCode,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error executing fresh migrations and seeders',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('dev.migrate-fresh-seed');

Route::get('/dev/clear-cache', function () {
    // Check if we're in development environment
    if (!app()->environment('local', 'development')) {
        abort(404, 'This route is only available in development environment');
    }
    
    try {
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error clearing caches',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('dev.clear-cache');

// Authentication routes - handled by LandingController

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/tab-data', [DashboardController::class, 'getTabData'])->name('dashboard.tab-data');
    
    // Users route (redirects to admin users)
    Route::get('/users', function () {
        return redirect()->route('admin.users.index');
    })->name('users.index');

// Return from impersonation
Route::post('/admin/return-from-impersonation', [\App\Http\Controllers\Admin\UserManagementController::class, 'returnFromImpersonation'])->name('admin.return-from-impersonation');
    
    // Admin Management Routes - Regular Admin Access
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
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
        // Route::resource('agencies', \App\Http\Controllers\Admin\AgencyManagementController::class);
        // Route::post('agencies/{agency}/toggle-status', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'toggleStatus'])->name('agencies.toggle-status');
        // Route::get('agencies/{agency}/users', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'users'])->name('agencies.users');
        // Route::get('agencies/{agency}/statistics', [\App\Http\Controllers\Admin\AgencyManagementController::class, 'statistics'])->name('agencies.statistics');

        // Role Management
        Route::resource('roles', \App\Http\Controllers\Admin\RoleManagementController::class);
        Route::get('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleManagementController::class, 'updatePermissions'])->name('roles.permissions.update');
        Route::post('roles/{role}/duplicate', [\App\Http\Controllers\Admin\RoleManagementController::class, 'duplicate'])->name('roles.duplicate');
        Route::get('roles/{role}/users', [\App\Http\Controllers\Admin\RoleManagementController::class, 'users'])->name('roles.users');

        // Permission Management
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionManagementController::class);
        Route::get('permissions/bulk-create', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'showBulkCreate'])->name('admin.permissions.bulk-create');
        Route::post('permissions/bulk-create', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'bulkCreate'])->name('admin.permissions.bulk-create.store');
        Route::get('permissions/{permission}/roles', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'roles'])->name('admin.permissions.roles');
        Route::get('permissions/{permission}/users', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'users'])->name('admin.permissions.users');
        
        // Route alias for backward compatibility
        Route::get('bulk-create', [\App\Http\Controllers\Admin\PermissionManagementController::class, 'showBulkCreate'])->name('bulk-create');
        
        // Tenant Car Selection Management
        Route::get('tenant-car-selection', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'index'])->name('car-selection.index');
        Route::get('tenant-car-selection/{tenant}', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'show'])->name('car-selection.show');
        Route::post('tenant-car-selection/{tenant}/update', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'updateLandingDisplay'])->name('car-selection.update');
        Route::post('tenant-car-selection/{tenant}/bulk-update', [\App\Http\Controllers\Admin\TenantCarSelectionController::class, 'bulkUpdate'])->name('car-selection.bulk-update');
    });
    
    // Super Admin Routes - SaaS Management (Exclusive Access)
    Route::prefix('saas')->name('saas.')->middleware(['super_admin'])->group(function () {
        // SaaS Overview
        Route::get('/', function () {
            $stats = [
                'total_tenants' => \App\Models\Tenant::count(),
                'total_agencies' => \App\Models\Agency::count(),
                'total_users' => \App\Models\User::count(),
                'total_revenue' => \App\Models\Reservation::sum('prix_total'),
                'active_subscriptions' => \App\Models\Tenant::where('is_active', true)->count(),
                'inactive_tenants' => \App\Models\Tenant::where('is_active', false)->count(),
                'trial_tenants' => \App\Models\Tenant::where('is_active', true)
                    ->whereNotNull('trial_ends_at')
                    ->where('trial_ends_at', '>', now())
                    ->count(),
                'expired_tenants' => \App\Models\Tenant::where('is_active', true)
                    ->whereHas('subscription', function($query) {
                        $query->where(function($q) {
                            $q->where('status', 'canceled')
                              ->orWhere('status', 'unpaid')
                              ->orWhere('status', 'past_due')
                              ->orWhere(function($subQ) {
                                  $subQ->whereNotNull('ends_at')
                                       ->where('ends_at', '<', now());
                              });
                        });
                    })
                    ->count(),
            ];
            return view('saas.overview', compact('stats'));
        })->name('overview');
        
        // Tenant Management
        Route::resource('tenants', \App\Http\Controllers\TenantManagementController::class);
        Route::post('tenants/{tenant}/toggle-status', [\App\Http\Controllers\TenantManagementController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::get('tenants/{tenant}/billing', [\App\Http\Controllers\TenantManagementController::class, 'billing'])->name('tenants.billing');
        Route::post('tenants/{tenant}/billing', [\App\Http\Controllers\TenantManagementController::class, 'updateBilling'])->name('tenants.billing.update');
        
        // Global User Management - Routes moved to saas.php
        
        // Global Role Management
        Route::resource('global-roles', \App\Http\Controllers\GlobalRoleManagementController::class);
        Route::get('global-roles/{role}/permissions', [\App\Http\Controllers\GlobalRoleManagementController::class, 'permissions'])->name('global-roles.permissions');
        Route::post('global-roles/{role}/permissions', [\App\Http\Controllers\GlobalRoleManagementController::class, 'updatePermissions'])->name('global-roles.permissions.update');
        
        // Global Permission Management
        Route::resource('global-permissions', \App\Http\Controllers\GlobalPermissionManagementController::class);
        
        // Billing & Subscriptions
        Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
        Route::get('billing/overview', [\App\Http\Controllers\BillingController::class, 'overview'])->name('billing.overview');
        Route::get('billing/invoices', [\App\Http\Controllers\BillingController::class, 'invoices'])->name('billing.invoices');
        
        // System-wide Analytics
        Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/tenants', [\App\Http\Controllers\AnalyticsController::class, 'tenants'])->name('analytics.tenants');
        Route::get('analytics/revenue', [\App\Http\Controllers\AnalyticsController::class, 'revenue'])->name('analytics.revenue');
        
        // System Maintenance
        Route::get('maintenance', [\App\Http\Controllers\MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/logs', [\App\Http\Controllers\MaintenanceController::class, 'logs'])->name('maintenance.logs');
        Route::post('maintenance/backup', [\App\Http\Controllers\MaintenanceController::class, 'createBackup'])->name('maintenance.backup');
        Route::post('maintenance/clear-cache', [\App\Http\Controllers\MaintenanceController::class, 'clearCache'])->name('maintenance.clear-cache');
        Route::post('maintenance/optimize', [\App\Http\Controllers\MaintenanceController::class, 'optimize'])->name('maintenance.optimize');
    });
    
    Route::group([], function () { // Temporarily removed 'tenant' middleware
        // Brands
        Route::resource('marques', MarqueController::class);
        Route::get('marques/{marque}/toggle-status', [MarqueController::class, 'toggleStatus'])->name('marques.toggle-status');
        Route::get('marques/active', [MarqueController::class, 'active'])->name('marques.active');

        // Clients (Students)
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/toggle-blacklist', [ClientController::class, 'toggleBlacklist'])->name('clients.toggle-blacklist');
        Route::get('clients/statistics', [ClientController::class, 'statistics'])->name('clients.statistics');
        Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');

        // Vehicles
        Route::resource('vehicules', VehiculeController::class);
        Route::get('vehicules/{vehicule}/toggle-status', [VehiculeController::class, 'toggleStatus'])->name('vehicules.toggle-status');
        Route::post('vehicules/{vehicule}/toggle-landing', [VehiculeController::class, 'toggleLandingDisplay'])->name('vehicules.toggle-landing');
        Route::post('vehicules/{vehicule}/remove-image', [VehiculeController::class, 'removeImage'])->name('vehicules.remove-image');
        Route::get('vehicules/available', [VehiculeController::class, 'available'])->name('vehicules.available');

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

// Include driving school routes
require __DIR__.'/driving-school.php';

// Driving School Web Routes - Temporarily without auth for testing
Route::group([], function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Students
    Route::get('/students', [App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [App\Http\Controllers\StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [App\Http\Controllers\StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/{student}/progress', [App\Http\Controllers\StudentController::class, 'progress'])->name('students.progress');
    Route::get('/students/{student}/schedule', [App\Http\Controllers\StudentController::class, 'schedule'])->name('students.schedule');
    Route::post('/students/{student}/status', [App\Http\Controllers\StudentController::class, 'updateStatus'])->name('students.updateStatus');
    Route::get('/students/{student}/payments', [App\Http\Controllers\StudentController::class, 'payments'])->name('students.payments');
    
    // Instructors
    Route::get('/instructors', [App\Http\Controllers\InstructorController::class, 'index'])->name('instructors.index');
    Route::post('/instructors', [App\Http\Controllers\InstructorController::class, 'store'])->name('instructors.store');
    Route::get('/instructors/{instructor}', [App\Http\Controllers\InstructorController::class, 'show'])->name('instructors.show');
    Route::get('/instructors/{instructor}/edit', [App\Http\Controllers\InstructorController::class, 'edit'])->name('instructors.edit');
    Route::put('/instructors/{instructor}', [App\Http\Controllers\InstructorController::class, 'update'])->name('instructors.update');
    Route::delete('/instructors/{instructor}', [App\Http\Controllers\InstructorController::class, 'destroy'])->name('instructors.destroy');
    
    // Lessons
    Route::get('/lessons', [App\Http\Controllers\LessonController::class, 'index'])->name('lessons.index');
    Route::post('/lessons', [App\Http\Controllers\LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}', [App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
    Route::get('/lessons/{lesson}/edit', [App\Http\Controllers\LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [App\Http\Controllers\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [App\Http\Controllers\LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::patch('/lessons/{lesson}/start', [App\Http\Controllers\LessonController::class, 'start'])->name('lessons.start');
    Route::patch('/lessons/{lesson}/complete', [App\Http\Controllers\LessonController::class, 'complete'])->name('lessons.complete');
    Route::patch('/lessons/{lesson}/cancel', [App\Http\Controllers\LessonController::class, 'cancel'])->name('lessons.cancel');
    
    // Exams
    Route::get('/exams', [App\Http\Controllers\ExamController::class, 'index'])->name('exams.index');
    Route::post('/exams', [App\Http\Controllers\ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [App\Http\Controllers\ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [App\Http\Controllers\ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [App\Http\Controllers\ExamController::class, 'destroy'])->name('exams.destroy');
    Route::patch('/exams/{exam}/start', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
    Route::patch('/exams/{exam}/complete', [App\Http\Controllers\ExamController::class, 'complete'])->name('exams.complete');
    Route::patch('/exams/{exam}/cancel', [App\Http\Controllers\ExamController::class, 'cancel'])->name('exams.cancel');
    
    // Payments
    Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [App\Http\Controllers\PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [App\Http\Controllers\PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::patch('/payments/{payment}/mark-paid', [App\Http\Controllers\PaymentController::class, 'markAsPaid'])->name('payments.mark-paid');
    
    // Schedule
    Route::get('/schedule', [App\Http\Controllers\LessonController::class, 'schedule'])->name('schedule.index');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');

    // Student Packages
    Route::get('/student-packages', [App\Http\Controllers\StudentPackageController::class, 'index'])->name('student-packages.index');
    Route::get('/student-packages/create', [App\Http\Controllers\StudentPackageController::class, 'create'])->name('student-packages.create');
    Route::post('/student-packages', [App\Http\Controllers\StudentPackageController::class, 'store'])->name('student-packages.store');
    Route::get('/student-packages/{studentPackage}', [App\Http\Controllers\StudentPackageController::class, 'show'])->name('student-packages.show');
    Route::get('/student-packages/{studentPackage}/edit', [App\Http\Controllers\StudentPackageController::class, 'edit'])->name('student-packages.edit');
    Route::put('/student-packages/{studentPackage}', [App\Http\Controllers\StudentPackageController::class, 'update'])->name('student-packages.update');
    Route::delete('/student-packages/{studentPackage}', [App\Http\Controllers\StudentPackageController::class, 'destroy'])->name('student-packages.destroy');
    Route::post('/student-packages/{studentPackage}/update-status', [App\Http\Controllers\StudentPackageController::class, 'updateStatus'])->name('student-packages.update-status');
    Route::get('/student-packages/statistics/overview', [App\Http\Controllers\StudentPackageController::class, 'statistics'])->name('student-packages.statistics');
    Route::get('/students/{student}/packages', [App\Http\Controllers\StudentPackageController::class, 'byStudent'])->name('student-packages.by-student');
    Route::get('/packages/{package}/students', [App\Http\Controllers\StudentPackageController::class, 'byPackage'])->name('student-packages.by-package');
    Route::get('/reports/{report}/edit', [App\Http\Controllers\ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [App\Http\Controllers\ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');
});
