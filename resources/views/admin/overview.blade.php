@extends('layouts.app')

@section('title', __('app.admin') . ' - ' . __('app.overview'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('app.admin') }} - {{ __('app.overview') }}</h1>
        <div class="text-sm text-gray-600">
            @if(auth()->user()->isSuperAdmin())
                {{ __('app.system') }} {{ __('app.admin') }} Odys
            @else
                {{ __('app.admin') }} {{ __('app.panel') }}
            @endif
        </div>
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
                    <div class="text-indigo-100 text-sm">{{ __('app.tenants') }}</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">{{ __('app.multi_tenant_system') }}</h3>
            <p class="text-indigo-100 text-sm">{{ __('app.manage_all_agencies') }}</p>
        </div>

        <!-- Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</div>
                    <div class="text-blue-100 text-sm">{{ __('app.users') }}</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">{{ __('app.user_management') }}</h3>
            <p class="text-blue-100 text-sm">{{ __('app.control_access_permissions') }}</p>
        </div>

        <!-- Agencies -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</div>
                    <div class="text-green-100 text-sm">{{ __('app.agencies') }}</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">{{ __('app.agency_management') }}</h3>
            <p class="text-green-100 text-sm">{{ __('app.manage_rental_agencies') }}</p>
        </div>

        <!-- Revenue -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">${{ number_format($stats['total_revenue'] ?? 0) }}</div>
                    <div class="text-red-100 text-sm">{{ __('app.total_revenue') }}</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">{{ __('app.financial_overview') }}</h3>
            <p class="text-red-100 text-sm">{{ __('app.monitor_revenue_agencies') }}</p>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- User & Role Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">{{ __('app.user_role_management') }}</h3>
                <div class="text-sm text-gray-600">{{ __('app.access_control') }}</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.users') }}</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_users'] ?? 0 }} {{ __('app.total_users') }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                        {{ __('app.manage_users') }}
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-shield text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.roles') }}</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_roles'] ?? 0 }} {{ __('app.defined_roles') }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm">
                        {{ __('app.manage_roles') }}
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-orange-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.permissions') }}</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_permissions'] ?? 0 }} {{ __('app.system_permissions') }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary btn-sm">
                        {{ __('app.manage_permissions') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Agency & Tenant Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">{{ __('app.agency_tenant_management') }}</h3>
                <div class="text-sm text-gray-600">{{ __('app.multi_tenant_control') }}</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.agencies') }}</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_agencies'] ?? 0 }} {{ __('app.rental_agencies') }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.agencies.index') }}" class="btn btn-primary btn-sm">
                        {{ __('app.manage_agencies') }}
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.tenants') }}</div>
                            <div class="text-sm text-gray-600">{{ $stats['total_tenants'] ?? 0 }} {{ __('app.active_tenants') }}</div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-secondary btn-sm">
                        {{ __('app.view_tenants') }}
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ __('app.analytics') }}</div>
                            <div class="text-sm text-gray-600">{{ __('app.system_performance_metrics') }}</div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-secondary btn-sm">
                        {{ __('app.view_analytics') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">{{ __('app.quick_actions') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">{{ __('app.add_user') }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.create_new_user_account') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.agencies.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-building text-green-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">{{ __('app.add_agency') }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.create_new_rental_agency') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.roles.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">{{ __('app.create_role') }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.define_new_user_role') }}</div>
                </div>
            </a>

                            <a href="{{ route('admin.bulk-create') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-key text-orange-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">{{ __('app.bulk_permissions') }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.create_multiple_permissions') }}</div>
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
