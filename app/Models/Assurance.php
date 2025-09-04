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
        'numero_assurance',
        'numero_police',
        'date',
        'date_prochaine',
        'date_reglement',
        'prix',
        'periode',
        'fichiers',
        'description',
        'tenant_id',
    ];

    protected $casts = [
        'date' => 'date',
        'date_prochaine' => 'date',
        'date_reglement' => 'date',
        'prix' => 'decimal:2',
        'fichiers' => 'array',
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
     * Scope a query to only include expiring insurance.
     */
    public function scopeExpiring($query)
    {
        return $query->where('date_prochaine', '<=', now()->addDays(30));
    }

    /**
     * Check if insurance is expiring soon.
     */
    public function isExpiringSoon(): bool
    {
        return $this->date_prochaine->diffInDays(now()) <= 30;
    }

    /**
     * Get the file URLs.
     */
    public function getFileUrlsAttribute(): array
    {
        if ($this->fichiers && is_array($this->fichiers)) {
            return array_map(function ($file) {
                return asset('storage/' . $file);
            }, $this->fichiers);
        }
        return [];
    }

    /**
     * Check if assurance has files.
     */
    public function hasFiles(): bool
    {
        return !empty($this->fichiers);
    }
}
