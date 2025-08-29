<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agences';

    protected $fillable = [
        'tenant_id',
        'logo',
        'nom_agence',
        'adresse',
        'ville',
        'rc',
        'patente',
        'IF',
        'n_cnss',
        'ICE',
        'n_compte_bancaire',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenant that owns the agency.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the users for the agency.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the vehicles for the agency.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    /**
     * Get the reservations for the agency.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the contracts for the agency.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Get the clients for the agency.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Scope a query to only include active agencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive agencies.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to filter by tenant.
     */
    public function scopeTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-agency-logo.png');
    }

    /**
     * Check if agency is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->adresse, $this->ville]);
        return implode(', ', $parts);
    }
}
