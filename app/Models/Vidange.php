<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vidange extends Model
{
    use HasFactory;

    protected $table = 'vidanges';

    protected $fillable = [
        'vehicule_id',
        'date_prevue',
        'kilometrage_actuel',
        'kilometrage_prochaine',
        'type_huile',
        'quantite_huile',
        'filtre_huile',
        'filtre_air',
        'filtre_carburant',
        'cout_estime',
        'statut',
        'notes',
        'tenant_id',
        // Keep old columns for backward compatibility
        'prix',
        'fichier',
        'description',
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'kilometrage_actuel' => 'integer',
        'kilometrage_prochaine' => 'integer',
        'quantite_huile' => 'decimal:2',
        'cout_estime' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the oil change.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vehicle that owns the oil change.
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Scope a query to only include completed oil changes.
     */
    public function scopeCompleted($query)
    {
        return $query->where('statut', 'terminée');
    }

    /**
     * Scope a query to only include due oil changes.
     */
    public function scopeDue($query)
    {
        return $query->where('statut', '!=', 'terminée');
    }

    /**
     * Check if oil change is completed.
     */
    public function isCompleted(): bool
    {
        return $this->statut === 'terminée';
    }

    /**
     * Complete the oil change.
     */
    public function complete(): void
    {
        $this->update(['statut' => 'terminée']);
    }
}
