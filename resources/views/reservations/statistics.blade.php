@extends('layouts.app')

@section('title', 'Statistiques des Réservations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Statistiques des Réservations</h1>
        <a href="{{ route('reservations.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux Réservations
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des Réservations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_reservations']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Attente</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['en_attente']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confirmées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['confirmees']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-play-circle text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Cours</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['en_cours']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-flag-checkered text-2xl text-gray-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Terminées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['terminees']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-2xl text-red-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Annulées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['annulees']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-indigo-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ce Mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ce_mois']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-euro-sign text-2xl text-emerald-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Revenus Ce Mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ce_mois_revenus'], 2) }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par Statut</h3>
            <div class="relative chart-container" style="height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendances Mensuelles</h3>
            <div class="relative chart-container" style="height: 300px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Métriques de Performance</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Confirmation Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ $stats['total_reservations'] > 0 ? number_format(($stats['confirmees'] / $stats['total_reservations']) * 100, 1) : 0 }}%
                </div>
                <p class="text-sm text-gray-600">Taux de Confirmation</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['confirmees'] }} / {{ $stats['total_reservations'] }} réservations</p>
            </div>

            <!-- Completion Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">
                    {{ $stats['confirmees'] > 0 ? number_format(($stats['terminees'] / $stats['confirmees']) * 100, 1) : 0 }}%
                </div>
                <p class="text-sm text-gray-600">Taux de Finalisation</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['terminees'] }} / {{ $stats['confirmees'] }} confirmées</p>
            </div>

            <!-- Cancellation Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600 mb-2">
                    {{ $stats['total_reservations'] > 0 ? number_format(($stats['annulees'] / $stats['total_reservations']) * 100, 1) : 0 }}%
                </div>
                <p class="text-sm text-gray-600">Taux d'Annulation</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['annulees'] }} / {{ $stats['total_reservations'] }} réservations</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-plus text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Nouvelles Réservations</p>
                        <p class="text-sm text-gray-500">{{ $stats['ce_mois'] }} ce mois</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['ce_mois'] }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-euro-sign text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Revenus Générés</p>
                        <p class="text-sm text-gray-500">Ce mois-ci</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['ce_mois_revenus'], 2) }} DH</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">En Attente</p>
                        <p class="text-sm text-gray-500">Réservations en attente de confirmation</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['en_attente'] }}</p>
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
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['En Attente', 'Confirmées', 'En Cours', 'Terminées', 'Annulées'],
            datasets: [{
                data: [
                    {{ $stats['en_attente'] }},
                    {{ $stats['confirmees'] }},
                    {{ $stats['en_cours'] }},
                    {{ $stats['terminees'] }},
                    {{ $stats['annulees'] }}
                ],
                backgroundColor: [
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(147, 51, 234, 0.8)',
                    'rgba(107, 114, 128, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(251, 191, 36, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(147, 51, 234, 1)',
                    'rgba(107, 114, 128, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Monthly Trends Chart (simplified - you can enhance this with real monthly data)
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Réservations',
                data: [0, 0, 0, 0, 0, 0, 0, 0, {{ $stats['ce_mois'] }}, 0, 0, 0], // Only current month has data
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush




