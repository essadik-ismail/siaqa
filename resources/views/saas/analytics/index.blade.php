@extends('layouts.app')

@section('title', __('app.system_analytics'))

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">SaaS Analytics Dashboard</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.analytics.tenants') }}" class="btn btn-secondary">
                <i class="fas fa-building mr-2"></i>
                Tenant Analytics
            </a>
            <a href="{{ route('saas.analytics.revenue') }}" class="btn btn-success">
                <i class="fas fa-chart-line mr-2"></i>
                Revenue Analytics
            </a>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Tenants</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_tenants']) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Tenants</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_tenants']) }}</p>
                    <p class="text-sm text-green-600">
                        {{ $stats['total_tenants'] > 0 ? round(($stats['active_tenants'] / $stats['total_tenants']) * 100, 1) : 0 }}% active
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-car text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_vehicles']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Monthly Growth -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Monthly Growth</h3>
                <span class="text-sm text-gray-500">Tenants</span>
            </div>
            <div class="flex items-center">
                <div class="text-3xl font-bold {{ $stats['monthly_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $stats['monthly_growth'] >= 0 ? '+' : '' }}{{ $stats['monthly_growth'] }}%
                </div>
                <div class="ml-3">
                    @if($stats['monthly_growth'] >= 0)
                        <i class="fas fa-arrow-up text-green-600"></i>
                    @else
                        <i class="fas fa-arrow-down text-red-600"></i>
                    @endif
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">vs. last month</p>
        </div>

        <!-- Total Reservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Total Reservations</h3>
                <span class="text-sm text-gray-500">All time</span>
            </div>
            <div class="text-3xl font-bold text-blue-600">
                {{ number_format($stats['total_reservations']) }}
            </div>
            <p class="text-sm text-gray-500 mt-2">Reservations across all tenants</p>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">System Health</h3>
                <span class="text-sm text-gray-500">Status</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <span class="text-lg font-semibold text-green-600">Healthy</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">All systems operational</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Tenant Growth Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tenant Growth (Last 6 Months)</h3>
            <div class="h-64 flex items-center justify-center">
                @if($tenantGrowth->count() > 0)
                    <canvas id="tenantGrowthChart" width="400" height="200"></canvas>
                @else
                    <div class="text-center text-gray-500">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <p>No tenant growth data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Activity Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity (Last 6 Months)</h3>
            <div class="h-64 flex items-center justify-center">
                @if($userActivity->count() > 0)
                    <canvas id="userActivityChart" width="400" height="200"></canvas>
                @else
                    <div class="text-center text-gray-500">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>No user activity data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenue Trends Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trends (Last 6 Months)</h3>
        <div class="h-80 flex items-center justify-center">
            @if($revenueTrends->count() > 0)
                <canvas id="revenueTrendsChart" width="800" height="300"></canvas>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-dollar-sign fa-3x mb-3"></i>
                    <p>No revenue data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Insights</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Top Performing Tenants -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-trophy text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Top Performers</h4>
                        <p class="text-sm text-gray-500">Best performing tenants</p>
                    </div>
                </div>
                <a href="{{ route('saas.analytics.tenants') }}" class="btn btn-primary btn-sm w-full">
                    View Details
                </a>
            </div>
            
            <!-- Revenue Analysis -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Revenue Analysis</h4>
                        <p class="text-sm text-gray-500">Financial performance insights</p>
                    </div>
                </div>
                <a href="{{ route('saas.analytics.revenue') }}" class="btn btn-success btn-sm w-full">
                    View Details
                </a>
            </div>
            
            <!-- System Metrics -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cogs text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">System Metrics</h4>
                        <p class="text-sm text-gray-500">Performance & health data</p>
                    </div>
                </div>
                <a href="{{ route('saas.maintenance.index') }}" class="btn btn-secondary btn-sm w-full">
                    View Details
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent System Activity</h3>
        
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-plus text-green-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">New tenant registered</p>
                    <p class="text-xs text-gray-500">2 hours ago</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">New user account created</p>
                    <p class="text-xs text-gray-500">4 hours ago</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-car text-yellow-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">New vehicle added</p>
                    <p class="text-xs text-gray-500">6 hours ago</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tenant Growth Chart
    @if($tenantGrowth->count() > 0)
    const tenantCtx = document.getElementById('tenantGrowthChart').getContext('2d');
    new Chart(tenantCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($tenantGrowth->pluck('date')) !!},
            datasets: [{
                label: 'New Tenants',
                data: {!! json_encode($tenantGrowth->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif

    // User Activity Chart
    @if($userActivity->count() > 0)
    const userCtx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($userActivity->pluck('date')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userActivity->pluck('count')) !!},
                backgroundColor: 'rgba(147, 51, 234, 0.8)',
                borderColor: 'rgb(147, 51, 234)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif

    // Revenue Trends Chart
    @if($revenueTrends->count() > 0)
    const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueTrends->pluck('date')) !!},
            datasets: [{
                label: 'Daily Revenue',
                data: {!! json_encode($revenueTrends->pluck('revenue')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true
                }
            }
        }
    });
    @endif
});
</script>
@endpush
