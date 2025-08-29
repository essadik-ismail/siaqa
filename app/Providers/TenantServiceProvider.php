<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Agence;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Reservation;
use App\Models\Contrat;
use App\Models\Assurance;
use App\Models\Vidange;
use App\Models\Visite;
use App\Models\Intervention;
use App\Models\RetourContrat;
use App\Models\Charge;
use App\Models\Notification;
use App\Models\ActivityLog;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('tenant', function () {
            return null; // Will be set by middleware
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add global scope to filter by tenant for all tenant-aware models
        $this->addTenantScopeToModels();
    }

    protected function addTenantScopeToModels()
    {
        $tenantAwareModels = [
            User::class,
            Agence::class,
            Client::class,
            Vehicule::class,
            Reservation::class,
            Contrat::class,
            Assurance::class,
            Vidange::class,
            Visite::class,
            Intervention::class,
            RetourContrat::class,
            Charge::class,
            Notification::class,
            ActivityLog::class,
        ];

        foreach ($tenantAwareModels as $model) {
            $model::addGlobalScope('tenant', function ($query) {
                if (app()->has('tenant') && app('tenant') !== null) {
                    $query->where('tenant_id', app('tenant')->id);
                }
            });
        }
    }
} 