<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Usage;
use App\Models\Subscription;
use App\Models\Invoice;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    protected $stripe;

    public function __construct()
    {
        try {
            if (class_exists(StripeClient::class) && config('services.stripe.secret')) {
                $this->stripe = new StripeClient(config('services.stripe.secret'));
            } else {
                $this->stripe = null;
                Log::warning('Stripe not configured or StripeClient class not found');
            }
        } catch (\Exception $e) {
            $this->stripe = null;
            Log::warning('Failed to initialize Stripe: ' . $e->getMessage());
        }
    }

    public function createSubscription(Tenant $tenant, string $planId)
    {
        if (!$this->stripe) {
            // Fallback: create local subscription without Stripe
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_name' => $this->getPlanNameFromPriceId($planId),
                'stripe_subscription_id' => null,
                'starts_at' => now(),
                'status' => 'active',
                'features' => $this->getPlanFeatures($planId),
            ]);
            
            Log::info('Created local subscription (Stripe not available) for tenant: ' . $tenant->domain);
            return $subscription;
        }

        try {
            $subscription = $this->stripe->subscriptions->create([
                'customer' => $tenant->stripe_customer_id,
                'items' => [['price' => $planId]],
                'trial_period_days' => 14,
                'metadata' => [
                    'tenant_id' => $tenant->id,
                ],
            ]);

            // Create local subscription record
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_name' => $this->getPlanNameFromPriceId($planId),
                'stripe_subscription_id' => $subscription->id,
                'starts_at' => now(),
                'status' => $subscription->status,
                'features' => $this->getPlanFeatures($planId),
            ]);

            return $subscription;
        } catch (\Exception $e) {
            Log::error('Failed to create subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    public function cancelSubscription(Tenant $tenant)
    {
        // Update local subscription regardless of Stripe availability
        $subscription = $tenant->subscription;
        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
                'ends_at' => now(),
            ]);
        }

        if (!$this->stripe || !$tenant->stripe_subscription_id) {
            Log::info('Cancelled local subscription (Stripe not available) for tenant: ' . $tenant->domain);
            return;
        }

        try {
            $this->stripe->subscriptions->cancel($tenant->stripe_subscription_id);
        } catch (\Exception $e) {
            Log::error('Failed to cancel Stripe subscription: ' . $e->getMessage());
            // Don't throw - local subscription is already cancelled
        }
    }

    public function updatePaymentMethod(Tenant $tenant, string $paymentMethodId)
    {
        if (!$this->stripe || !$tenant->stripe_customer_id) {
            Log::info('Payment method update skipped (Stripe not available) for tenant: ' . $tenant->domain);
            return;
        }

        try {
            $this->stripe->customers->update($tenant->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update payment method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function trackUsage(Tenant $tenant, string $feature, int $count = 1)
    {
        $usage = Usage::firstOrNew([
            'tenant_id' => $tenant->id,
            'feature' => $feature,
            'period' => now()->format('Y-m'),
        ]);

        if (!$usage->exists) {
            $usage->limit = $this->getFeatureLimit($tenant, $feature);
        }

        $usage->usage_count += $count;
        $usage->save();

        return $usage;
    }

    public function checkFeatureLimit(Tenant $tenant, string $feature): bool
    {
        if ($tenant->isOnTrial()) {
            return true;
        }

        $usage = Usage::where('tenant_id', $tenant->id)
            ->where('feature', $feature)
            ->where('period', now()->format('Y-m'))
            ->first();

        if (!$usage) {
            return true;
        }

        return $usage->usage_count < $usage->limit;
    }

    protected function getPlanNameFromPriceId(string $priceId): string
    {
        $plans = [
            'price_starter' => 'starter',
            'price_professional' => 'professional',
            'price_enterprise' => 'enterprise',
        ];

        return $plans[$priceId] ?? 'starter';
    }

    protected function getPlanFeatures(string $priceId): array
    {
        $features = [
            'price_starter' => [
                'vehicles' => 10,
                'users' => 5,
                'api_calls' => 1000,
                'support' => 'email',
            ],
            'price_professional' => [
                'vehicles' => 50,
                'users' => 15,
                'api_calls' => 5000,
                'support' => 'priority',
            ],
            'price_enterprise' => [
                'vehicles' => -1, // Unlimited
                'users' => -1, // Unlimited
                'api_calls' => -1, // Unlimited
                'support' => 'dedicated',
            ],
        ];

        return $features[$priceId] ?? $features['price_starter'];
    }

    protected function getFeatureLimit(Tenant $tenant, string $feature): int
    {
        $subscription = $tenant->subscription;
        if (!$subscription) {
            return 0;
        }

        return $subscription->getFeatureLimit($feature);
    }
} 