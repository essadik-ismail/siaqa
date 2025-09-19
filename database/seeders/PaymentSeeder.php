<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Tenant;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $students = Student::where('tenant_id', $tenant->id)->get();

            if ($students->isEmpty()) {
                continue;
            }

            $paymentTypes = ['lesson', 'package', 'exam', 'registration'];
            $statuses = ['pending', 'paid', 'overdue', 'cancelled'];
            $methods = ['cash', 'card', 'bank_transfer', 'online'];

            foreach ($students as $student) {
                // Create 3-8 payments per student
                $paymentCount = rand(3, 8);
                
                for ($i = 0; $i < $paymentCount; $i++) {
                    $type = $paymentTypes[array_rand($paymentTypes)];
                    $status = $statuses[array_rand($statuses)];
                    $method = $methods[array_rand($methods)];
                    
                    $amount = $this->generateAmount($type);
                    $dueDate = now()->subDays(rand(0, 60));
                    $paidAt = $status === 'paid' ? $dueDate->copy()->addDays(rand(0, 5)) : null;

                    Payment::create([
                        'tenant_id' => $tenant->id,
                        'student_id' => $student->id,
                        'payment_number' => 'PAY-' . $tenant->id . '-' . $student->id . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                        'type' => $type,
                        'amount' => $amount,
                        'status' => $status,
                        'payment_method' => $method,
                        'due_date' => $dueDate,
                        'paid_at' => $paidAt,
                        'description' => $this->generateDescription($type),
                        'notes' => $this->generateNotes($status),
                    ]);
                }
            }
        }
    }

    private function generateAmount($type)
    {
        $amounts = [
            'lesson' => [25.00, 30.00, 35.00, 40.00],
            'package' => [200.00, 300.00, 500.00, 800.00],
            'exam' => [50.00, 75.00, 100.00],
            'registration' => [150.00, 200.00, 250.00],
        ];

        return $amounts[$type][array_rand($amounts[$type])];
    }

    private function generateDescription($type)
    {
        $descriptions = [
            'lesson' => [
                'Paiement leçon de conduite',
                'Cours pratique',
                'Leçon de code',
                'Session de simulation',
            ],
            'package' => [
                'Forfait 10 leçons',
                'Pack découverte',
                'Formation complète',
                'Forfait accéléré',
            ],
            'exam' => [
                'Frais d\'examen théorique',
                'Frais d\'examen pratique',
                'Inscription examen',
            ],
            'registration' => [
                'Frais d\'inscription',
                'Dossier administratif',
                'Frais de dossier',
            ],
        ];

        return $descriptions[$type][array_rand($descriptions[$type])];
    }

    private function generateNotes($status)
    {
        $notes = [
            'pending' => [
                'En attente de paiement',
                'Rappel envoyé',
                'Paiement en cours',
            ],
            'paid' => [
                'Paiement reçu',
                'Transaction validée',
                'Paiement confirmé',
            ],
            'overdue' => [
                'Paiement en retard',
                'Relance nécessaire',
                'Échéance dépassée',
            ],
            'cancelled' => [
                'Paiement annulé',
                'Transaction annulée',
                'Remboursement effectué',
            ],
        ];

        return $notes[$status][array_rand($notes[$status])];
    }
}
