<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'type',
        'nom',
        'prenom',
        'ice_societe',
        'nom_societe',
        'date_naissance',
        'lieu_de_naissance',
        'adresse',
        'telephone',
        'ville',
        'postal_code',
        'code_postal',
        'pays',
        'email',
        'nationalite',
        'numero_cin',
        'date_cin_expiration',
        'numero_permis',
        'date_permis',
        'date_obtention_permis',
        'passport',
        'date_passport',
        'numero_piece_identite',
        'type_piece_identite',
        'date_expiration_piece',
        'profession',
        'employeur',
        'revenu_mensuel',
        'description',
        'notes',
        'bloquer',
        'is_blacklisted',
        'is_blacklist',
        'motif_blacklist',
        'document',
        'image',
        'images',
        'tenant_id',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_cin_expiration' => 'date',
        'date_permis' => 'date',
        'date_passport' => 'date',
        'date_obtention_permis' => 'date',
        'date_expiration_piece' => 'date',
        'bloquer' => 'boolean',
        'is_blacklisted' => 'boolean',
        'is_blacklist' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the tenant that owns the client.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the reservations for the client.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the contracts for the client.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Check if the client is blacklisted
     */
    public function isBlacklisted(): bool
    {
        return $this->is_blacklisted || $this->is_blacklist;
    }

    /**
     * Get the secondary contracts for the client.
     */
    public function secondaryContrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'client_two_id');
    }

    /**
     * Get the full name of the client.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->type === 'societe') {
            return $this->nom_societe ?? $this->nom;
        }
        return trim($this->prenom . ' ' . $this->nom);
    }

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('bloquer', false);
    }

    /**
     * Scope a query to only include non-blocked clients.
     */
    public function scopeNotBlocked($query)
    {
        return $query->where('bloquer', false);
    }

    /**
     * Check if client is blocked.
     */
    public function isBlocked(): bool
    {
        return $this->bloquer;
    }

    /**
     * Toggle block status.
     */
    public function toggleBlock(): void
    {
        $this->update(['bloquer' => !$this->bloquer]);
    }

    /**
     * Get the main image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        return null;
    }

    /**
     * Get the additional images URLs.
     */
    public function getImagesUrlsAttribute(): array
    {
        if ($this->images && is_array($this->images)) {
            return array_map(function ($image) {
                return Storage::disk('public')->url($image);
            }, $this->images);
        }
        return [];
    }

    /**
     * Get the document URL.
     */
    public function getDocumentUrlAttribute(): ?string
    {
        if ($this->document) {
            return Storage::disk('public')->url($this->document);
        }
        return null;
    }

    /**
     * Check if client has images.
     */
    public function hasImages(): bool
    {
        return !empty($this->images) || !empty($this->image);
    }

    /**
     * Get identity document type label.
     */
    public function getIdentityDocumentTypeLabelAttribute(): string
    {
        $types = [
            'carte_nationale' => 'Carte Nationale',
            'passeport' => 'Passeport',
            'permis_conduire' => 'Permis de Conduire',
            'carte_sejour' => 'Carte de Séjour',
        ];

        return $types[$this->type_piece_identite] ?? $this->type_piece_identite;
    }
}
