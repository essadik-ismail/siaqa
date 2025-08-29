<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Intervention extends Model
{
    protected $fillable = [
        'vehicule_id',
        'type_intervention',
        'description',
        'date_debut',
        'date_fin',
        'cout',
        'statut',
        'technicien',
        'tenant_id'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'cout' => 'decimal:2'
    ];

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeInProgress($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeCompleted($query)
    {
        return $query->where('statut', 'terminé');
    }
}
