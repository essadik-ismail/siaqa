<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetourContrat extends Model
{
    protected $fillable = [
        'contrat_id',
        'date_retour',
        'kilometrage_retour',
        'etat_vehicule',
        'observations',
        'statut',
        'tenant_id'
    ];

    protected $casts = [
        'date_retour' => 'datetime',
        'kilometrage_retour' => 'integer'
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeCompleted($query)
    {
        return $query->where('statut', 'terminé');
    }
}
