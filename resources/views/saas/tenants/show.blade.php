@extends('layouts.app')

@section('title', 'Tenant Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tenant: {{ $tenant->company_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.tenants.edit', $tenant) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Tenant
            </a>
            <a href="{{ route('saas.tenants.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Tenants
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building mr-3 text-blue-600"></i>
                    Basic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Company Name</label>
                        <p class="text-gray-900 font-medium">{{ $tenant->company_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Domain</label>
                        <p class="text-gray-900 font-medium">{{ $tenant->domain }}.{{ config('app.domain', 'localhost') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Contact Email</label>
                        <p class="text-gray-900">{{ $tenant->contact_email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Contact Phone</label>
                        <p class="text-gray-900">{{ $tenant->contact_phone ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Created</label>
                        <p class="text-gray-900">{{ $tenant->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                
                @if($tenant->address)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="text-gray-900">{{ $tenant->address }}</p>
                </div>
                @endif
                
                @if($tenant->notes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Notes</label>
                    <p class="text-gray-900">{{ $tenant->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Subscription Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-credit-card mr-3 text-green-600"></i>
                    Subscription Information
                </h2>
                
                @if($tenant->subscription)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Plan</label>
                        <p class="text-gray-900 font-medium capitalize">{{ $tenant->subscription->plan_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $tenant->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($tenant->subscription->status === 'canceled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($tenant->subscription->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Started</label>
                        <p class="text-gray-900">{{ $tenant->subscription->starts_at->format('M d, Y') }}</p>
                    </div>
                    
                    @if($tenant->subscription->ends_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ends</label>
                        <p class="text-gray-900">{{ $tenant->subscription->ends_at->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-3"></i>
                    <p class="text-gray-500">No active subscription</p>
                    <a href="{{ route('saas.tenants.edit', $tenant) }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus mr-2"></i>
                        Add Subscription
                    </a>
                </div>
                @endif
            </div>

            <!-- Usage Statistics Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-purple-600"></i>
                    Usage Statistics
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $tenant->users()->count() }}</div>
                        <div class="text-sm text-gray-500">Users</div>
                        <div class="text-xs text-gray-400">
                            @if($tenant->max_users > 0)
                                of {{ $tenant->max_users }}
                            @else
                                Unlimited
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $tenant->vehicules()->count() }}</div>
                        <div class="text-sm text-gray-500">Vehicles</div>
                        <div class="text-xs text-gray-400">
                            @if($tenant->max_vehicles > 0)
                                of {{ $tenant->max_vehicles }}
                            @else
                                Unlimited
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $tenant->reservations()->count() }}</div>
                        <div class="text-sm text-gray-500">Reservations</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $tenant->clients()->count() }}</div>
                        <div class="text-sm text-gray-500">Clients</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <form action="{{ route('saas.tenants.toggle-status', $tenant) }}" method="POST" class="inline-block w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full btn {{ $tenant->is_active ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $tenant->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                            {{ $tenant->is_active ? 'Deactivate' : 'Activate' }} Tenant
                        </button>
                    </form>
                    
                    <a href="{{ route('saas.billing.index', ['tenant' => $tenant->id]) }}" class="btn btn-secondary w-full">
                        <i class="fas fa-credit-card mr-2"></i>
                        View Billing
                    </a>
                    
                    <a href="#" class="btn btn-info w-full">
                        <i class="fas fa-download mr-2"></i>
                        Export Data
                    </a>
                </div>
            </div>

            <!-- Trial Information -->
            @if($tenant->trial_ends_at)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Trial Information</h3>
                
                <div class="text-center">
                    @if($tenant->isOnTrial())
                        <div class="text-2xl font-bold text-green-600">
                            {{ $tenant->trial_ends_at->diffForHumans() }}
                        </div>
                        <div class="text-sm text-gray-500">Trial ends</div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $tenant->trial_ends_at->format('M d, Y') }}
                        </div>
                    @else
                        <div class="text-2xl font-bold text-red-600">Expired</div>
                        <div class="text-sm text-gray-500">Trial ended</div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $tenant->trial_ends_at->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                
                <div class="space-y-3">
                    @forelse($tenant->users()->latest()->take(5)->get() as $user)
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">User created</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">No recent activity</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
