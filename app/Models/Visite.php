<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visite extends Model
{
    protected $fillable = [
        'vehicule_id',
        'date_visite',
        'prochaine_visite',
        'type_visite',
        'resultat',
        'observations',
        'statut',
        'tenant_id'
    ];

    protected $casts = [
        'date_visite' => 'date',
        'prochaine_visite' => 'date'
    ];

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeDue($query)
    {
        return $query->where('prochaine_visite', '<=', now()->addDays(30));
    }

    public function scopeCompleted($query)
    {
        return $query->where('statut', 'terminée');
    }

    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }
}
