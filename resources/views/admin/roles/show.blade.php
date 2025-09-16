@extends('layouts.app')

@section('title', 'Role Details: ' . $role->display_name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <div class="flex items-center mb-2">
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Role Details</h1>
            </div>
            <p class="text-gray-600">{{ $role->display_name }} - View role information and permissions</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>
                Edit Role
            </a>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>
                Back to Roles
            </a>
        </div>
    </div>

    <!-- Role Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Role Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <p class="text-gray-900 font-medium">{{ $role->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                    <p class="text-gray-900">{{ $role->display_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                    @if($role->tenant)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $role->tenant->name }}
                        </span>
                    @else
                        <span class="text-gray-500 text-sm">No tenant assigned</span>
                    @endif
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900">{{ $role->description ?: 'No description provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                    <p class="text-gray-900">{{ $role->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900">{{ $role->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Users with this Role</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $role->users->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Permissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $role->permissions->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Security Level</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        @if($role->name === 'super_admin')
                            <span class="text-red-600">High</span>
                        @elseif($role->name === 'admin')
                            <span class="text-orange-600">Medium</span>
                        @else
                            <span class="text-blue-600">Standard</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Users with this Role -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Users with this Role ({{ $role->users->count() }})</h3>
            </div>
            
            @if($role->users->count() > 0)
                <div class="max-h-96 overflow-y-auto">
                    @foreach($role->users as $user)
                        <div class="px-6 py-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-users text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users assigned</h3>
                    <p class="mt-1 text-sm text-gray-500">This role doesn't have any users assigned yet.</p>
                </div>
            @endif
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Permissions ({{ $role->permissions->count() }})</h3>
            </div>
            
            @if($role->permissions->count() > 0)
                <div class="max-h-96 overflow-y-auto">
                    @php
                        $permissionsByModule = $role->permissions->groupBy('module');
                    @endphp
                    @foreach($permissionsByModule as $module => $permissions)
                        <div class="px-6 py-4 border-b border-gray-200 last:border-b-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-3 capitalize">{{ $module }}</h4>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Granted
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-key text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions assigned</h3>
                    <p class="mt-1 text-sm text-gray-500">This role doesn't have any permissions assigned yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.roles.edit', $role) }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-900">Edit Role</p>
                        <p class="text-xs text-yellow-700">Modify role details</p>
                    </div>
                </a>

                <a href="{{ route('admin.roles.edit', $role) }}#permissions" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-key text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-900">Manage Permissions</p>
                        <p class="text-xs text-green-700">{{ $role->permissions->count() }} permissions</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}?role={{ $role->id }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-900">View Users</p>
                        <p class="text-xs text-blue-700">{{ $role->users->count() }} users</p>
                    </div>
                </a>

                <a href="{{ route('admin.roles.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">All Roles</p>
                        <p class="text-xs text-gray-700">Back to roles list</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


