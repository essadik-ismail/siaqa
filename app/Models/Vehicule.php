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
        'marque',
        'name',
        'immatriculation',
        'status',
        'is_active',
        'is_training_vehicle',
        'training_type',
        'required_licenses',
        'has_dual_controls',
        'has_automatic_transmission',
        'has_manual_transmission',
        'max_students',
        'hourly_rate',
        'safety_features',
        'last_inspection',
        'next_inspection',
        'requires_maintenance',
        'maintenance_notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_training_vehicle' => 'boolean',
        'has_dual_controls' => 'boolean',
        'has_automatic_transmission' => 'boolean',
        'has_manual_transmission' => 'boolean',
        'requires_maintenance' => 'boolean',
        'landing_display' => 'boolean',
        'landing_order' => 'integer',
        'max_students' => 'integer',
        'hourly_rate' => 'decimal:2',
        'last_inspection' => 'date',
        'next_inspection' => 'date',
        'required_licenses' => 'array',
        'safety_features' => 'array',
        'images' => 'array',
    ];

    /**
     * Get the vehicle model name.
     */
    public function getModeleAttribute()
    {
        return $this->name;
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
     * Get the lessons for the vehicle.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'vehicle_id');
    }

    /**
     * Get the vehicle assignments.
     */
    public function vehicleAssignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class, 'vehicle_id');
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include vehicles that should be displayed on landing page.
     */
    public function scopeLandingDisplay($query)
    {
        return $query->where('landing_display', true)
                    ->where('is_active', true)
                    ->orderBy('landing_order', 'asc');
    }

    /**
     * Check if vehicle is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && !$this->requires_maintenance;
    }

    /**
     * Check if vehicle is in maintenance.
     */
    public function isInMaintenance(): bool
    {
        return $this->requires_maintenance;
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
        
        return asset('appp/Rent-Car2/assets/images/' . $defaultImages[array_rand($defaultImages)]);
    }

}
