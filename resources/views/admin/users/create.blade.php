@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New User</h1>
            <p class="text-gray-600">Create a new user account with specific roles and permissions</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                       placeholder="Enter full name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                       placeholder="Enter email address">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password" id="password" name="password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                       placeholder="Enter password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-1 text-xs text-gray-500">Minimum 8 characters</div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Confirm password">
                                <div class="mt-1 text-xs text-gray-500" id="password-match"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                       placeholder="Enter phone number">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant *</label>
                                <select id="tenant_id" name="tenant_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                                    <option value="">Select a tenant</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-2">Agency</label>
                            <select id="agency_id" name="agency_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('agency_id') border-red-500 @enderror">
                                <option value="">Select an agency (optional)</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                        {{ $agency->nom_agence }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                      placeholder="Enter address">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Roles *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ $role->display_name ?: $role->name }}</span>
                                    @if($role->description)
                                        <span class="ml-2 text-xs text-gray-500">({{ $role->description }})</span>
                                    @endif
                                </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Help & Guidelines</h4>
                
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Password Requirements</h5>
                        <p class="text-sm text-gray-600">Minimum 8 characters with a mix of letters, numbers, and symbols</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Role Assignment</h5>
                        <p class="text-sm text-gray-600">Select one or more roles to define user permissions</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Tenant Assignment</h5>
                        <p class="text-sm text-gray-600">Required - determines which tenant the user belongs to</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Agency Assignment</h5>
                        <p class="text-sm text-gray-600">Optional - assign user to a specific rental agency</p>
                    </div>
                </div>

                @if($recentUsers->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium text-gray-800 mb-3">Recently Created Users</h5>
                    <div class="space-y-2">
                        @foreach($recentUsers as $user)
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            <span class="text-gray-500 ml-2">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const matchDiv = document.getElementById('password-match');
    
    if (confirmation === '') {
        matchDiv.textContent = '';
        matchDiv.className = 'mt-1 text-xs text-gray-500';
    } else if (password === confirmation) {
        matchDiv.textContent = 'Passwords match';
        matchDiv.className = 'mt-1 text-xs text-green-600';
    } else {
        matchDiv.textContent = 'Passwords do not match';
        matchDiv.className = 'mt-1 text-xs text-red-600';
    }
});

// Form validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Passwords do not match. Please correct this before submitting.');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long.');
        return false;
    }
    
    const roles = document.querySelectorAll('input[name="roles[]"]:checked');
    if (roles.length === 0) {
        e.preventDefault();
        alert('Please select at least one role for the user.');
        return false;
    }
});
</script>
@endpush
