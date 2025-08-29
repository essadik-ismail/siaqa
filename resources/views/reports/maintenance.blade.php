@extends('layouts.app')

@section('title', 'Rapport de Maintenance')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Rapport de Maintenance</h2>
            <p class="text-gray-600 text-lg">Suivi des coûts et interventions de maintenance</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportReport('maintenance')" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-download"></i>
                <span>Exporter</span>
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Maintenance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Coût ce mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyCosts[date('n')] ?? 0, 2) }} €</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Coût ce trimestre</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(array_sum(array_slice($monthlyCosts, floor((date('n')-1)/3)*3, 3)), 2) }} €</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Coût cette année</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(array_sum($monthlyCosts), 2) }} €</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Costs Chart -->
    <div class="content-card p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Coûts mensuels</h3>
        <div class="h-64">
            <canvas id="monthlyCostsChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Vehicle Maintenance Summary -->
    <div class="content-card overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">Résumé par véhicule</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interventions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût moyen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicleMaintenanceSummary as $summary)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-car text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ ($summary['brand_name'] ?? '') . ' ' . ($summary['modele'] ?? 'N/A') }}</div>
                                        <div class="text-sm text-gray-500">{{ $summary['immatriculation'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $summary['maintenance_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($summary['total_cost'] ?? 0, 2) }} €</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if(($summary['maintenance_count'] ?? 0) > 0)
                                    {{ number_format(($summary['total_cost'] ?? 0) / ($summary['maintenance_count'] ?? 1), 2) }} €
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-tools text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-400">Aucune maintenance trouvée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Maintenance Records -->
    <div class="content-card overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">Historique des interventions</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($maintenanceRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->date ? \Carbon\Carbon::parse($record->date)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fas fa-car text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ ($record->vehicule->marque->nom ?? '') . ' ' . ($record->vehicule->modele ?? 'N/A') }}</div>
                                        <div class="text-sm text-gray-500">{{ $record->vehicule->immatriculation ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->designation ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($record->montant ?? 0, 2) }} €</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($record->statut == 'termine')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Terminé
                                    </span>
                                @elseif($record->statut == 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En cours
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($record->statut ?? 'N/A') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-tools text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-400">Aucune intervention trouvée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($maintenanceRecords->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $maintenanceRecords->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Costs Chart
let monthlyCostsChart;

function initMonthlyCostsChart() {
    const ctx = document.getElementById('monthlyCostsChart').getContext('2d');
    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    monthlyCostsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Coûts (€)',
                data: @json(array_values($monthlyCosts)),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
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
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });
}

function exportReport(type) {
    // Implementation for exporting maintenance report
    console.log('Exporting maintenance report');
    // You would typically make an AJAX call to generate and download the report
}

// Initialize chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMonthlyCostsChart();
});
</script>
@endsection
