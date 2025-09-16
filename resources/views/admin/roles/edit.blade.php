@extends('layouts.app')

@section('title', 'Edit Role: ' . $role->display_name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <div class="flex items-center mb-2">
                <a href="{{ route('admin.roles.show', $role) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Role</h1>
            </div>
            <p class="text-gray-600">{{ $role->display_name }} - Modify role information and permissions</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.roles.show', $role) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>
                View Role
            </a>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>
                All Roles
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                       placeholder="e.g., manager, editor, viewer">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Display Name *</label>
                                <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('display_name') border-red-500 @enderror"
                                       placeholder="e.g., Manager, Editor, Viewer">
                                @error('display_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe the role's purpose and responsibilities">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                            <select id="tenant_id" name="tenant_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                                <option value="">Select a tenant (optional)</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id', $role->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.roles.show', $role) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200" id="permissions">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                    <p class="text-sm text-gray-600 mt-1">Select the permissions that this role should have</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_permissions" value="1">
                        
                        @php
                            $rolePermissions = $role->permissions->pluck('id')->toArray();
                        @endphp
                        
                        @foreach($permissions as $module => $modulePermissions)
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 capitalize flex items-center">
                                    <i class="fas fa-{{ $module === 'users' ? 'users' : ($module === 'roles' ? 'user-shield' : ($module === 'agencies' ? 'building' : 'cog')) }} mr-2 text-blue-600"></i>
                                    {{ ucfirst($module) }} Permissions
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($modulePermissions as $permission)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" 
                                                       id="permission_{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3">
                                                <label for="permission_{{ $permission->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                                    {{ $permission->display_name }}
                                                </label>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="selectAllPermissions()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Select All
                            </button>
                            <button type="button" onclick="deselectAllPermissions()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Deselect All
                            </button>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                Update Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h4>
                
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Current Status</h5>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Users with this Role</h5>
                        <p class="text-sm text-gray-600">{{ $role->users->count() }} users</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Current Permissions</h5>
                        <p class="text-sm text-gray-600">{{ $role->permissions->count() }} permissions</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Created</h5>
                        <p class="text-sm text-gray-600">{{ $role->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium text-gray-800 mb-3">Quick Actions</h5>
                    <div class="space-y-2">
                        <a href="{{ route('admin.roles.show', $role) }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            View Role Details
                        </a>
                        <a href="{{ route('admin.users.index') }}?role={{ $role->id }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <i class="fas fa-users mr-2"></i>
                            View Users ({{ $role->users->count() }})
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Add smooth scrolling to permissions section
document.addEventListener('DOMContentLoaded', function() {
    // Check if URL has #permissions anchor
    if (window.location.hash === '#permissions') {
        document.getElementById('permissions').scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection


