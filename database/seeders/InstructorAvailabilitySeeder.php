<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstructorAvailability;
use App\Models\Instructor;
use App\Models\Tenant;

class InstructorAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $instructors = Instructor::where('tenant_id', $tenant->id)->get();

            foreach ($instructors as $instructor) {
                // Create availability for the next 30 days
                for ($i = 0; $i < 30; $i++) {
                    $date = now()->addDays($i);
                    $dayOfWeek = strtolower($date->format('l'));

                    // Skip Sundays for most instructors
                    if ($dayOfWeek === 'sunday' && $instructor->id % 3 !== 0) {
                        continue;
                    }

                    // Create morning availability (9:00-12:00)
                    InstructorAvailability::create([
                        'tenant_id' => $tenant->id,
                        'instructor_id' => $instructor->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => '09:00:00',
                        'end_time' => '12:00:00',
                        'is_available' => true,
                        'max_students' => 2,
                        'current_bookings' => 0,
                        'notes' => 'Disponible le matin',
                    ]);

                    // Create afternoon availability (14:00-18:00)
                    InstructorAvailability::create([
                        'tenant_id' => $tenant->id,
                        'instructor_id' => $instructor->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => '14:00:00',
                        'end_time' => '18:00:00',
                        'is_available' => true,
                        'max_students' => 2,
                        'current_bookings' => 0,
                        'notes' => 'Disponible l\'après-midi',
                    ]);

                    // Create evening availability for some instructors (18:00-20:00)
                    if ($instructor->id % 2 === 0) {
                        InstructorAvailability::create([
                            'tenant_id' => $tenant->id,
                            'instructor_id' => $instructor->id,
                            'date' => $date->format('Y-m-d'),
                            'start_time' => '18:00:00',
                            'end_time' => '20:00:00',
                            'is_available' => true,
                            'max_students' => 1,
                            'current_bookings' => 0,
                            'notes' => 'Disponible en soirée',
                        ]);
                    }
                }
            }
        }
    }
}