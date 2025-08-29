<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SaaS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the SaaS platform including subscription plans,
    | feature limits, and billing settings.
    |
    */

    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => 29.99,
            'stripe_price_id' => env('STRIPE_PRICE_STARTER', 'price_starter'),
            'features' => [
                'vehicles' => 10,
                'users' => 5,
                'api_calls' => 1000,
                'reservations' => 100,
                'contrats' => 50,
                'support' => 'email',
                'white_label' => false,
                'api_access' => true,
                'reports' => 'basic',
            ],
        ],
        'professional' => [
            'name' => 'Professional',
            'price' => 79.99,
            'stripe_price_id' => env('STRIPE_PRICE_PROFESSIONAL', 'price_professional'),
            'features' => [
                'vehicles' => 50,
                'users' => 15,
                'api_calls' => 5000,
                'reservations' => 500,
                'contrats' => 250,
                'support' => 'priority',
                'white_label' => true,
                'api_access' => true,
                'reports' => 'advanced',
            ],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 199.99,
            'stripe_price_id' => env('STRIPE_PRICE_ENTERPRISE', 'price_enterprise'),
            'features' => [
                'vehicles' => -1, // Unlimited
                'users' => -1, // Unlimited
                'api_calls' => -1, // Unlimited
                'reservations' => -1, // Unlimited
                'contrats' => -1, // Unlimited
                'support' => 'dedicated',
                'white_label' => true,
                'api_access' => true,
                'reports' => 'enterprise',
                'custom_integrations' => true,
            ],
        ],
    ],

    'trial_days' => 14,

    'billing' => [
        'currency' => 'USD',
        'tax_rate' => 0.0, // Set to your tax rate
        'invoice_prefix' => 'INV',
        'payment_terms' => 30, // days
    ],

    'features' => [
        'usage_tracking' => true,
        'billing_portal' => true,
        'subscription_management' => true,
        'usage_limits' => true,
        'white_label' => true,
        'api_access' => true,
        'reports' => true,
    ],

    'limits' => [
        'max_tenants_per_plan' => [
            'starter' => 1,
            'professional' => 5,
            'enterprise' => -1, // Unlimited
        ],
        'max_database_size' => [
            'starter' => '1GB',
            'professional' => '10GB',
            'enterprise' => '100GB',
        ],
    ],

    'notifications' => [
        'trial_ending' => 3, // days before trial ends
        'subscription_expiring' => 7, // days before subscription expires
        'usage_limit_reached' => 80, // percentage
    ],
]; 