@extends('layouts.app')

@section('title', 'SaaS Overview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SaaS Overview</h1>
        <div class="text-sm text-gray-600">Super Administrator - SaaS Management</div>
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
                    <div class="text-indigo-100 text-sm">Total Tenants</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Multi-Tenant System</h3>
            <p class="text-indigo-100 text-sm">{{ $stats['active_subscriptions'] ?? 0 }} active, {{ $stats['inactive_tenants'] ?? 0 }} inactive</p>
        </div>

        <!-- Agencies -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</div>
                    <div class="text-green-100 text-sm">Total Agencies</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Agency Management</h3>
            <p class="text-green-100 text-sm">Control all rental agencies across tenants</p>
        </div>

        <!-- Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</div>
                    <div class="text-blue-100 text-sm">Total Users</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">User Management</h3>
            <p class="text-blue-100 text-sm">Manage users across all tenants</p>
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
            <p class="text-red-100 text-sm">Monitor revenue across all tenants</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Tenant Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Tenant Management</h3>
                <div class="text-sm text-gray-600">SaaS Operations</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Active Tenants</div>
                            <div class="text-sm text-gray-600">{{ $stats['active_subscriptions'] ?? 0 }} active subscriptions</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.tenants.index') }}" class="btn btn-primary btn-sm">
                        Manage Tenants
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pause-circle text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Inactive Tenants</div>
                            <div class="text-sm text-gray-600">{{ $stats['inactive_tenants'] ?? 0 }} suspended tenants</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.tenants.index') }}" class="btn btn-warning btn-sm">
                        Manage Tenants
                    </a>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Trial Tenants</div>
                            <div class="text-sm text-gray-600">{{ $stats['trial_tenants'] ?? 0 }} on trial period</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.tenants.index') }}" class="btn btn-primary btn-sm">
                        View Trials
                    </a>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Expired Subscriptions</div>
                            <div class="text-sm text-gray-600">{{ $stats['expired_tenants'] ?? 0 }} need renewal</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.tenants.index') }}" class="btn btn-danger btn-sm">
                        Renewals
                    </a>
                </div>
            </div>
        </div>

        <!-- System Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">System Management</h3>
                <div class="text-sm text-gray-600">Infrastructure</div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">System Analytics</div>
                            <div class="text-sm text-gray-600">Cross-tenant insights</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.analytics.index') }}" class="btn btn-primary btn-sm">
                        View Analytics
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">System Maintenance</div>
                            <div class="text-sm text-gray-600">Backup & maintenance</div>
                        </div>
                    </div>
                    <a href="{{ route('saas.maintenance.index') }}" class="btn btn-success btn-sm">
                        Maintenance
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Recent System Activity</h3>
            <div class="text-sm text-gray-600">Last 24 hours</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800">System backup completed successfully</div>
                    <div class="text-sm text-gray-600">2 hours ago</div>
                </div>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800">New tenant registration: Premium Motors</div>
                    <div class="text-sm text-gray-600">4 hours ago</div>
                </div>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800">System maintenance scheduled for tonight</div>
                    <div class="text-sm text-gray-600">6 hours ago</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
