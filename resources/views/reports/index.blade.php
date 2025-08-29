@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Rapports & Analyses</h2>
            <p class="text-gray-600 text-lg">Tableaux de bord et analyses pour votre agence de location</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportAllReports()" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-download"></i>
                <span>Exporter tout</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus totaux</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} €</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-euro-sign text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +{{ $revenueGrowth }}% ce mois
                </span>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Locations actives</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeRentals }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-key text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600">
                    <i class="fas fa-car mr-1"></i>
                    {{ $availableVehicles }} véhicules disponibles
                </span>
            </div>
        </div>

        <!-- Fleet Utilization -->
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisation flotte</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fleetUtilization }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600">
                    <i class="fas fa-percentage mr-1"></i>
                    Moyenne mensuelle
                </span>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Satisfaction clients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customerSatisfaction }}/5</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600">
                    <i class="fas fa-users mr-1"></i>
                    {{ $totalCustomers }} clients
                </span>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Financial Reports -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-800">Rapports Financiers</h3>
            
            <!-- Revenue Report -->
            <div class="content-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-gray-800">Revenus par période</h4>
                    <select id="revenuePeriod" class="form-input text-sm px-3 py-2" onchange="updateRevenueChart()">
                        <option value="7">7 derniers jours</option>
                        <option value="30" selected>30 derniers jours</option>
                        <option value="90">3 derniers mois</option>
                        <option value="365">12 derniers mois</option>
                    </select>
                </div>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-600">Aujourd'hui</p>
                        <p class="font-semibold text-gray-900">{{ number_format($todayRevenue, 2) }} €</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cette semaine</p>
                        <p class="font-semibold text-gray-900">{{ number_format($weekRevenue, 2) }} €</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ce mois</p>
                        <p class="font-semibold text-gray-900">{{ number_format($monthRevenue, 2) }} €</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Performance -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Performance des véhicules</h4>
                <div class="space-y-3">
                    @foreach($topPerformingVehicles as $vehicle)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ ($vehicle->brand_name ?? '') . ' ' . ($vehicle->modele ?? 'N/A') }}</p>
                                <p class="text-sm text-gray-500">{{ $vehicle->immatriculation }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ number_format($vehicle->total_revenue, 2) }} €</p>
                            <p class="text-sm text-gray-500">{{ $vehicle->rental_count }} locations</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Operational Reports -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-800">Rapports Opérationnels</h3>
            
            <!-- Fleet Status -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Statut de la flotte</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Disponibles</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-green-600">{{ $fleetStatus['disponible'] }}</span>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">En location</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-blue-600">{{ $fleetStatus['en_location'] }}</span>
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">En maintenance</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-yellow-600">{{ $fleetStatus['en_maintenance'] }}</span>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Hors service</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-red-600">{{ $fleetStatus['hors_service'] }}</span>
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Vehicle Categories -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Catégories populaires</h4>
                <div class="space-y-3">
                    @foreach($popularCategories as $category)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $category->category_name ?? 'N/A' }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $category->percentage }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $category->percentage }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports Section -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Rapports Détaillés</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Analysis -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Analyse des clients</h4>
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $newCustomersThisMonth }}</p>
                        <p class="text-sm text-gray-600">Nouveaux clients ce mois</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $repeatCustomers }}</p>
                        <p class="text-sm text-gray-600">Clients fidèles</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $averageRentalDuration }}</p>
                        <p class="text-sm text-gray-600">Durée moyenne (jours)</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('reports.customers') }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-200">
                        Voir le rapport complet
                    </a>
                </div>
            </div>

            <!-- Maintenance Costs -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Coûts de maintenance</h4>
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600">{{ number_format($maintenanceCosts['monthly'], 2) }} €</p>
                        <p class="text-sm text-gray-600">Ce mois</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($maintenanceCosts['quarterly'], 2) }} €</p>
                        <p class="text-sm text-gray-600">Ce trimestre</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($maintenanceCosts['yearly'], 2) }} €</p>
                        <p class="text-sm text-gray-600">Cette année</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('reports.maintenance') }}" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-200">
                        Voir le rapport complet
                    </a>
                </div>
            </div>

            <!-- Seasonal Trends -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Tendances saisonnières</h4>
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $seasonalTrends['peak_month'] }}</p>
                        <p class="text-sm text-gray-600">Mois de pointe</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $seasonalTrends['low_month'] }}</p>
                        <p class="text-sm text-gray-600">Mois creux</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $seasonalTrends['peak_factor'] }}x</p>
                        <p class="text-sm text-gray-600">Facteur de pointe</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('reports.seasonal') }}" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-200">
                        Voir le rapport complet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="mt-8 content-card p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Options d'export</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="exportReport('financial')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-file-excel mr-2"></i>Rapport financier
            </button>
            <button onclick="exportReport('operational')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-file-excel mr-2"></i>Rapport opérationnel
            </button>
            <button onclick="exportReport('customers')" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-file-excel mr-2"></i>Rapport clients
            </button>
            <button onclick="exportReport('maintenance')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-file-excel mr-2"></i>Rapport maintenance
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
let revenueChart;

function initRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($revenueChartData['labels']),
            datasets: [{
                label: 'Revenus (€)',
                data: @json($revenueChartData['data']),
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
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });
}

function updateRevenueChart() {
    const period = document.getElementById('revenuePeriod').value;
    // Here you would typically make an AJAX call to get new data
    // For now, we'll just show a loading state
    console.log('Updating chart for period:', period);
}

function exportReport(type) {
    // Implementation for exporting specific report types
    console.log('Exporting report:', type);
    // You would typically make an AJAX call to generate and download the report
}

function exportAllReports() {
    // Implementation for exporting all reports
    console.log('Exporting all reports');
    // You would typically make an AJAX call to generate and download all reports
}

// Initialize chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    initRevenueChart();
});
</script>
@endsection
