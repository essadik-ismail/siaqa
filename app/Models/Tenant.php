<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'companyName',
        'address',
        'tel',
        'gsm',
        'email',
        'website',
        'subscription_plan',
        'trial_ends_at',
        'subscription_ends_at',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function usage()
    {
        return $this->hasMany(Usage::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function agence()
    {
        return $this->hasOne(Agence::class);
    }

    public function agences()
    {
        return $this->hasMany(Agence::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class);
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription()
    {
        return $this->subscription && $this->subscription->status === 'active';
    }

    public function canAccessFeature(string $feature): bool
    {
        if ($this->isOnTrial()) {
            return true;
        }

        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $usage = $this->usage()
            ->where('feature', $feature)
            ->where('period', now()->format('Y-m'))
            ->first();

        if (!$usage) {
            return true;
        }

        return $usage->usage_count < $usage->limit;
    }
    
    /**
     * Get tenant status for display
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'suspended';
        }
        
        if ($this->isOnTrial()) {
            return 'trial';
        }
        
        if ($this->hasActiveSubscription()) {
            return 'active';
        }
        
        return 'expired';
    }
    
    /**
     * Get status color for display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'trial' => 'blue',
            'suspended' => 'red',
            'expired' => 'yellow',
            default => 'gray'
        };
    }
} 