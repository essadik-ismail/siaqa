<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'database',
        'subscription_plan',
        'stripe_customer_id',
        'stripe_subscription_id',
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

    public function agences()
    {
        return $this->hasMany(Agence::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
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
} 