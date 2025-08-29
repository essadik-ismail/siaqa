<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agence extends Model
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

    /**
     * Get the tenant that owns the agency.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vehicles for the agency.
     */
    public function vehicules(): HasMany
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
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Get the charges for the agency.
     */
    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    /**
     * Get the agency name.
     */
    public function getNomAttribute(): string
    {
        return $this->nom_agence;
    }

    /**
     * Get the agency address.
     */
    public function getAdresseAttribute(): string
    {
        return $this->adresse ?? '';
    }
}
