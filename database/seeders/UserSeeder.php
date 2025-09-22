<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            // Create admin users for each tenant
            User::create([
                'tenant_id' => $tenant->id,
                'name' => 'Admin ' . $tenant->name,
                'email' => 'admin' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create instructor users
            $instructors = [
                [
                    'name' => 'Jean Dupont',
                    'email' => 'jean.dupont' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Marie Martin',
                    'email' => 'marie.martin' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Pierre Durand',
                    'email' => 'pierre.durand' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
            ];

            foreach ($instructors as $instructorData) {
                User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $instructorData['name'],
                    'email' => $instructorData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            }

            // Create student users
            $students = [
                [
                    'name' => 'Sophie Bernard',
                    'email' => 'sophie.bernard' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Thomas Petit',
                    'email' => 'thomas.petit' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Emma Rousseau',
                    'email' => 'emma.rousseau' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Lucas Moreau',
                    'email' => 'lucas.moreau' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
                [
                    'name' => 'Chloé Simon',
                    'email' => 'chloe.simon' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                ],
            ];

            foreach ($students as $studentData) {
                User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            }
        }
    }
}
