<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\VidangeController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Tenant-aware routes - all routes below this are tenant-specific
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    
    // Clients routes
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::post('/', [ClientController::class, 'store']);
        Route::get('/statistics', [ClientController::class, 'statistics']);
        Route::get('/search', [ClientController::class, 'search']);
        Route::get('/{client}', [ClientController::class, 'show']);
        Route::put('/{client}', [ClientController::class, 'update']);
        Route::delete('/{client}', [ClientController::class, 'destroy']);
        Route::patch('/{client}/toggle-blacklist', [ClientController::class, 'toggleBlacklist']);
    });

    // Marques routes
    Route::prefix('marques')->group(function () {
        Route::get('/', [MarqueController::class, 'index']);
        Route::post('/', [MarqueController::class, 'store']);
        Route::get('/active', [MarqueController::class, 'active']);
        Route::get('/{marque}', [MarqueController::class, 'show']);
        Route::put('/{marque}', [MarqueController::class, 'update']);
        Route::delete('/{marque}', [MarqueController::class, 'destroy']);
        Route::patch('/{marque}/toggle-status', [MarqueController::class, 'toggleStatus']);
    });

    // Vehicules routes
    Route::prefix('vehicules')->group(function () {
        Route::get('/', [VehiculeController::class, 'index']);
        Route::post('/', [VehiculeController::class, 'store']);
        Route::get('/available', [VehiculeController::class, 'available']);
        Route::get('/statistics', [VehiculeController::class, 'statistics']);
        Route::get('/{vehicule}', [VehiculeController::class, 'show']);
        Route::put('/{vehicule}', [VehiculeController::class, 'update']);
        Route::delete('/{vehicule}', [VehiculeController::class, 'destroy']);
        Route::patch('/{vehicule}/status', [VehiculeController::class, 'updateStatus']);
    });

    // Assurances routes
    Route::prefix('assurances')->group(function () {
        Route::get('/', [AssuranceController::class, 'index']);
        Route::post('/', [AssuranceController::class, 'store']);
        Route::get('/expiring-soon', [AssuranceController::class, 'expiringSoon']);
        Route::get('/{assurance}', [AssuranceController::class, 'show']);
        Route::put('/{assurance}', [AssuranceController::class, 'update']);
        Route::delete('/{assurance}', [AssuranceController::class, 'destroy']);
    });

    // Vidanges routes
    Route::prefix('vidanges')->group(function () {
        Route::get('/', [VidangeController::class, 'index']);
        Route::post('/', [VidangeController::class, 'store']);
        Route::get('/due-soon', [VidangeController::class, 'dueSoon']);
        Route::get('/{vidange}', [VidangeController::class, 'show']);
        Route::put('/{vidange}', [VidangeController::class, 'update']);
        Route::delete('/{vidange}', [VidangeController::class, 'destroy']);
    });

    // Visites routes
    Route::prefix('visites')->group(function () {
        Route::get('/', [VisiteController::class, 'index']);
        Route::post('/', [VisiteController::class, 'store']);
        Route::get('/due-soon', [VisiteController::class, 'dueSoon']);
        Route::get('/{visite}', [VisiteController::class, 'show']);
        Route::put('/{visite}', [VisiteController::class, 'update']);
        Route::delete('/{visite}', [VisiteController::class, 'destroy']);
    });

    // Charges routes
    Route::prefix('charges')->group(function () {
        Route::get('/', [ChargeController::class, 'index']);
        Route::post('/', [ChargeController::class, 'store']);
        Route::get('/statistics', [ChargeController::class, 'statistics']);
        Route::get('/{charge}', [ChargeController::class, 'show']);
        Route::put('/{charge}', [ChargeController::class, 'update']);
        Route::delete('/{charge}', [ChargeController::class, 'destroy']);
    });

    // Notifications routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::get('/{notification}', [NotificationController::class, 'show']);
        Route::put('/{notification}', [NotificationController::class, 'update']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::patch('/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    });

    // Dashboard statistics
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_clients' => \App\Models\Client::count(),
                'total_vehicules' => \App\Models\Vehicule::count(),
                'vehicules_disponibles' => \App\Models\Vehicule::where('statut', 'disponible')->count(),
                'assurances_expirant_soon' => \App\Models\Assurance::where('date_expiration', '<=', now()->addDays(30))->count(),
            ],
            'message' => 'Statistiques du tableau de bord récupérées avec succès'
        ]);
    });

});

// Include SaaS routes
require __DIR__.'/saas.php'; 