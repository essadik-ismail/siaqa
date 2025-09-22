<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentPackage;
use App\Models\Student;
use App\Models\Package;
use App\Models\Tenant;

class StudentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $students = Student::where('tenant_id', $tenant->id)->get();
            $packages = Package::where('tenant_id', $tenant->id)->get();

            foreach ($students as $index => $student) {
                $package = $packages->get($index % $packages->count());
                
                if ($package) {
                    StudentPackage::create([
                        'tenant_id' => $tenant->id,
                        'student_id' => $student->id,
                        'package_id' => $package->id,
                        'purchase_date' => now()->subDays(rand(1, 90)),
                        'start_date' => now()->subDays(rand(1, 30)),
                        'end_date' => now()->addDays($package->validity_days),
                        'status' => 'active',
                        'price_paid' => $package->price,
                        'payment_method' => ['cash', 'card', 'bank_transfer'][rand(0, 2)],
                        'payment_status' => 'paid',
                        'theory_hours_used' => rand(0, $package->theory_hours),
                        'practical_hours_used' => rand(0, $package->practical_hours),
                        'notes' => 'Package acheté par l\'étudiant',
                    ]);
                }
            }

            // Create some completed packages
            foreach ($students->take(2) as $student) {
                $package = $packages->first();
                
                if ($package) {
                    StudentPackage::create([
                        'tenant_id' => $tenant->id,
                        'student_id' => $student->id,
                        'package_id' => $package->id,
                        'purchase_date' => now()->subDays(120),
                        'start_date' => now()->subDays(120),
                        'end_date' => now()->subDays(30),
                        'status' => 'completed',
                        'price_paid' => $package->price,
                        'payment_method' => 'card',
                        'payment_status' => 'paid',
                        'theory_hours_used' => $package->theory_hours,
                        'practical_hours_used' => $package->practical_hours,
                        'notes' => 'Package terminé avec succès',
                    ]);
                }
            }
        }
    }
}