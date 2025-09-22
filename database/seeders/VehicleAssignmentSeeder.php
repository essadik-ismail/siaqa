<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleAssignment;
use App\Models\Vehicle;
use App\Models\Instructor;
use App\Models\Tenant;

class VehicleAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $vehicles = Vehicle::where('tenant_id', $tenant->id)->get();
            $instructors = Instructor::where('tenant_id', $tenant->id)->get();

            // Assign vehicles to instructors
            foreach ($instructors as $index => $instructor) {
                $vehicle = $vehicles->get($index % $vehicles->count());
                
                if ($vehicle) {
                    VehicleAssignment::create([
                        'tenant_id' => $tenant->id,
                        'vehicle_id' => $vehicle->id,
                        'instructor_id' => $instructor->id,
                        'assigned_date' => now()->subDays(30),
                        'status' => 'active',
                        'notes' => 'Véhicule assigné pour les cours de conduite',
                        'assigned_by' => 1, // Assuming admin user ID
                    ]);
                }
            }

            // Create some historical assignments
            if ($vehicles->count() > 1) {
                VehicleAssignment::create([
                    'tenant_id' => $tenant->id,
                    'vehicle_id' => $vehicles->first()->id,
                    'instructor_id' => $instructors->first()->id,
                    'assigned_date' => now()->subDays(60),
                    'unassigned_date' => now()->subDays(31),
                    'status' => 'completed',
                    'notes' => 'Ancienne assignation - véhicule remplacé',
                    'assigned_by' => 1,
                ]);
            }
        }
    }
}
