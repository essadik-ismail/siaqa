<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contrat extends Model
{
    use HasFactory;

    protected $table = 'contrats';

    protected $fillable = [
        'tenant_id',
        'vehicule_id',
        'reservation_id',
        'client_one_id',
        'client_two_id',
        'number_contrat',
        'numero_document',
        'etat_contrat',
        'statut',
        'date_contrat',
        'date_debut',
        'date_fin',
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
        'montant_total',
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
        'autre_fichier',
        'description',
    ];

    protected $casts = [
        'date_contrat' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_contrat' => 'datetime',
        'heure_depart' => 'datetime',
        'date_retour' => 'date',
        'heure_retour' => 'datetime',
        'prix' => 'decimal:2',
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'remise' => 'decimal:2',
        'documents' => 'boolean',
        'cric' => 'boolean',
        'siege_enfant' => 'boolean',
        'roue_secours' => 'boolean',
        'poste_radio' => 'boolean',
        'plaque_panne' => 'boolean',
        'gillet' => 'boolean',
        'extincteur' => 'boolean',
    ];

    /**
     * Get the tenant that owns the contract.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the primary client that owns the contract.
     */
    public function clientOne(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_one_id');
    }

    /**
     * Get the secondary client that owns the contract.
     */
    public function clientTwo(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_two_id');
    }

    /**
     * Get the primary client (alias for clientOne for backward compatibility).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_one_id');
    }

    /**
     * Get the vehicle that owns the contract.
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Get the agency that owns the contract.
     */
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Get the reservation that owns the contract.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the contract return for this contract.
     */
    public function retourContrat(): HasOne
    {
        return $this->hasOne(RetourContrat::class);
    }

    /**
     * Scope a query to only include active contracts.
     */
    public function scopeActive($query)
    {
        return $query->where('etat_contrat', 'en cours');
    }

    /**
     * Scope a query to only include finished contracts.
     */
    public function scopeFinished($query)
    {
        return $query->where('etat_contrat', 'termine');
    }

    /**
     * Check if contract is finished.
     */
    public function isFinished(): bool
    {
        return $this->etat_contrat === 'termine';
    }
}
