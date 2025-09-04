@extends('layouts.app')

@section('title', 'Tenant Analytics')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tenant Analytics</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.analytics.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Analytics
            </a>
            <a href="{{ route('saas.analytics.revenue') }}" class="btn btn-success">
                <i class="fas fa-chart-line mr-2"></i>
                Revenue Analytics
            </a>
        </div>
    </div>

    <!-- Tenant Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($tenantStats->total) }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($tenantStats->active) }}</p>
                    <p class="text-sm text-green-600">
                        {{ $tenantStats->total > 0 ? round(($tenantStats->active / $tenantStats->total) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Inactive Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($tenantStats->inactive) }}</p>
                    <p class="text-sm text-red-600">
                        {{ $tenantStats->total > 0 ? round(($tenantStats->inactive / $tenantStats->total) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Trial Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Trial</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($tenantStats->trial) }}</p>
                    <p class="text-sm text-yellow-600">
                        {{ $tenantStats->total > 0 ? round(($tenantStats->trial / $tenantStats->total) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Expired Tenants -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-times text-gray-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Expired</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($tenantStats->expired) }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $tenantStats->total > 0 ? round(($tenantStats->expired / $tenantStats->total) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Tenants by Plan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tenants by Subscription Plan</h3>
            <div class="h-64 flex items-center justify-center">
                @if($tenantsByPlan->count() > 0)
                    <canvas id="planDistributionChart" width="400" height="200"></canvas>
                @else
                    <div class="text-center text-gray-500">
                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                        <p>No plan distribution data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tenants by Month -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tenant Growth by Month ({{ now()->year }})</h3>
            <div class="h-64 flex items-center justify-center">
                @if($tenantsByMonth->count() > 0)
                    <canvas id="monthlyGrowthChart" width="400" height="200"></canvas>
                @else
                    <div class="text-center text-gray-500">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>No monthly growth data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detailed Tenant Analytics</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Conversion Rate -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        {{ $tenantStats->total > 0 ? round((($tenantStats->active + $tenantStats->trial) / $tenantStats->total) * 100, 1) : 0 }}%
                    </div>
                    <p class="text-sm font-medium text-gray-900">Conversion Rate</p>
                    <p class="text-xs text-gray-500">Trial to Active</p>
                </div>
            </div>
            
            <!-- Churn Rate -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">
                        {{ $tenantStats->total > 0 ? round(($tenantStats->inactive / $tenantStats->total) * 100, 1) : 0 }}%
                    </div>
                    <p class="text-sm font-medium text-gray-900">Churn Rate</p>
                    <p class="text-xs text-gray-500">Active to Inactive</p>
                </div>
            </div>
            
            <!-- Trial Success Rate -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">
                        {{ $tenantStats->trial > 0 ? round(($tenantStats->active / $tenantStats->trial) * 100, 1) : 0 }}%
                    </div>
                    <p class="text-sm font-medium text-gray-900">Trial Success</p>
                    <p class="text-xs text-gray-500">Trial to Paid</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Metrics</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Average Tenants per Month -->
            <div class="p-4 bg-blue-50 rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $tenantsByMonth->count() > 0 ? round($tenantsByMonth->avg('count'), 1) : 0 }}
                    </div>
                    <p class="text-sm font-medium text-blue-900">Avg/Month</p>
                </div>
            </div>
            
            <!-- Peak Month -->
            <div class="p-4 bg-green-50 rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $tenantsByMonth->count() > 0 ? $tenantsByMonth->max('count') : 0 }}
                    </div>
                    <p class="text-sm font-medium text-green-900">Peak Month</p>
                </div>
            </div>
            
            <!-- Growth Trend -->
            <div class="p-4 bg-yellow-50 rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">
                        @if($tenantsByMonth->count() >= 2)
                            @php
                                $firstHalf = $tenantsByMonth->take(6)->sum('count');
                                $secondHalf = $tenantsByMonth->skip(6)->sum('count');
                                $growth = $firstHalf > 0 ? (($secondHalf - $firstHalf) / $firstHalf) * 100 : 0;
                            @endphp
                            {{ round($growth, 1) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <p class="text-sm font-medium text-yellow-900">6-Month Growth</p>
                </div>
            </div>
            
            <!-- Retention Rate -->
            <div class="p-4 bg-purple-50 rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ $tenantStats->total > 0 ? round((($tenantStats->total - $tenantStats->inactive) / $tenantStats->total) * 100, 1) : 0 }}%
                    </div>
                    <p class="text-sm font-medium text-purple-900">Retention Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Analytics Insights & Recommendations</h3>
        
        <div class="space-y-4">
            @if($tenantStats->trial > 0 && $tenantStats->trial > $tenantStats->active)
            <div class="flex items-start p-4 bg-yellow-50 rounded-lg">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-1">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-yellow-900">High Trial to Active Conversion Needed</h4>
                    <p class="text-sm text-yellow-700 mt-1">
                        {{ $tenantStats->trial }} tenants are currently on trial. Consider improving onboarding and support to increase conversion rates.
                    </p>
                </div>
            </div>
            @endif

            @if($tenantStats->inactive > 0 && $tenantStats->inactive > ($tenantStats->total * 0.2))
            <div class="flex items-start p-4 bg-red-50 rounded-lg">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-1">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-red-900">High Churn Rate Detected</h4>
                    <p class="text-sm text-red-700 mt-1">
                        {{ round(($tenantStats->inactive / $tenantStats->total) * 100, 1) }}% of tenants are inactive. Review customer success strategies.
                    </p>
                </div>
            </div>
            @endif

            @if($tenantStats->active > 0 && $tenantStats->active > ($tenantStats->total * 0.7))
            <div class="flex items-start p-4 bg-green-50 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-green-900">Strong Tenant Retention</h4>
                    <p class="text-sm text-green-700 mt-1">
                        {{ round(($tenantStats->active / $tenantStats->total) * 100, 1) }}% of tenants are active. Excellent customer success performance!
                    </p>
                </div>
            </div>
            @endif

            <div class="flex items-start p-4 bg-blue-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                    <i class="fas fa-lightbulb text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-blue-900">Growth Opportunities</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        Focus on converting trial users, reducing churn, and expanding to new markets to increase tenant base.
                    </p>
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
    // Plan Distribution Chart
    @if($tenantsByPlan->count() > 0)
    const planCtx = document.getElementById('planDistributionChart').getContext('2d');
    new Chart(planCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($tenantsByPlan->pluck('subscription_plan')) !!},
            datasets: [{
                data: {!! json_encode($tenantsByPlan->pluck('count')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    // Monthly Growth Chart
    @if($tenantsByMonth->count() > 0)
    const monthlyCtx = document.getElementById('monthlyGrowthChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($tenantsByMonth->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
            datasets: [{
                label: 'New Tenants',
                data: {!! json_encode($tenantsByMonth->pluck('count')) !!},
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
});
</script>
@endpush
