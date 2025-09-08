<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicules';

    protected $fillable = [
        'tenant_id',
        'agence_id',
        'marque_id',
        'name',
        'immatriculation',
        'statut',
        'is_active',
        'landing_display',
        'landing_order',
        'type_carburant',
        'nombre_cylindre',
        'nbr_place',
        'reference',
        'serie',
        'fournisseur',
        'numero_facture',
        'prix_achat',
        'prix_location_jour',
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
        'is_active' => 'boolean',
        'landing_display' => 'boolean',
        'landing_order' => 'integer',
        'nombre_cylindre' => 'integer',
        'nbr_place' => 'integer',
        'prix_achat' => 'decimal:2',
        'prix_location_jour' => 'decimal:2',
        'kilometrage_actuel' => 'integer',
        'images' => 'array',
    ];

    /**
     * Get the daily rental price.
     */
    public function getPrixJourAttribute()
    {
        return $this->prix_location_jour;
    }

    /**
     * Get the vehicle model name.
     */
    public function getModeleAttribute()
    {
        return $this->name;
    }

    /**
     * Get the vehicle capacity.
     */
    public function getCapaciteAttribute()
    {
        return $this->nbr_place;
    }

    /**
     * Get the vehicle mileage.
     */
    public function getKilometrageAttribute()
    {
        return $this->kilometrage_actuel;
    }

    /**
     * Get the vehicle year.
     */
    public function getAnneeAttribute()
    {
        // Return current year as default since annee column doesn't exist
        return date('Y');
    }

    /**
     * Get the vehicle fuel type.
     */
    public function getCarburantAttribute()
    {
        return $this->type_carburant;
    }

    /**
     * Get the vehicle transmission.
     */
    public function getTransmissionAttribute()
    {
        // Return default value since transmission column doesn't exist
        return 'Manual';
    }

    /**
     * Get the vehicle power.
     */
    public function getPuissanceAttribute()
    {
        // Return default value since puissance column doesn't exist
        return 120;
    }

    /**
     * Get the tenant that owns the vehicle.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the brand that owns the vehicle.
     */
    public function marque(): BelongsTo
    {
        return $this->belongsTo(Marque::class);
    }

    /**
     * Get the agency that owns the vehicle.
     */
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Get the reservations for the vehicle.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the contracts for the vehicle.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Get the insurance for the vehicle.
     */
    public function assurances(): HasMany
    {
        return $this->hasMany(Assurance::class);
    }

    /**
     * Get the oil changes for the vehicle.
     */
    public function vidanges(): HasMany
    {
        return $this->hasMany(Vidange::class);
    }

    /**
     * Get the inspections for the vehicle.
     */
    public function visites(): HasMany
    {
        return $this->hasMany(Visite::class);
    }

    /**
     * Get the maintenance interventions for the vehicle.
     */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    /**
     * Get the charges for the vehicle.
     */
    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    /**
     * Scope a query to only include available vehicles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('statut', 'disponible');
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('statut', '!=', 'hors_service');
    }

    /**
     * Scope a query to only include vehicles that should be displayed on landing page.
     */
    public function scopeLandingDisplay($query)
    {
        return $query->where('landing_display', true)
                    ->where('is_active', true)
                    ->where('statut', 'disponible')
                    ->orderBy('landing_order', 'asc');
    }

    /**
     * Check if vehicle is available.
     */
    public function isAvailable(): bool
    {
        return $this->statut === 'disponible';
    }

    /**
     * Check if vehicle is rented.
     */
    public function isRented(): bool
    {
        return $this->statut === 'loué';
    }

    /**
     * Check if vehicle is in maintenance.
     */
    public function isInMaintenance(): bool
    {
        return $this->statut === 'maintenance';
    }

    /**
     * Update vehicle status.
     */
    public function updateStatus(string $status): void
    {
        $this->update(['statut' => $status]);
    }

    /**
     * Toggle vehicle status.
     */
    public function toggleStatus(): void
    {
        $statuses = ['disponible', 'loué', 'maintenance', 'hors_service'];
        $currentIndex = array_search($this->statut, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $this->update(['statut' => $statuses[$nextIndex]]);
    }

    /**
     * Get the vehicle's image URL with fallback to default images.
     */
    public function getImageUrlAttribute(): string
    {
        // If vehicle has a custom image and the file exists, return it
        if ($this->image && !empty($this->image)) {
            $exists = Storage::disk('public')->exists($this->image);
            \Log::info('Vehicle image check', [
                'vehicle_id' => $this->id,
                'image_path' => $this->image,
                'storage_exists' => $exists,
                'full_path' => storage_path('app/public/' . $this->image)
            ]);
            
            if ($exists) {
                return asset('storage/' . $this->image);
            }
        }

        // Return the default car image
        return asset('assets/images/default-car.png');
    }

    /**
     * Get a random default car image.
     */
    public static function getRandomDefaultImage(): string
    {
        $defaultImages = [
            'car-1.jpg', 'car-2.jpg', 'car-3.jpg', 'car-4.jpg', 'car-5.jpg', 'car-6.jpg'
        ];
        
        return asset('app/Rent-Car2/assets/images/' . $defaultImages[array_rand($defaultImages)]);
    }

}
