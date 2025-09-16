<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agence;
use App\Models\Marque;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Reservation;
use App\Models\Contrat;
use App\Models\Assurance;
use App\Models\Vidange;
use App\Models\Visite;
use App\Models\Intervention;
use App\Models\Charge;
use App\Models\Notification;
use Carbon\Carbon;

class CarRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the default tenant
        $tenant = \App\Models\Tenant::first();
        if (!$tenant) {
            $this->command->error('No tenant found. Please run TenantSeeder first.');
            return;
        }

        // Create sample agences
        $agences = [
            [
                'logo' => 'agence1.png',
                'nom_agence' => 'Agence Centrale',
                'Adresse' => '123 Rue de la Paix',
                'ville' => 'Paris',
                'rc' => 'RC123456',
                'patente' => 'PAT789012',
                'IF' => 'IF345678',
                'n_cnss' => 'CNSS901234',
                'ICE' => 'ICE567890',
                'n_compte_bancaire' => 'FR7630001007941234567890185',
            ],
            [
                'logo' => 'agence2.png',
                'nom_agence' => 'Agence Aéroport',
                'Adresse' => 'Terminal 2F, Aéroport Charles de Gaulle',
                'ville' => 'Roissy',
                'rc' => 'RC654321',
                'patente' => 'PAT210987',
                'IF' => 'IF876543',
                'n_cnss' => 'CNSS432109',
                'ICE' => 'ICE098765',
                'n_compte_bancaire' => 'FR7630001007941234567890186',
            ],
        ];

        foreach ($agences as $agenceData) {
            $agenceData['tenant_id'] = $tenant->id;
            Agence::create($agenceData);
        }

        // Create sample marques
        $marques = [
            ['marque' => 'Renault', 'image' => 'renault.png'],
            ['marque' => 'Peugeot', 'image' => 'peugeot.png'],
            ['marque' => 'Citroën', 'image' => 'citroen.png'],
            ['marque' => 'Volkswagen', 'image' => 'volkswagen.png'],
            ['marque' => 'BMW', 'image' => 'bmw.png'],
            ['marque' => 'Mercedes-Benz', 'image' => 'mercedes.png'],
            ['marque' => 'Toyota', 'image' => 'toyota.png'],
            ['marque' => 'Honda', 'image' => 'honda.png'],
        ];

        foreach ($marques as $marqueData) {
            $marqueData['tenant_id'] = $tenant->id;
            Marque::create($marqueData);
        }

        // Create sample clients
        $clients = [
            [
                'type' => 'client',
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@email.com',
                'telephone' => '06 12 34 56 78',
                'adresse' => '456 Avenue des Champs',
                'ville' => 'Paris',
                'code_postal' => '75008',
                'pays' => 'France',
                'date_naissance' => '1985-03-15',
                'numero_permis' => '123456789012345',
                'date_obtention_permis' => '2003-06-20',
                'nationalite' => 'Française',
                'numero_cin' => '123456789',
                'date_cin_expiration' => '2030-12-31',
                'numero_piece_identite' => 'CN123456789',
                'type_piece_identite' => 'carte_nationale',
                'date_expiration_piece' => '2030-12-31',
                'profession' => 'Ingénieur',
                'employeur' => 'TechCorp',
                'revenu_mensuel' => 4500.00,
                'description' => 'Client fidèle',
                'notes' => 'Client régulier, toujours ponctuel',
                'bloquer' => false,
                'is_blacklisted' => false,
                'is_blacklist' => false,
                'image' => null,
                'images' => null,
            ],
            [
                'type' => 'client',
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'email' => 'marie.martin@email.com',
                'telephone' => '06 98 76 54 32',
                'adresse' => '789 Boulevard Saint-Germain',
                'ville' => 'Paris',
                'code_postal' => '75006',
                'pays' => 'France',
                'date_naissance' => '1990-07-22',
                'numero_permis' => '987654321098765',
                'date_obtention_permis' => '2008-09-15',
                'nationalite' => 'Française',
                'numero_cin' => '987654321',
                'date_cin_expiration' => '2028-05-15',
                'numero_piece_identite' => 'PP987654321',
                'type_piece_identite' => 'passeport',
                'date_expiration_piece' => '2028-05-15',
                'profession' => 'Avocate',
                'employeur' => 'Cabinet Legal',
                'revenu_mensuel' => 5200.00,
                'description' => 'Nouveau client',
                'notes' => 'Client professionnel, demande des véhicules haut de gamme',
                'bloquer' => false,
                'is_blacklisted' => false,
                'is_blacklist' => false,
                'image' => null,
                'images' => null,
            ],
        ];

        foreach ($clients as $clientData) {
            $clientData['tenant_id'] = $tenant->id;
            Client::create($clientData);
        }

        // Get created data for relationships
        $agence1 = Agence::first();
        $agence2 = Agence::skip(1)->first();
        $marque1 = Marque::first();
        $marque2 = Marque::skip(1)->first();
        $client1 = Client::first();
        $client2 = Client::skip(1)->first();

        // Create sample vehicules
        $vehicules = [
            [
                'immatriculation' => 'AB-123-CD',
                'marque_id' => $marque1->id,
                'agence_id' => $agence1->id,
                'name' => 'Clio',
                'statut' => 'disponible',
                'is_active' => true,
                'type_carburant' => 'essence',
                'nombre_cylindre' => 4,
                'nbr_place' => 5,
                'couleur' => 'Blanc',
                'prix_location_jour' => 45.00,
                'kilometrage_actuel' => 15000,
                'categorie_vehicule' => 'B',
                'description' => 'Véhicule en excellent état',
            ],
            [
                'immatriculation' => 'EF-456-GH',
                'marque_id' => $marque2->id,
                'agence_id' => $agence2->id,
                'name' => '208',
                'statut' => 'disponible',
                'is_active' => true,
                'type_carburant' => 'diesel',
                'nombre_cylindre' => 4,
                'nbr_place' => 5,
                'couleur' => 'Bleu',
                'prix_location_jour' => 55.00,
                'kilometrage_actuel' => 22000,
                'categorie_vehicule' => 'B',
                'description' => 'Véhicule confortable',
            ],
        ];

        foreach ($vehicules as $vehiculeData) {
            $vehiculeData['tenant_id'] = $tenant->id;
            Vehicule::updateOrCreate(
                ['immatriculation' => $vehiculeData['immatriculation']],
                $vehiculeData
            );
        }

        // Get created vehicules
        $vehicule1 = Vehicule::first();
        $vehicule2 = Vehicule::skip(1)->first();

        // Create sample reservations
        $reservations = [
            [
                'numero_reservation' => 'RES-001-2024',
                'client_id' => $client1->id,
                'vehicule_id' => $vehicule1->id,
                'agence_id' => $agence1->id,
                'date_debut' => Carbon::now()->addDays(1),
                'date_fin' => Carbon::now()->addDays(3),
                'lieu_depart' => 'Agence Centrale',
                'lieu_retour' => 'Agence Centrale',
                'nombre_passagers' => 2,
                'prix_total' => 135.00,
                'caution' => 200.00,
                'statut' => 'confirmee',
                'notes' => 'Réservation pour weekend',
            ],
            [
                'numero_reservation' => 'RES-002-2024',
                'client_id' => $client2->id,
                'vehicule_id' => $vehicule2->id,
                'agence_id' => $agence2->id,
                'date_debut' => Carbon::now()->addDays(5),
                'date_fin' => Carbon::now()->addDays(7),
                'lieu_depart' => 'Agence Aéroport',
                'lieu_retour' => 'Agence Aéroport',
                'nombre_passagers' => 1,
                'prix_total' => 165.00,
                'caution' => 250.00,
                'statut' => 'en_attente',
                'notes' => 'Réservation pour voyage d\'affaires',
            ],
        ];

        foreach ($reservations as $reservationData) {
            $reservationData['tenant_id'] = $tenant->id;
            Reservation::updateOrCreate(
                ['numero_reservation' => $reservationData['numero_reservation']],
                $reservationData
            );
        }

        // Create sample assurances
        $assurances = [
            [
                'vehicule_id' => $vehicule1->id,
                'numero_assurance' => 'ASS-001-2024',
                'numero_de_police' => 'POL123456',
                'date' => '2023-01-01',
                'date_prochaine' => '2024-12-31',
                'date_reglement' => '2023-01-15',
                'periode' => 'Annuelle',
                'prix' => 800.00,
                'description' => 'Assurance complète',
            ],
            [
                'vehicule_id' => $vehicule2->id,
                'numero_assurance' => 'ASS-002-2024',
                'numero_de_police' => 'POL789012',
                'date' => '2023-02-01',
                'date_prochaine' => '2024-01-31',
                'date_reglement' => '2023-02-15',
                'periode' => 'Annuelle',
                'prix' => 750.00,
                'description' => 'Assurance standard',
            ],
        ];

        foreach ($assurances as $assuranceData) {
            Assurance::create($assuranceData);
        }

        // Create sample charges
        $charges = [
            [
                'designation' => 'Maintenance véhicule',
                'date' => Carbon::now()->subDays(10),
                'montant' => 150.00,
                'description' => 'Vidange et révision',
            ],
            [
                'designation' => 'Assurance mensuelle',
                'date' => Carbon::now()->subDays(5),
                'montant' => 200.00,
                'description' => 'Paiement assurance AXA',
            ],
        ];

        foreach ($charges as $chargeData) {
            $chargeData['tenant_id'] = $tenant->id;
            Charge::create($chargeData);
        }

        // Create sample notifications
        $notifications = [
            [
                'title' => 'Nouvelle réservation',
                'content' => 'Une nouvelle réservation a été créée pour le véhicule AB-123-CD',
                'type' => 'info',
                'read' => false,
            ],
            [
                'title' => 'Maintenance prévue',
                'content' => 'Le véhicule EF-456-GH nécessite une maintenance',
                'type' => 'warning',
                'read' => false,
            ],
        ];

        foreach ($notifications as $notificationData) {
            $notificationData['tenant_id'] = $tenant->id;
            Notification::create($notificationData);
        }
    }
} 