<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    protected $fillable = [
        'tenant_id',
        'vehicule_id',
        'designation',
        'date',
        'montant',
        'statut',
        'description',
        'fichier'
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2'
    ];

    /**
     * Get the vehicle that owns the charge.
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Get the tenant that owns the charge.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the agence that owns the charge.
     */
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }
}
