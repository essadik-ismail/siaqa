<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marque extends Model
{
    use HasFactory;

    protected $table = 'marques';

    protected $fillable = [
        'tenant_id',
        'marque',
        'image',
        'is_active',
    ];

    /**
     * Get the tenant that owns the marque.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vehicules for the marque.
     */
    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    /**
     * Get the marque name.
     */
    public function getNomAttribute(): string
    {
        return $this->marque;
    }
}
