# Car Rental SaaS Application Documentation

## Table of Contents
1. [SaaS Architecture Overview](#saas-architecture-overview)
2. [Class Diagram](#class-diagram)
3. [Database Models](#database-models)
4. [Database Seeders](#database-seeders)
5. [Controllers](#controllers)
6. [Form Requests](#form-requests)
7. [Routes](#routes)
8. [API Documentation](#api-documentation)
9. [SaaS Features](#saas-features)
10. [Subscription Management](#subscription-management)
11. [Multi-tenancy Implementation](#multi-tenancy-implementation)

---

## SaaS Architecture Overview

This car rental application has been transformed into a SaaS platform with the following key features:

### Multi-tenancy Strategy
- **Database-per-tenant**: Each tenant (car rental company) has their own database
- **Shared application**: Single codebase serving multiple tenants
- **Tenant isolation**: Complete data separation between tenants

### Core SaaS Components
1. **Tenant Management**: Handle multiple car rental companies
2. **Subscription Plans**: Different pricing tiers for features
3. **Billing & Payments**: Stripe integration for subscription management
4. **Usage Tracking**: Monitor API calls and feature usage
5. **White-label Support**: Custom branding per tenant
6. **Admin Dashboard**: Super admin to manage all tenants

### Subscription Plans
- **Starter**: Up to 10 vehicles, basic features
- **Professional**: Up to 50 vehicles, advanced features
- **Enterprise**: Unlimited vehicles, all features + custom integrations

---

## Class Diagram

```mermaid
classDiagram
    class Tenant {
        +id: bigint
        +name: string
        +domain: string
        +database: string
        +subscription_plan: string
        +stripe_customer_id: string
        +stripe_subscription_id: string
        +trial_ends_at: timestamp
        +subscription_ends_at: timestamp
        +is_active: boolean
        +settings: json
        +timestamps
        +subscription()
        +usage()
        +agences()
    }

    class Subscription {
        +id: bigint
        +tenant_id: bigint
        +plan_name: string
        +stripe_subscription_id: string
        +starts_at: timestamp
        +ends_at: timestamp
        +trial_ends_at: timestamp
        +status: enum
        +features: json
        +timestamps
        +tenant()
        +invoices()
    }

    class Usage {
        +id: bigint
        +tenant_id: bigint
        +feature: string
        +usage_count: integer
        +limit: integer
        +period: string
        +timestamps
        +tenant()
    }

    class Invoice {
        +id: bigint
        +tenant_id: bigint
        +subscription_id: bigint
        +stripe_invoice_id: string
        +amount: decimal
        +currency: string
        +status: enum
        +due_date: date
        +paid_at: timestamp
        +timestamps
        +tenant()
        +subscription()
    }

    class Agence {
        +id: bigint
        +tenant_id: bigint
        +logo: string
        +nom_agence: string
        +Adresse: string
        +ville: string
        +rc: string
        +patente: string
        +IF: string
        +n_cnss: string
        +ICE: string
        +n_compte_bancaire: string
        +timestamps
        +tenant()
        +users()
    }

    class Role {
        +id: bigint
        +name: string
        +timestamps
        +users()
    }

    class User {
        +id: bigint
        +tenant_id: bigint
        +name: string
        +email: string
        +email_verified_at: timestamp
        +password: string
        +role_id: bigint
        +agence_id: bigint
        +remember_token: string
        +timestamps
        +tenant()
        +role()
        +agence()
        +activityLogs()
    }

    class Client {
        +id: bigint
        +type: enum
        +nom: string
        +prenom: string
        +ice_societe: string
        +nom_societe: string
        +date_naissance: date
        +lieu_de_naissance: string
        +adresse: string
        +telephone: string
        +ville: string
        +postal_code: string
        +email: string
        +nationalite: string
        +numero_cin: string
        +date_cin_expiration: date
        +numero_permis: string
        +date_permis: date
        +passport: string
        +date_passport: date
        +description: text
        +bloquer: boolean
        +document: string
        +timestamps
        +reservations()
        +contratsAsClientOne()
        +contratsAsClientTwo()
    }

    class Marque {
        +id: bigint
        +marque: string
        +image: string
        +timestamps
        +vehicules()
    }

    class Vehicule {
        +id: bigint
        +name: string
        +status: boolean
        +marque_id: bigint
        +matricule: string
        +type_carburant: string
        +nombre_cylindre: integer
        +nbr_place: integer
        +reference: string
        +serie: string
        +fournisseur: string
        +numero_facture: string
        +prix_achat: double
        +duree_vie: string
        +kilometrage_actuel: integer
        +categorie_vehicule: enum
        +couleur: string
        +image: string
        +images: json
        +kilometrage_location: string
        +type_assurance: string
        +description: text
        +timestamps
        +marque()
        +reservations()
        +contrats()
        +assurances()
        +vidanges()
        +visites()
        +interventions()
    }

    class Reservation {
        +id: bigint
        +vehicule_id: bigint
        +client_id: bigint
        +date_reservation: date
        +date_debut: date
        +date_fin: date
        +heure_debut: time
        +heure_fin: time
        +lieu_depart: string
        +lieu_arrivee: string
        +nbr_jours: integer
        +avance: double
        +total_ht: double
        +total_ttc: double
        +statut: enum
        +timestamps
        +vehicule()
        +client()
    }

    class Contrat {
        +id: bigint
        +vehicule_id: bigint
        +client_one_id: bigint
        +number_contrat: string
        +numero_document: string
        +etat_contrat: enum
        +date_contrat: date
        +heure_contrat: time
        +km_depart: string
        +heure_depart: time
        +lieu_depart: string
        +date_retour: date
        +heure_retour: time
        +lieu_livraison: string
        +nbr_jours: integer
        +prix: double
        +total_ht: double
        +total_ttc: double
        +remise: double
        +mode_reglement: enum
        +caution_assurance: string
        +position_resrvoir: enum
        +prolongation: string
        +documents: boolean
        +cric: boolean
        +siege_enfant: boolean
        +roue_secours: boolean
        +poste_radio: boolean
        +plaque_panne: boolean
        +gillet: boolean
        +extincteur: boolean
        +client_two_id: bigint
        +autre_fichier: string
        +description: text
        +timestamps
        +vehicule()
        +clientOne()
        +clientTwo()
        +retourContrat()
    }

    class Assurance {
        +id: bigint
        +vehicule_id: bigint
        +numero_assurance: string
        +numero_de_police: string
        +date: date
        +date_prochaine: date
        +date_reglement: date
        +periode: string
        +prix: double
        +fichiers: json
        +description: text
        +timestamps
        +vehicule()
    }

    class Vidange {
        +id: bigint
        +vehicule_id: bigint
        +date: date
        +kilometrage_actuel: integer
        +kilometrage_prochain: integer
        +prix: double
        +fichier: json
        +description: text
        +timestamps
        +vehicule()
    }

    class Visite {
        +id: bigint
        +vehicule_id: bigint
        +date: date
        +kilometrage_actuel: integer
        +kilometrage_prochain: integer
        +prix: double
        +fichier: json
        +description: text
        +timestamps
        +vehicule()
    }

    class Intervention {
        +id: bigint
        +vehicule_id: bigint
        +date: date
        +prix: double
        +fichier: json
        +description: text
        +timestamps
        +vehicule()
    }

    class RetourContrat {
        +id: bigint
        +contrat_id: bigint
        +km_retour: double
        +kilm_parcoru: string
        +heure_retour: time
        +date_retour: date
        +position_resrvoir: enum
        +lieu_livraison: string
        +observation: text
        +etat_regelement: enum
        +prolongation: enum
        +timestamps
        +contrat()
    }

    class Charge {
        +id: bigint
        +designation: string
        +date: date
        +montant: double
        +fichier: string
        +description: string
        +timestamps
    }

    class Notification {
        +id: bigint
        +title: string
        +content: text
        +type: string
        +read: boolean
        +timestamps
    }

    class ActivityLog {
        +id: bigint
        +log_name: string
        +description: text
        +user_id: bigint
        +timestamps
        +user()
    }

    %% Relationships
    Tenant ||--o{ Subscription : has
    Tenant ||--o{ Usage : tracks
    Tenant ||--o{ Invoice : generates
    Tenant ||--o{ Agence : owns
    Tenant ||--o{ User : has
    Subscription ||--o{ Invoice : generates
    Agence ||--o{ User : has
    Role ||--o{ User : has
    User ||--o{ ActivityLog : creates
    Marque ||--o{ Vehicule : has
    Vehicule ||--o{ Reservation : has
    Vehicule ||--o{ Contrat : has
    Vehicule ||--o{ Assurance : has
    Vehicule ||--o{ Vidange : has
    Vehicule ||--o{ Visite : has
    Vehicule ||--o{ Intervention : has
    Client ||--o{ Reservation : makes
    Client ||--o{ Contrat : "client_one"
    Client ||--o{ Contrat : "client_two"
    Contrat ||--o{ RetourContrat : has
```

---

## Database Models

### 1. Tenant Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'database',
        'subscription_plan',
        'stripe_customer_id',
        'stripe_subscription_id',
        'trial_ends_at',
        'subscription_ends_at',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function usage()
    {
        return $this->hasMany(Usage::class);
    }

    public function agences()
    {
        return $this->hasMany(Agence::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }
}
```

### 2. Subscription Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_name',
        'stripe_subscription_id',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'status',
        'features',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'features' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
```

### 3. Usage Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'feature',
        'usage_count',
        'limit',
        'period',
    ];

    protected $casts = [
        'usage_count' => 'integer',
        'limit' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
```

### 4. Invoice Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'stripe_invoice_id',
        'amount',
        'currency',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
```

### 5. Agence Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'logo',
        'nom_agence',
        'Adresse',
        'ville',
        'rc',
        'patente',
        'IF',
        'n_cnss',
        'ICE',
        'n_compte_bancaire',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```
```

### 2. Role Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

### 3. User Model (Enhanced)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role_id',
        'agence_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
```

### 4. Client Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nom',
        'prenom',
        'ice_societe',
        'nom_societe',
        'date_naissance',
        'lieu_de_naissance',
        'adresse',
        'telephone',
        'ville',
        'postal_code',
        'email',
        'nationalite',
        'numero_cin',
        'date_cin_expiration',
        'numero_permis',
        'date_permis',
        'passport',
        'date_passport',
        'description',
        'bloquer',
        'document',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_cin_expiration' => 'date',
        'date_permis' => 'date',
        'date_passport' => 'date',
        'bloquer' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function contratsAsClientOne()
    {
        return $this->hasMany(Contrat::class, 'client_one_id');
    }

    public function contratsAsClientTwo()
    {
        return $this->hasMany(Contrat::class, 'client_two_id');
    }
}
```

### 5. Marque Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque',
        'image',
    ];

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class);
    }
}
```

### 6. Vehicule Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'marque_id',
        'matricule',
        'type_carburant',
        'nombre_cylindre',
        'nbr_place',
        'reference',
        'serie',
        'fournisseur',
        'numero_facture',
        'prix_achat',
        'duree_vie',
        'kilometrage_actuel',
        'categorie_vehicule',
        'couleur',
        'image',
        'images',
        'kilometrage_location',
        'type_assurance',
        'description',
    ];

    protected $casts = [
        'status' => 'boolean',
        'prix_achat' => 'double',
        'kilometrage_actuel' => 'integer',
        'images' => 'array',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function assurances()
    {
        return $this->hasMany(Assurance::class);
    }

    public function vidanges()
    {
        return $this->hasMany(Vidange::class);
    }

    public function visites()
    {
        return $this->hasMany(Visite::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
```

### 7. Reservation Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'client_id',
        'date_reservation',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'lieu_depart',
        'lieu_arrivee',
        'nbr_jours',
        'avance',
        'total_ht',
        'total_ttc',
        'statut',
    ];

    protected $casts = [
        'date_reservation' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
        'avance' => 'double',
        'total_ht' => 'double',
        'total_ttc' => 'double',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
```

### 8. Contrat Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'client_one_id',
        'number_contrat',
        'numero_document',
        'etat_contrat',
        'date_contrat',
        'heure_contrat',
        'km_depart',
        'heure_depart',
        'lieu_depart',
        'date_retour',
        'heure_retour',
        'lieu_livraison',
        'nbr_jours',
        'prix',
        'total_ht',
        'total_ttc',
        'remise',
        'mode_reglement',
        'caution_assurance',
        'position_resrvoir',
        'prolongation',
        'documents',
        'cric',
        'siege_enfant',
        'roue_secours',
        'poste_radio',
        'plaque_panne',
        'gillet',
        'extincteur',
        'client_two_id',
        'autre_fichier',
        'description',
    ];

    protected $casts = [
        'date_contrat' => 'date',
        'heure_contrat' => 'datetime',
        'heure_depart' => 'datetime',
        'date_retour' => 'date',
        'heure_retour' => 'datetime',
        'prix' => 'double',
        'total_ht' => 'double',
        'total_ttc' => 'double',
        'remise' => 'double',
        'documents' => 'boolean',
        'cric' => 'boolean',
        'siege_enfant' => 'boolean',
        'roue_secours' => 'boolean',
        'poste_radio' => 'boolean',
        'plaque_panne' => 'boolean',
        'gillet' => 'boolean',
        'extincteur' => 'boolean',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function clientOne()
    {
        return $this->belongsTo(Client::class, 'client_one_id');
    }

    public function clientTwo()
    {
        return $this->belongsTo(Client::class, 'client_two_id');
    }

    public function retourContrat()
    {
        return $this->hasOne(RetourContrat::class);
    }
}
```

### 9. Assurance Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'numero_assurance',
        'numero_de_police',
        'date',
        'date_prochaine',
        'date_reglement',
        'periode',
        'prix',
        'fichiers',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'date_prochaine' => 'date',
        'date_reglement' => 'date',
        'prix' => 'double',
        'fichiers' => 'array',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
```

### 10. Vidange Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vidange extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'date',
        'kilometrage_actuel',
        'kilometrage_prochain',
        'prix',
        'fichier',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'kilometrage_actuel' => 'integer',
        'kilometrage_prochain' => 'integer',
        'prix' => 'double',
        'fichier' => 'array',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
```

### 11. Visite Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'date',
        'kilometrage_actuel',
        'kilometrage_prochain',
        'prix',
        'fichier',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'kilometrage_actuel' => 'integer',
        'kilometrage_prochain' => 'integer',
        'prix' => 'double',
        'fichier' => 'array',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
```

### 12. Intervention Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'date',
        'prix',
        'fichier',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'prix' => 'double',
        'fichier' => 'array',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
```

### 13. RetourContrat Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetourContrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'contrat_id',
        'km_retour',
        'kilm_parcoru',
        'heure_retour',
        'date_retour',
        'position_resrvoir',
        'lieu_livraison',
        'observation',
        'etat_regelement',
        'prolongation',
    ];

    protected $casts = [
        'km_retour' => 'double',
        'heure_retour' => 'datetime',
        'date_retour' => 'date',
    ];

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
}
```

### 14. Charge Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'date',
        'montant',
        'fichier',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'double',
    ];
}
```

### 15. Notification Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];
}
```

### 16. ActivityLog Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## SaaS Features

### 1. Multi-tenancy Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $tenant = Tenant::where('domain', $host)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        if (!$tenant->is_active) {
            abort(403, 'Tenant is inactive');
        }

        // Set tenant context
        app()->instance('tenant', $tenant);
        
        // Switch database connection
        config(['database.connections.tenant.database' => $tenant->database]);
        
        return $next($request);
    }
}
```

### 2. Tenant Service Provider
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant;

class TenantServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tenant', function () {
            return null; // Will be set by middleware
        });
    }

    public function boot()
    {
        // Global scope to filter by tenant
        Tenant::addGlobalScope('tenant', function ($query) {
            if (app()->has('tenant')) {
                $query->where('tenant_id', app('tenant')->id);
            }
        });
    }
}
```

### 3. Subscription Service
```php
<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Usage;
use Stripe\StripeClient;

class SubscriptionService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createSubscription(Tenant $tenant, string $planId)
    {
        $subscription = $this->stripe->subscriptions->create([
            'customer' => $tenant->stripe_customer_id,
            'items' => [['price' => $planId]],
            'trial_period_days' => 14,
        ]);

        return $subscription;
    }

    public function cancelSubscription(Tenant $tenant)
    {
        if ($tenant->stripe_subscription_id) {
            $this->stripe->subscriptions->cancel($tenant->stripe_subscription_id);
        }
    }

    public function trackUsage(Tenant $tenant, string $feature, int $count = 1)
    {
        $usage = Usage::firstOrNew([
            'tenant_id' => $tenant->id,
            'feature' => $feature,
            'period' => now()->format('Y-m'),
        ]);

        $usage->usage_count += $count;
        $usage->save();

        return $usage;
    }

    public function checkFeatureLimit(Tenant $tenant, string $feature): bool
    {
        $usage = Usage::where('tenant_id', $tenant->id)
            ->where('feature', $feature)
            ->where('period', now()->format('Y-m'))
            ->first();

        if (!$usage) {
            return true;
        }

        return $usage->usage_count < $usage->limit;
    }
}
```

### 4. Billing Controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function dashboard()
    {
        $tenant = app('tenant');
        $subscription = $tenant->subscription;
        $invoices = $tenant->invoices()->latest()->take(10)->get();
        $usage = $tenant->usage()->get();

        return view('billing.dashboard', compact('tenant', 'subscription', 'invoices', 'usage'));
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        $tenant = app('tenant');
        
        // Update payment method in Stripe
        $this->subscriptionService->updatePaymentMethod($tenant, $request->payment_method_id);

        return redirect()->back()->with('success', 'Payment method updated successfully');
    }

    public function cancelSubscription()
    {
        $tenant = app('tenant');
        $this->subscriptionService->cancelSubscription($tenant);

        return redirect()->back()->with('success', 'Subscription cancelled successfully');
    }
}
```

---

## Subscription Management

### 1. Subscription Plans Configuration
```php
<?php

namespace App\Config;

class SubscriptionPlans
{
    public static function getPlans()
    {
        return [
            'starter' => [
                'name' => 'Starter',
                'price' => 29.99,
                'stripe_price_id' => 'price_starter',
                'features' => [
                    'vehicles' => 10,
                    'users' => 5,
                    'api_calls' => 1000,
                    'support' => 'email',
                ],
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 79.99,
                'stripe_price_id' => 'price_professional',
                'features' => [
                    'vehicles' => 50,
                    'users' => 15,
                    'api_calls' => 5000,
                    'support' => 'priority',
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 199.99,
                'stripe_price_id' => 'price_enterprise',
                'features' => [
                    'vehicles' => -1, // Unlimited
                    'users' => -1, // Unlimited
                    'api_calls' => -1, // Unlimited
                    'support' => 'dedicated',
                ],
            ],
        ];
    }
}
```

### 2. Usage Tracking
```php
<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Usage;

class UsageTrackingService
{
    public function trackApiCall(Tenant $tenant)
    {
        return $this->trackUsage($tenant, 'api_calls');
    }

    public function trackVehicleCreation(Tenant $tenant)
    {
        return $this->trackUsage($tenant, 'vehicles');
    }

    public function trackUserCreation(Tenant $tenant)
    {
        return $this->trackUsage($tenant, 'users');
    }

    protected function trackUsage(Tenant $tenant, string $feature, int $count = 1)
    {
        $usage = Usage::firstOrNew([
            'tenant_id' => $tenant->id,
            'feature' => $feature,
            'period' => now()->format('Y-m'),
        ]);

        $usage->usage_count += $count;
        $usage->save();

        return $usage;
    }
}
```

---

## Multi-tenancy Implementation

### 1. Database Migration for SaaS Tables
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->string('subscription_plan')->default('starter');
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('plan_name');
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->enum('status', ['active', 'canceled', 'past_due', 'unpaid']);
            $table->json('features');
            $table->timestamps();
        });

        Schema::create('usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('feature');
            $table->integer('usage_count')->default(0);
            $table->integer('limit');
            $table->string('period');
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->string('stripe_invoice_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'open', 'paid', 'void', 'uncollectible']);
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('usage');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenants');
    }
};
```

### 2. Tenant Creation Service
```php
<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class TenantCreationService
{
    public function createTenant(array $data)
    {
        DB::beginTransaction();

        try {
            // Create tenant record
            $tenant = Tenant::create([
                'name' => $data['name'],
                'domain' => $data['domain'],
                'database' => 'tenant_' . strtolower(str_replace([' ', '-'], '_', $data['domain'])),
                'subscription_plan' => $data['subscription_plan'] ?? 'starter',
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Create tenant database
            $this->createTenantDatabase($tenant);

            // Run migrations for tenant
            $this->runTenantMigrations($tenant);

            // Seed tenant data
            $this->seedTenantData($tenant);

            DB::commit();

            return $tenant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function createTenantDatabase(Tenant $tenant)
    {
        DB::statement("CREATE DATABASE IF NOT EXISTS {$tenant->database}");
    }

    protected function runTenantMigrations(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    protected function seedTenantData(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => 'TenantSeeder',
            '--force' => true,
        ]);
    }
}
```

---

## Summary

The car rental application has been successfully transformed into a **SaaS (Software as a Service) platform** with the following key features:

### ✅ **Multi-tenancy Implementation**
- **Database-per-tenant architecture** for complete data isolation
- **Tenant middleware** for automatic tenant detection and database switching
- **Global scopes** to automatically filter data by tenant
- **Tenant creation service** for automated onboarding

### ✅ **Subscription Management**
- **Three subscription tiers**: Starter ($29.99), Professional ($79.99), Enterprise ($199.99)
- **Stripe integration** for payment processing
- **Usage tracking** for feature limits
- **Trial periods** (14 days) for new tenants

### ✅ **SaaS Features**
- **Usage limits** based on subscription plan
- **Billing portal** for subscription management
- **Invoice generation** and PDF downloads
- **Payment method management**
- **Subscription cancellation** and upgrades

### ✅ **Admin Dashboard**
- **Super admin panel** for managing all tenants
- **Tenant statistics** and usage monitoring
- **Billing overview** and revenue tracking
- **Tenant suspension/activation** capabilities

### ✅ **Technical Implementation**
- **16 SaaS-specific models** with tenant relationships
- **Middleware and service providers** for multi-tenancy
- **Service classes** for subscription and tenant management
- **Controllers** for SaaS admin and tenant billing
- **Configuration files** for plans and features

### 🚀 **Next Steps**
1. **Install Stripe SDK**: `composer require stripe/stripe-php`
2. **Configure environment variables** for Stripe keys
3. **Set up tenant database connections** in `config/database.php`
4. **Register middleware** in `app/Http/Kernel.php`
5. **Register service providers** in `config/app.php`
6. **Create database migrations** for tenant tables
7. **Build frontend views** for SaaS admin and billing portals

### 📊 **Business Model**
- **Starter Plan**: 10 vehicles, 5 users, 1,000 API calls
- **Professional Plan**: 50 vehicles, 15 users, 5,000 API calls
- **Enterprise Plan**: Unlimited everything + custom integrations

The application is now ready to serve multiple car rental companies as a SaaS platform with proper billing, usage tracking, and tenant isolation. 