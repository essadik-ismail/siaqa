<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'client_id',
        'vehicule_id',
        'agence_id',
        'tenant_id',
        'numero_reservation',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'lieu_depart',
        'lieu_retour',
        'nombre_passagers',
        'options',
        'prix_total',
        'caution',
        'statut',
        'notes',
        'motif_annulation',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
        'nombre_passagers' => 'integer',
        'options' => 'array',
        'prix_total' => 'decimal:2',
        'caution' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the reservation.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the client that owns the reservation.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the vehicle that owns the reservation.
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Get the agency that owns the reservation.
     */
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Get the contract associated with the reservation.
     */
    public function contrat(): HasOne
    {
        return $this->hasOne(Contrat::class);
    }

    /**
     * Scope a query to only include confirmed reservations.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('statut', 'confirmée');
    }

    /**
     * Scope a query to only include pending reservations.
     */
    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Check if reservation is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->statut === 'confirmée';
    }

    /**
     * Check if reservation is pending.
     */
    public function isPending(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Confirm the reservation.
     */
    public function confirm(): void
    {
        $this->update(['statut' => 'confirmée']);
    }

    /**
     * Cancel the reservation.
     */
    public function cancel(): void
    {
        $this->update(['statut' => 'annulée']);
    }
}
