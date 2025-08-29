@extends('layouts.app')

@section('title', 'Admin Overview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Overview</h1>
        <div class="text-sm text-gray-600">SaaS Administration Dashboard</div>
    </div>

    <!-- SaaS Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Tenants -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_tenants'] ?? 0 }}</div>
                    <div class="text-indigo-100 text-sm">Tenants</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Multi-Tenant System</h3>
            <p class="text-indigo-100 text-sm">Manage all rental agencies and their data</p>
        </div>

        <!-- Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</div>
                    <div class="text-blue-100 text-sm">Users</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">User Management</h3>
            <p class="text-blue-100 text-sm">Control access and permissions across the system</p>
        </div>

        <!-- Agencies -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</div>
                    <div class="text-green-100 text-sm">Agencies</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Agency Management</h3>
            <p class="text-green-100 text-sm">Manage rental agencies and their configurations</p>
        </div>

        <!-- Revenue -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">${{ number_format($stats['total_revenue'] ?? 0) }}</div>
                    <div class="text-red-100 text-sm">Total Revenue</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Financial Overview</h3>
            <p class="text-red-100 text-sm">Monitor revenue across all agencies</p>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- User & Role Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">User & Role Management</h3>
                <div class="text-sm text-gray-600">Access Control</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Users</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_users'] ?? 0 }} total users</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                        Manage Users
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-shield text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Roles</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_roles'] ?? 0 }} defined roles</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm">
                        Manage Roles
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-orange-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Permissions</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_permissions'] ?? 0 }} system permissions</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary btn-sm">
                        Manage Permissions
                    </a>
                </div>
            </div>
        </div>

        <!-- Agency & Tenant Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Agency & Tenant Management</h3>
                <div class="text-sm text-gray-600">Multi-Tenant Control</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Agencies</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_agencies'] ?? 0 }} rental agencies</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.agencies.index') }}" class="btn btn-primary btn-sm">
                        Manage Agencies
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Tenants</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_tenants'] ?? 0 }} active tenants</div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-secondary btn-sm">
                        View Tenants
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Analytics</div>
                            <div class="text-sm text-gray-600">System performance metrics</div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-secondary btn-sm">
                        View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Add User</div>
                    <div class="text-sm text-gray-600">Create new user account</div>
                </div>
            </a>

            <a href="{{ route('admin.agencies.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-building text-green-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Add Agency</div>
                    <div class="text-sm text-gray-600">Create new rental agency</div>
                </div>
            </a>

            <a href="{{ route('admin.roles.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Create Role</div>
                    <div class="text-sm text-gray-600">Define new user role</div>
                </div>
            </a>

                            <a href="{{ route('admin.bulk-create') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-key text-orange-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Bulk Permissions</div>
                    <div class="text-sm text-gray-600">Create multiple permissions</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.btn {
    @apply px-4 py-2 rounded-lg font-medium text-sm transition-colors duration-200;
}

.btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700;
}

.btn-secondary {
    @apply bg-gray-600 text-white hover:bg-gray-700;
}

.btn-sm {
    @apply px-3 py-1.5 text-xs;
}
</style>
@endpush
