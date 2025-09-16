@extends('layouts.app')

@section('title', 'Revenue Analytics')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
            <a href="{{ route('saas.analytics.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Analytics
            </a>
            <a href="{{ route('saas.analytics.tenants') }}" class="btn btn-primary">
                <i class="fas fa-building mr-2"></i>
                Tenant Analytics
            </a>
        </div>
    </div>

    <!-- Revenue Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($revenueStats->total_revenue ?? 0, 2) }} dh</p>
                </div>
            </div>
        </div>

        <!-- Total Reservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Reservations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($revenueStats->total_reservations ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Average Reservation Value -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Avg. Reservation</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($revenueStats->avg_reservation_value ?? 0, 2) }} dh</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Trends Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue Trends ({{ now()->year }})</h3>
        <div class="h-80 flex items-center justify-center">
            @if($monthlyRevenue->count() > 0)
                <canvas id="monthlyRevenueChart" width="800" height="300"></canvas>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <p>No revenue data available for this year</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Revenue Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Monthly Average Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">
                    {{ $monthlyRevenue->count() > 0 ? number_format($monthlyRevenue->avg('revenue'), 2) : '0.00' }} dh
                </div>
                <p class="text-sm font-medium text-gray-900">Monthly Average</p>
                <p class="text-xs text-gray-500">Revenue per month</p>
            </div>
        </div>

        <!-- Peak Month Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">
                    {{ $monthlyRevenue->count() > 0 ? number_format($monthlyRevenue->max('revenue'), 2) : '0.00' }} dh
                </div>
                <p class="text-sm font-medium text-gray-900">Peak Month</p>
                <p class="text-xs text-gray-500">Highest revenue month</p>
            </div>
        </div>

        <!-- Growth Rate -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600">
                    @if($monthlyRevenue->count() >= 2)
                        @php
                            $firstHalf = $monthlyRevenue->take(6)->sum('revenue');
                            $secondHalf = $monthlyRevenue->skip(6)->sum('revenue');
                            $growth = $firstHalf > 0 ? (($secondHalf - $firstHalf) / $firstHalf) * 100 : 0;
                        @endphp
                        {{ round($growth, 1) }}%
                    @else
                        0%
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-900">6-Month Growth</p>
                <p class="text-xs text-gray-500">Revenue growth trend</p>
            </div>
        </div>

        <!-- Reservation Frequency -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">
                    {{ $monthlyRevenue->count() > 0 ? round($monthlyRevenue->avg('reservations'), 1) : 0 }}
                </div>
                <p class="text-sm font-medium text-gray-900">Avg. Reservations</p>
                <p class="text-xs text-gray-500">Per month</p>
            </div>
        </div>
    </div>

    <!-- Top Performing Tenants -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Tenants</h3>
        
        @if($topPerformingTenants->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rank
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tenant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Revenue
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topPerformingTenants as $index => $tenant)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index < 3)
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                            {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                               ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800') }}">
                                            <i class="fas fa-trophy text-sm"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-gray-600">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">#{{ $index + 1 }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $tenant->company_name ?? $tenant->domain }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    {{ number_format($tenant->total_revenue ?? 0, 2) }} dh
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tenant->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('saas.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-900">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-chart-bar fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-500">No tenant revenue data available</p>
            </div>
        @endif
    </div>

    <!-- Revenue Insights -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Insights & Analysis</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Revenue Distribution -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Revenue Distribution</h4>
                <div class="space-y-3">
                    @if($monthlyRevenue->count() > 0)
                        @php
                            $totalRevenue = $monthlyRevenue->sum('revenue');
                            $avgRevenue = $monthlyRevenue->avg('revenue');
                            $aboveAvg = $monthlyRevenue->where('revenue', '>', $avgRevenue)->count();
                            $belowAvg = $monthlyRevenue->where('revenue', '<', $avgRevenue)->count();
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Above Average Months</span>
                            <span class="text-sm font-medium text-green-600">{{ $aboveAvg }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Below Average Months</span>
                            <span class="text-sm font-medium text-red-600">{{ $belowAvg }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Revenue</span>
                            <span class="text-sm font-medium text-blue-600">{{ number_format($totalRevenue, 2) }} dh</span>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No revenue data available for analysis</p>
                    @endif
                </div>
            </div>

            <!-- Performance Indicators -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Performance Indicators</h4>
                <div class="space-y-3">
                    @if($monthlyRevenue->count() > 0)
                        @php
                            $bestMonth = $monthlyRevenue->sortByDesc('revenue')->first();
                            $worstMonth = $monthlyRevenue->sortBy('revenue')->first();
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Best Month</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ $bestMonth ? date('F', mktime(0, 0, 0, $bestMonth->month, 1)) : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Worst Month</span>
                            <span class="text-sm font-medium text-red-600">
                                {{ $worstMonth ? date('F', mktime(0, 0, 0, $worstMonth->month, 1)) : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Revenue Volatility</span>
                            <span class="text-sm font-medium text-yellow-600">
                                @if($monthlyRevenue->count() > 1)
                                    {{ round((($bestMonth->revenue - $worstMonth->revenue) / $bestMonth->revenue) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No revenue data available for analysis</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Optimization Recommendations</h3>
        
        <div class="space-y-4">
            @if($monthlyRevenue->count() > 0)
                @php
                    $avgRevenue = $monthlyRevenue->avg('revenue');
                    $recentMonths = $monthlyRevenue->take(3);
                    $trendingUp = $recentMonths->count() >= 2 && $recentMonths->last()->revenue > $recentMonths->first()->revenue;
                @endphp

                @if($trendingUp)
                <div class="flex items-start p-4 bg-green-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-green-900">Positive Revenue Trend</h4>
                        <p class="text-sm text-green-700 mt-1">
                            Revenue is trending upward in recent months. Continue current strategies and consider scaling successful initiatives.
                        </p>
                    </div>
                </div>
                @else
                <div class="flex items-start p-4 bg-yellow-50 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-yellow-900">Revenue Growth Opportunity</h4>
                        <p class="text-sm text-yellow-700 mt-1">
                            Revenue growth has slowed. Consider implementing new pricing strategies, expanding services, or improving customer retention.
                        </p>
                    </div>
                </div>
                @endif

                @if($topPerformingTenants->count() > 0)
                <div class="flex items-start p-4 bg-blue-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-lightbulb text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-blue-900">Top Performer Analysis</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Analyze top-performing tenants to identify successful patterns and replicate them across other accounts.
                        </p>
                    </div>
                </div>
                @endif
            @else
                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-info-circle text-gray-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Data Collection Needed</h4>
                        <p class="text-sm text-gray-700 mt-1">
                            Start collecting revenue data to generate meaningful insights and recommendations for business growth.
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex items-start p-4 bg-purple-50 rounded-lg">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-1">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-purple-900">Strategic Actions</h4>
                    <p class="text-sm text-purple-700 mt-1">
                        Focus on customer acquisition, retention strategies, and pricing optimization to maximize revenue growth.
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
    // Monthly Revenue Chart
    @if($monthlyRevenue->count() > 0)
    const revenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }, {
                label: 'Reservations',
                data: {!! json_encode($monthlyRevenue->pluck('reservations')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (dh)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Reservations'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
    @endif
});
</script>
@endpush
