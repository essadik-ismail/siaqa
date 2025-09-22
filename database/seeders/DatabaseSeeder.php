<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the database with sample data
        $this->call([
            TenantSeeder::class,
            UserSeeder::class,
            MarqueSeeder::class,
            VehiculeSeeder::class,
            InstructorSeeder::class,
            StudentSeeder::class,
            PackageSeeder::class,
            StudentPackageSeeder::class,
            LessonSeeder::class,
            ExamSeeder::class,
            StudentProgressSeeder::class,
            InstructorAvailabilitySeeder::class,
            VehicleAssignmentSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
