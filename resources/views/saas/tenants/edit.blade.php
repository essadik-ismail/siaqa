@extends('layouts.app')

@section('title', 'Edit Tenant')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Tenant: {{ $tenant->company_name }}</h1>
        <a href="{{ route('saas.tenants.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Tenants
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('saas.tenants.update', $tenant) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h3>
                    
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name *
                        </label>
                        <input type="text" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name', $tenant->company_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-2">
                            Domain *
                        </label>
                        <div class="flex">
                            <input type="text" 
                                   id="domain" 
                                   name="domain" 
                                   value="{{ old('domain', $tenant->domain) }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="company"
                                   required>
                            <span class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-gray-600">
                                .{{ config('app.domain', 'localhost') }}
                            </span>
                        </div>
                        @error('domain')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email *
                        </label>
                        <input type="email" 
                               id="contact_email" 
                               name="contact_email" 
                               value="{{ old('contact_email', $tenant->contact_email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('contact_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone
                        </label>
                        <input type="tel" 
                               id="contact_phone" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', $tenant->contact_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('contact_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Subscription & Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Subscription & Settings</h3>
                    
                    <div>
                        <label for="plan_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Subscription Plan *
                        </label>
                        <select id="plan_name" 
                                name="plan_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select a plan</option>
                            <option value="starter" {{ old('plan_name', $tenant->subscription?->plan_name) == 'starter' ? 'selected' : '' }}>
                                Starter - 29 dh/month
                            </option>
                            <option value="professional" {{ old('plan_name', $tenant->subscription?->plan_name) == 'professional' ? 'selected' : '' }}>
                                Professional - 79 dh/month
                            </option>
                            <option value="enterprise" {{ old('plan_name', $tenant->subscription?->plan_name) == 'enterprise' ? 'selected' : '' }}>
                                Enterprise - 199 dh/month
                            </option>
                        </select>
                        @error('plan_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="trial_ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Trial End Date
                        </label>
                        <input type="date" 
                               id="trial_ends_at" 
                               name="trial_ends_at" 
                               value="{{ old('trial_ends_at', $tenant->trial_ends_at?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('trial_ends_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_users" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Users
                        </label>
                        <input type="number" 
                               id="max_users" 
                               name="max_users" 
                               value="{{ old('max_users', $tenant->max_users ?? 5) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('max_users')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_vehicles" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Vehicles
                        </label>
                        <input type="number" 
                               id="max_vehicles" 
                               name="max_vehicles" 
                               value="{{ old('max_vehicles', $tenant->max_vehicles ?? 10) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('max_vehicles')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Additional Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $tenant->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $tenant->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active tenant</span>
                    </label>
                </div>
            </div>

            <!-- Current Status -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-2">Current Status</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="font-medium">{{ $tenant->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Users:</span>
                        <span class="font-medium">{{ $tenant->users()->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Vehicles:</span>
                        <span class="font-medium">{{ $tenant->vehicules()->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                <a href="{{ route('saas.tenants.index') }}" 
                   class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Plan-based limits
    const planSelect = document.getElementById('plan_name');
    const maxUsersInput = document.getElementById('max_users');
    const maxVehiclesInput = document.getElementById('max_vehicles');
    
    planSelect.addEventListener('change', function() {
        const plan = this.value;
        const limits = {
            'starter': { users: 5, vehicles: 10 },
            'professional': { users: 15, vehicles: 50 },
            'enterprise': { users: -1, vehicles: -1 }
        };
        
        if (limits[plan]) {
            if (limits[plan].users > 0) {
                maxUsersInput.value = limits[plan].users;
                maxUsersInput.disabled = false;
            } else {
                maxUsersInput.value = 'Unlimited';
                maxUsersInput.disabled = true;
            }
            
            if (limits[plan].vehicles > 0) {
                maxVehiclesInput.value = limits[plan].vehicles;
                maxVehiclesInput.disabled = false;
            } else {
                maxVehiclesInput.value = 'Unlimited';
                maxVehiclesInput.disabled = true;
            }
        }
    });
});
</script>
@endpush
