@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('app.settings') }}</h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 tab-button active" id="general-tab" data-tab="general">
                        {{ __('app.general') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 tab-button" id="notifications-tab" data-tab="notifications">
                        {{ __('app.notifications') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 tab-button" id="security-tab" data-tab="security">
                        {{ __('app.security') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 tab-button" id="billing-tab" data-tab="billing">
                        {{ __('app.billing') }}
                    </button>
                </nav>
            </div>

            <!-- General Settings -->
            <div id="general-settings" class="p-6">
                <form method="POST" action="{{ route('settings.general.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.business_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['general']['company_name']) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                                    <input type="email" name="company_email" id="company_email" value="{{ old('company_email', $settings['general']['company_email']) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('company_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                    <input type="text" name="company_phone" id="company_phone" value="{{ old('company_phone', $settings['general']['company_phone']) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('company_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                    <input type="text" name="company_address" id="company_address" value="{{ old('company_address', $settings['general']['company_address']) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('company_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres système</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Devise</label>
                                    <select name="currency" id="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="DH" {{ old('currency', $settings['general']['currency']) == 'DH' ? 'selected' : '' }}>Dirham Marocain (DH)</option>
                                        <option value="EUR" {{ old('currency', $settings['general']['currency']) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        <option value="USD" {{ old('currency', $settings['general']['currency']) == 'USD' ? 'selected' : '' }}>Dollar US ($)</option>
                                        <option value="GBP" {{ old('currency', $settings['general']['currency']) == 'GBP' ? 'selected' : '' }}>Livre Sterling (£)</option>
                                    </select>
                                    @error('currency')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                                    <select name="timezone" id="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="Europe/Paris" {{ old('timezone', $settings['general']['timezone']) == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                        <option value="UTC" {{ old('timezone', $settings['general']['timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ old('timezone', $settings['general']['timezone']) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    </select>
                                    @error('timezone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">Format de date</label>
                                    <select name="date_format" id="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="d/m/Y" {{ old('date_format', $settings['general']['date_format']) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                        <option value="m/d/Y" {{ old('date_format', $settings['general']['date_format']) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                        <option value="Y-m-d" {{ old('date_format', $settings['general']['date_format']) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    </select>
                                    @error('date_format')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                                    <select name="language" id="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="fr" {{ old('language', $settings['general']['language']) == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="ar" {{ old('language', $settings['general']['language']) == 'ar' ? 'selected' : '' }}>العربية</option>
                                    </select>
                                    @error('language')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                Sauvegarder les paramètres
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Notifications Settings -->
            <div id="notifications-settings" class="p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres de notifications</h3>
                <form method="POST" action="{{ route('settings.notifications.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Notifications par email</h4>
                                <p class="text-sm text-gray-500">Recevoir les notifications par email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" class="sr-only peer" {{ $settings['notifications']['email_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Notifications de réservation</h4>
                                <p class="text-sm text-gray-500">Notifications pour les nouvelles réservations</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="reservation_notifications" class="sr-only peer" {{ $settings['notifications']['reservation_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Notifications de maintenance</h4>
                                <p class="text-sm text-gray-500">Notifications pour les maintenances prévues</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_notifications" class="sr-only peer" {{ $settings['notifications']['maintenance_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Notifications de contrat</h4>
                                <p class="text-sm text-gray-500">Notifications pour les nouveaux contrats</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="contract_notifications" class="sr-only peer" {{ $settings['notifications']['contract_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Notifications de paiement</h4>
                                <p class="text-sm text-gray-500">Notifications pour les paiements reçus</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_notifications" class="sr-only peer" {{ $settings['notifications']['payment_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Security Settings -->
            <div id="security-settings" class="p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres de sécurité</h3>
                <form method="POST" action="{{ route('settings.security.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Délai d'expiration de session (minutes)</label>
                            <input type="number" name="session_timeout" id="session_timeout" value="{{ old('session_timeout', $settings['security']['session_timeout']) }}" min="15" max="1440" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('session_timeout')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Authentification à deux facteurs</h4>
                                <p class="text-sm text-gray-500">Activer l'authentification à deux facteurs pour plus de sécurité</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="two_factor_auth" class="sr-only peer" {{ $settings['security']['two_factor_auth'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div>
                            <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 mb-2">Expiration du mot de passe (jours)</label>
                            <input type="number" name="password_expiry_days" id="password_expiry_days" value="{{ old('password_expiry_days', $settings['security']['password_expiry_days']) }}" min="30" max="365" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('password_expiry_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Nombre maximum de tentatives de connexion</label>
                            <input type="number" name="max_login_attempts" id="max_login_attempts" value="{{ old('max_login_attempts', $settings['security']['max_login_attempts']) }}" min="3" max="10" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('max_login_attempts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Billing Settings -->
            <div id="billing-settings" class="p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres de facturation</h3>
                <form method="POST" action="{{ route('settings.billing.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">Adresse de facturation</label>
                            <textarea name="billing_address" id="billing_address" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('billing_address', $settings['billing']['billing_address']) }}</textarea>
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Taux de TVA (%)</label>
                                <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $settings['billing']['tax_rate']) }}" min="0" max="100" step="0.01" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tax_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-2">Préfixe des factures</label>
                                <input type="text" name="invoice_prefix" id="invoice_prefix" value="{{ old('invoice_prefix', $settings['billing']['invoice_prefix']) }}" maxlength="10" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('invoice_prefix')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Délai de paiement (jours)</label>
                                <input type="number" name="payment_terms" id="payment_terms" value="{{ old('payment_terms', $settings['billing']['payment_terms']) }}" min="0" max="90" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('payment_terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = ['general', 'notifications', 'security', 'billing'];
    const tabButtons = tabs.map(tab => document.getElementById(`${tab}-tab`));
    const tabContents = tabs.map(tab => document.getElementById(`${tab}-settings`));

    function showTab(tabName) {
        // Hide all tab contents
        tabContents.forEach(content => {
            if (content) {
                content.classList.add('hidden');
            }
        });
        
        // Remove active state from all tab buttons
        tabButtons.forEach(button => {
            if (button) {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            }
        });
        
        // Show selected tab content
        const selectedContent = document.getElementById(`${tabName}-settings`);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
        }
        
        // Add active state to selected tab button
        const selectedButton = document.getElementById(`${tabName}-tab`);
        if (selectedButton) {
            selectedButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            selectedButton.classList.add('border-blue-500', 'text-blue-600');
        }
    }

    // Add click event listeners to tab buttons
    tabButtons.forEach(button => {
        if (button) {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                showTab(tabName);
            });
        }
    });

    // Initialize with general tab active
    showTab('general');
});
</script>
@endsection 