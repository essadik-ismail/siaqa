@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Role</h1>
            <p class="text-gray-600">Define a new role with specific permissions</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Roles
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                       placeholder="e.g., manager, editor, viewer">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                                <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}"
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
                                      placeholder="Describe the role's purpose and responsibilities">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                            <select id="tenant_id" name="tenant_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                                <option value="">Select a tenant (optional)</option>
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

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Create Role
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
                        <h5 class="font-medium text-gray-800 mb-2">Role Naming</h5>
                        <p class="text-sm text-gray-600">Use lowercase, descriptive names like 'manager', 'editor', 'viewer'</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Display Name</h5>
                        <p class="text-sm text-gray-600">Human-readable name that appears in the UI</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Tenant Assignment</h5>
                        <p class="text-sm text-gray-600">Leave empty for system-wide roles, assign to specific tenant for tenant-specific roles</p>
                    </div>
                </div>

                @if($recentRoles->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium text-gray-800 mb-3">Recently Created Roles</h5>
                    <div class="space-y-2">
                        @foreach($recentRoles as $role)
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ $role->name }}</span>
                            <span class="text-gray-500 ml-2">{{ $role->created_at->diffForHumans() }}</span>
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
