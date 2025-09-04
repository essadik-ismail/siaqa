@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.global-users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Users
            </a>
            <a href="{{ route('saas.global-users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    Basic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $user->phone ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $user->address ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email Verified</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tenant Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building mr-3 text-green-600"></i>
                    Tenant Information
                </h2>
                
                @if($user->tenant)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company Name</label>
                            <p class="text-gray-900 font-medium">{{ $user->tenant->company_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Domain</label>
                            <p class="text-gray-900">{{ $user->tenant->domain }}.{{ config('app.domain', 'localhost') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Contact Email</label>
                            <p class="text-gray-900">{{ $user->tenant->contact_email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tenant Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->tenant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No tenant assigned</p>
                    </div>
                @endif
            </div>

            <!-- Agency Information -->
            @if($user->agence)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-store mr-3 text-purple-600"></i>
                    Agency Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Agency Name</label>
                        <p class="text-gray-900 font-medium">{{ $user->agence->nom_agence }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Agency Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->agence->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->agence->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $user->agence->full_address ?: 'Not provided' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Roles and Permissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-orange-600"></i>
                    Roles and Permissions
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Roles -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Roles</h3>
                        @if($user->roles && $user->roles->count() > 0)
                            <div class="space-y-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $role->name === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                           ($role->name === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No roles assigned</p>
                        @endif
                    </div>
                    
                    <!-- Permissions -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Direct Permissions</h3>
                        @if($user->permissions && $user->permissions->count() > 0)
                            <div class="space-y-1">
                                @foreach($user->permissions as $permission)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ $permission->display_name ?: $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No direct permissions assigned</p>
                        @endif
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
                    <form action="{{ route('saas.global-users.launch', $user) }}" 
                          method="POST" 
                          target="_blank"
                          class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full btn btn-primary"
                                onclick="return confirm('Are you sure you want to launch as {{ $user->name }}? This will open in a new window.')">
                            <i class="fas fa-rocket mr-2"></i>
                            Launch as User (Private Window)
                        </button>
                    </form>
                    <a href="{{ route('saas.global-users.edit', $user) }}" class="w-full btn btn-secondary">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>
                    <form action="{{ route('saas.global-users.toggle-status', $user) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Login</span>
                        <span class="font-medium">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email Verified</span>
                        <span class="font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not verified' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Status</span>
                        <span class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Security</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Password Last Changed</span>
                        <span class="text-sm font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Two-Factor Auth</span>
                        <span class="text-sm font-medium text-gray-500">Not enabled</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Login Attempts</span>
                        <span class="text-sm font-medium text-gray-500">N/A</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.form-actions {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
</style>


@endsection
