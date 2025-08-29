<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assurance extends Model
{
    use HasFactory;

    protected $table = 'assurances';

    protected $fillable = [
        'vehicule_id',
        'compagnie',
        'numero_police',
        'date_debut',
        'date_fin',
        'montant',
        'type_couverture',
        'statut',
        'notes',
        'tenant_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the insurance.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vehicle that owns the insurance.
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Scope a query to only include active insurance.
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope a query to only include expiring insurance.
     */
    public function scopeExpiring($query)
    {
        return $query->where('date_fin', '<=', now()->addDays(30));
    }

    /**
     * Check if insurance is active.
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Check if insurance is expiring soon.
     */
    public function isExpiringSoon(): bool
    {
        return $this->date_fin->diffInDays(now()) <= 30;
    }

    /**
     * Renew the insurance.
     */
    public function renew(): void
    {
        $this->update(['statut' => 'renouvelé']);
    }
}
