@extends('layouts.app')

@section('title', 'Permission Roles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Permission Roles</h1>
                <p class="text-gray-600 mt-1">Roles assigned to {{ $permission->display_name }}</p>
            </div>
        </div>
    </div>

    <!-- Permission Info Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $permission->display_name }}</h2>
                <p class="text-gray-600 mb-4">{{ $permission->description }}</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span><i class="fas fa-tag mr-1"></i>{{ $permission->name }}</span>
                    <span><i class="fas fa-layer-group mr-1"></i>{{ ucfirst($permission->module) }}</span>
                    <span><i class="fas fa-building mr-1"></i>{{ $permission->tenant->name ?? 'No Tenant' }}</span>
                    <span><i class="fas fa-calendar mr-1"></i>Created {{ $permission->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $permission->is_system ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $permission->is_system ? 'System' : 'Custom' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Roles ({{ $roles->total() }})</h3>
                <div class="text-sm text-gray-600">
                    Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }} results
                </div>
            </div>
        </div>
        
        @if($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-user-shield text-indigo-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $role->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $role->tenant->name ?? 'No Tenant' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->users->count() }} users
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->name === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.roles.edit', $role) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                           title="Edit Role">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.permissions', $role) }}" 
                                           class="text-purple-600 hover:text-purple-900 p-1 rounded" 
                                           title="Manage Permissions">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.users', $role) }}" 
                                           class="text-green-600 hover:text-green-900 p-1 rounded" 
                                           title="View Users">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($roles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-center">
                        {{ $roles->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-user-shield text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Roles Found</h3>
                <p class="text-gray-600 mb-6">No roles are currently assigned to this permission.</p>
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Role
                </a>
            </div>
        @endif
    </div>
</div>
@endsection


