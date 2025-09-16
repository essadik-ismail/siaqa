<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        // Get current settings from cache or database
        $settings = $this->getSettings();
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string|max:500',
            'currency' => 'required|in:EUR,USD,GBP,DH',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|in:d/m/Y,m/d/Y,Y-m-d',
            'language' => 'required|in:fr,ar',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $tenantKey = $tenantId ? ".tenant_{$tenantId}" : '';

        // Store settings in cache (you can also store in database)
        foreach ($validated as $key => $value) {
            Cache::put("settings{$tenantKey}.{$key}", $value, now()->addYear());
        }

        return redirect()->back()->with('success', 'Paramètres généraux mis à jour avec succès');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'reservation_notifications' => 'boolean',
            'maintenance_notifications' => 'boolean',
            'contract_notifications' => 'boolean',
            'payment_notifications' => 'boolean',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $tenantKey = $tenantId ? ".tenant_{$tenantId}" : '';

        // Store notification settings
        foreach ($validated as $key => $value) {
            Cache::put("settings{$tenantKey}.notifications.{$key}", $value, now()->addYear());
        }

        return redirect()->back()->with('success', 'Paramètres de notifications mis à jour avec succès');
    }

    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session_timeout' => 'required|integer|min:15|max:1440',
            'two_factor_auth' => 'boolean',
            'password_expiry_days' => 'nullable|integer|min:30|max:365',
            'max_login_attempts' => 'required|integer|min:3|max:10',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $tenantKey = $tenantId ? ".tenant_{$tenantId}" : '';

        // Store security settings
        foreach ($validated as $key => $value) {
            Cache::put("settings{$tenantKey}.security.{$key}", $value, now()->addYear());
        }

        return redirect()->back()->with('success', 'Paramètres de sécurité mis à jour avec succès');
    }

    /**
     * Update billing settings.
     */
    public function updateBilling(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'billing_address' => 'nullable|string|max:500',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_prefix' => 'nullable|string|max:10',
            'payment_terms' => 'nullable|integer|min:0|max:90',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $tenantKey = $tenantId ? ".tenant_{$tenantId}" : '';

        // Store billing settings
        foreach ($validated as $key => $value) {
            Cache::put("settings{$tenantKey}.billing.{$key}", $value, now()->addYear());
        }

        return redirect()->back()->with('success', 'Paramètres de facturation mis à jour avec succès');
    }

    /**
     * Get all settings.
     */
    private function getSettings(): array
    {
        $tenantId = auth()->user()->tenant_id;
        $tenantKey = $tenantId ? ".tenant_{$tenantId}" : '';
        
        return [
            'general' => [
                'company_name' => Cache::get("settings{$tenantKey}.company_name", 'Car Rental System'),
                'company_email' => Cache::get("settings{$tenantKey}.company_email", 'contact@rental.com'),
                'company_phone' => Cache::get("settings{$tenantKey}.company_phone", '+33 1 23 45 67 89'),
                'company_address' => Cache::get("settings{$tenantKey}.company_address", '123 Rue de la Paix, Paris'),
                'currency' => Cache::get("settings{$tenantKey}.currency", 'DH'),
                'timezone' => Cache::get("settings{$tenantKey}.timezone", 'Europe/Paris'),
                'date_format' => Cache::get("settings{$tenantKey}.date_format", 'd/m/Y'),
                'language' => Cache::get("settings{$tenantKey}.language", 'fr'),
            ],
            'notifications' => [
                'email_notifications' => Cache::get("settings{$tenantKey}.notifications.email_notifications", true),
                'reservation_notifications' => Cache::get("settings{$tenantKey}.notifications.reservation_notifications", true),
                'maintenance_notifications' => Cache::get("settings{$tenantKey}.notifications.maintenance_notifications", true),
                'contract_notifications' => Cache::get("settings{$tenantKey}.notifications.contract_notifications", true),
                'payment_notifications' => Cache::get("settings{$tenantKey}.notifications.payment_notifications", true),
            ],
            'security' => [
                'session_timeout' => Cache::get("settings{$tenantKey}.security.session_timeout", 120),
                'two_factor_auth' => Cache::get("settings{$tenantKey}.security.two_factor_auth", false),
                'password_expiry_days' => Cache::get("settings{$tenantKey}.security.password_expiry_days", 90),
                'max_login_attempts' => Cache::get("settings{$tenantKey}.security.max_login_attempts", 5),
            ],
            'billing' => [
                'billing_address' => Cache::get("settings{$tenantKey}.billing.billing_address", ''),
                'tax_rate' => Cache::get("settings{$tenantKey}.billing.tax_rate", 20),
                'invoice_prefix' => Cache::get("settings{$tenantKey}.billing.invoice_prefix", 'INV'),
                'payment_terms' => Cache::get("settings{$tenantKey}.billing.payment_terms", 30),
            ],
        ];
    }
}
