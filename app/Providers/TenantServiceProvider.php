<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Client;
use App\Models\Vehicule;

use App\Models\Charge;
use App\Models\Notification;
use App\Models\ActivityLog;
// Driving School Models
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\StudentProgress;
use App\Models\Package;
use App\Models\StudentPackage;
use App\Models\TheoryClass;
use App\Models\StudentTheoryEnrollment;
use App\Models\InstructorAvailability;
use App\Models\VehicleAssignment;
use App\Models\Report;
use App\Models\Analytics;

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
            Client::class,
            Vehicule::class,
            Charge::class,
            Notification::class,
            ActivityLog::class,
            // Driving School Models
            Instructor::class,
            Student::class,
            Lesson::class,
            Exam::class,
            Payment::class,
            StudentProgress::class,
            Package::class,
            StudentPackage::class,
            TheoryClass::class,
            StudentTheoryEnrollment::class,
            InstructorAvailability::class,
            VehicleAssignment::class,
            Report::class,
            Analytics::class,
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