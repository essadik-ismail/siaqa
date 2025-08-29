@extends('layouts.app')

@section('title', 'Tendances Saisonnières')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Tendances Saisonnières</h2>
            <p class="text-gray-600 text-lg">Analyse des patterns de location par saison</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportReport('seasonal')" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-download"></i>
                <span>Exporter</span>
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Seasonal Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Mois de pointe</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seasonalData[array_search(max(array_column($seasonalData, 'rentals')), array_column($seasonalData, 'rentals'))]['month_name'] ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Mois creux</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seasonalData[array_search(min(array_column($seasonalData, 'rentals')), array_column($seasonalData, 'rentals'))]['month_name'] ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Facteur de pointe</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @php
                            $maxRentals = max(array_column($seasonalData, 'rentals'));
                            $minRentals = min(array_column($seasonalData, 'rentals'));
                            $peakFactor = $minRentals > 0 ? round($maxRentals / $minRentals, 1) : 1;
                        @endphp
                        {{ $peakFactor }}x
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    <div class="content-card p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Évolution mensuelle des locations</h3>
        <div class="h-64">
            <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Yearly Comparison -->
    <div class="content-card p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Comparaison année par année</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Année en cours</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Revenus totaux</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($yearlyComparison['current_year']['total_revenue'] ?? 0, 2) }} €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nombre de locations</span>
                        <span class="text-sm font-medium text-gray-900">{{ $yearlyComparison['current_year']['total_rentals'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nouveaux clients</span>
                        <span class="text-sm font-medium text-gray-900">{{ $yearlyComparison['current_year']['new_customers'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Année précédente</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Revenus totaux</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($yearlyComparison['last_year']['total_revenue'] ?? 0, 2) }} €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nombre de locations</span>
                        <span class="text-sm font-medium text-gray-900">{{ $yearlyComparison['last_year']['total_rentals'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nouveaux clients</span>
                        <span class="text-sm font-medium text-gray-900">{{ $yearlyComparison['last_year']['new_customers'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Growth Indicators -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-md font-medium text-gray-700 mb-3">Croissance</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Revenus</p>
                    <p class="text-lg font-semibold {{ ($yearlyComparison['growth']['total_revenue'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($yearlyComparison['growth']['total_revenue'] ?? 0) >= 0 ? '+' : '' }}{{ $yearlyComparison['growth']['total_revenue'] ?? 0 }}%
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Locations</p>
                    <p class="text-lg font-semibold {{ ($yearlyComparison['growth']['total_rentals'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($yearlyComparison['growth']['total_rentals'] ?? 0) >= 0 ? '+' : '' }}{{ $yearlyComparison['growth']['total_rentals'] ?? 0 }}%
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Clients</p>
                    <p class="text-lg font-semibold {{ ($yearlyComparison['growth']['new_customers'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($yearlyComparison['growth']['new_customers'] ?? 0) >= 0 ? '+' : '' }}{{ $yearlyComparison['growth']['new_customers'] ?? 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="content-card overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">Détail mensuel</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($seasonalData as $month => $monthData)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $monthData['month_name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $monthData['rentals'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($monthData['revenue'] ?? 0, 2) }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $maxRentals = max(array_column($seasonalData, 'rentals'));
                                    $percentage = $maxRentals > 0 ? round(($monthData['rentals'] / $maxRentals) * 100, 1) : 0;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trends Chart
let monthlyTrendsChart;

function initMonthlyTrendsChart() {
    const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    monthlyTrendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Locations',
                data: @json(array_values(array_column($seasonalData, 'rentals'))),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }, {
                label: 'Revenus (€)',
                data: @json(array_values(array_column($seasonalData, 'revenue'))),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
                    display: true
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de locations'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenus (€)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

function exportReport(type) {
    // Implementation for exporting seasonal report
    console.log('Exporting seasonal report');
    // You would typically make an AJAX call to generate and download the report
}

// Initialize chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMonthlyTrendsChart();
});
</script>
@endsection
