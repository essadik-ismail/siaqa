@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <!-- Enhanced Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Clients Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Clients</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_clients']) }}</p>
                    <p class="text-green-100 text-xs mt-1">+12% par rapport au mois dernier</p>
                </div>
                <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Reservations Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Réservations</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_reservations']) }}</p>
                    <p class="text-blue-100 text-xs mt-1">+8% par rapport au mois dernier</p>
                </div>
                <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Revenus Totaux</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_revenue']) }} DH</p>
                    <p class="text-red-100 text-xs mt-1">+15% par rapport au mois dernier</p>
                </div>
                <div class="w-12 h-12 bg-red-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Vehicles Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Véhicules</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_vehicles'] ?? 0) }}</p>
                    <p class="text-purple-100 text-xs mt-1">{{ $stats['available_vehicles'] ?? 0 }} disponibles</p>
                </div>
                <div class="w-12 h-12 bg-purple-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-car text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Utilization Rate -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Taux d'Utilisation</h3>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-blue-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['utilization_rate'] ?? 75 }}%</div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['utilization_rate'] ?? 75 }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Utilisation des véhicules</p>
        </div>

        <!-- Average Rental Duration -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Durée Moyenne de Location</h3>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['avg_rental_duration'] ?? 5 }} jours</div>
            <p class="text-sm text-gray-600">par location</p>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Satisfaction Client</h3>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['satisfaction_rate'] ?? 4.8 }}/5</div>
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-yellow-400 {{ $i <= ($stats['satisfaction_rate'] ?? 4.8) ? '' : 'opacity-30' }}"></i>
                @endfor
            </div>
        </div>
    </div>

    <!-- Admin Management Section -->
    @if(auth()->user()->isSuperAdmin())
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Administration</h2>
            <div class="text-sm text-gray-600">Système</div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- User Management -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.users.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-blue-100 text-sm">Utilisateurs</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Gestion des Utilisateurs</h3>
                <p class="text-blue-100 text-sm">Gérer les utilisateurs, rôles et permissions</p>
                <div class="mt-4 flex items-center text-blue-100 text-sm">
                    <span>Gérer les Utilisateurs</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Agency Management -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.agencies.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</div>
                        <div class="text-green-100 text-sm">Agences</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Gestion des Agences</h3>
                <p class="text-green-100 text-sm">Gérer les agences de location</p>
                <div class="mt-4 flex items-center text-green-100 text-sm">
                    <span>Gérer les Agences</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Role Management -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.roles.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_roles'] ?? 0 }}</div>
                        <div class="text-purple-100 text-sm">Rôles</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Gestion des Rôles</h3>
                <p class="text-purple-100 text-sm">Définir et gérer les rôles utilisateur</p>
                <div class="mt-4 flex items-center text-purple-100 text-sm">
                    <span>Gérer les Rôles</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Permission Management -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.permissions.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_permissions'] ?? 0 }}</div>
                        <div class="text-orange-100 text-sm">Permissions</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Gestion des Permissions</h3>
                <p class="text-orange-100 text-sm">Configurer les permissions système</p>
                <div class="mt-4 flex items-center text-orange-100 text-sm">
                    <span>Gérer les Permissions</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Ajouter un Nouvel Utilisateur
                </a>
                <a href="{{ route('admin.agencies.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-building mr-2"></i>
                    Ajouter une Nouvelle Agence
                </a>
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                    <i class="fas fa-user-shield mr-2"></i>
                    Créer un Nouveau Rôle
                </a>
                <a href="{{ route('admin.bulk-create') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <i class="fas fa-key mr-2"></i>
                    Créer des Permissions en Masse
                </a>
                <a href="{{ route('admin.car-selection.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                    <i class="fas fa-car mr-2"></i>
                    Gérer les Voitures de Débarquement
                </a>
                <a href="{{ route('saas.system-diagnostics') }}" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-heartbeat mr-2"></i>
                    Diagnostics Système
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activity & Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Activité Récente</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir Tout</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Nouvelle Réservation</p>
                        <p class="text-xs text-gray-500">Client a loué un véhicule</p>
                    </div>
                    <span class="text-xs text-gray-400">2m il y a</span>
                </div>
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Nouveau Client</p>
                        <p class="text-xs text-gray-500">Client enregistré</p>
                    </div>
                    <span class="text-xs text-gray-400">15m il y a</span>
                </div>
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-car text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Véhicule Rendu</p>
                        <p class="text-xs text-gray-500">Véhicule disponible</p>
                    </div>
                    <span class="text-xs text-gray-400">1h il y a</span>
                </div>
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Maintenance Due</p>
                        <p class="text-xs text-gray-500">Véhicule nécessite un service</p>
                    </div>
                    <span class="text-xs text-gray-400">3h il y a</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Statistiques Rapides</h3>
                <i class="fas fa-chart-bar text-gray-400"></i>
            </div>
            <div class="space-y-6">
                <!-- Today's Revenue -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Revenus d'Aujourd'hui</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($stats['todays_revenue'] ?? 1250) }} DH</span>
                </div>

                <!-- Active Reservations -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Réservations Actives</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['active_reservations'] ?? 12 }}</span>
                </div>

                <!-- Available Vehicles -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Véhicules Disponibles</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['available_vehicles'] ?? 8 }}</span>
                </div>

                <!-- Pending Approvals -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Approbations en Attente</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['pending_approvals'] ?? 3 }}</span>
                </div>

                <!-- Maintenance Alerts -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wrench text-red-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Alertes de Maintenance</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['maintenance_alerts'] ?? 2 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex space-x-1 mb-6">
            <button id="vehicles-tab" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium tab-button active" data-tab="vehicles">Véhicules</button>
            <button id="reservations-tab" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg tab-button" data-tab="reservations">Réservations</button>
            <button id="contracts-tab" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg tab-button" data-tab="contracts">Contrats</button>
        </div>

        <!-- Tab Content -->
        <div id="tab-content">
            <!-- Vehicles Tab Content -->
            <div id="vehicles-content" class="tab-panel">
                <!-- Vehicle Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Revenus Estimés Totaux</h3>
                            <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold">{{ $stats['estimated_utilization'] ?? 0 }}%</span>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($stats['estimated_revenue']) }} DH</p>
                        <div class="mt-4">
                            <div class="w-full bg-blue-400 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: {{ $stats['estimated_utilization'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Revenus Réels Totaux</h3>
                            <div class="w-16 h-16 bg-green-400 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold">{{ $stats['actual_utilization'] ?? 0 }}%</span>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($stats['actual_revenue']) }} DH</p>
                        <div class="mt-4">
                            <div class="w-full bg-green-400 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: {{ $stats['actual_utilization'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Vehicles -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Véhicules Récents</h3>
                    @include('dashboard.partials.vehicles', ['data' => $recentVehicles])
                </div>
            </div>

            <!-- Reservations Tab Content -->
            <div id="reservations-content" class="tab-panel hidden">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Réservations Récentes</h3>
                    @include('dashboard.partials.reservations', ['data' => $recentReservations])
                </div>
            </div>

            <!-- Contracts Tab Content -->
            <div id="contracts-content" class="tab-panel hidden">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contrats Récents</h3>
                    @include('dashboard.partials.contracts', ['data' => $recentContracts])
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Rapport de Revenus Mensuels</h3>
            <div class="flex space-x-4 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span>Revenus</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span>Réservations</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span>Croissance</span>
                </div>
            </div>
        </div>

        <div class="relative chart-container">
            <canvas id="revenueChart"></canvas>
            <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-medium">
                {{ number_format($stats['current_month_revenue']) }} DH
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    <!-- Sidebar content can be added here if needed -->
@endsection

@push('scripts')
<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });
            
            // Add active class to clicked button
            this.classList.add('bg-blue-500', 'text-white');
            this.classList.remove('text-gray-600', 'hover:bg-gray-100');
            
            // Hide all panels
            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Show target panel
            const targetPanel = document.getElementById(targetTab + '-content');
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }
            
            // Load data for the tab if it's not vehicles (which loads by default)
            if (targetTab !== 'vehicles') {
                loadTabData(targetTab);
            }
        });
    });
});

// Load tab data via AJAX
function loadTabData(tab) {
    const contentDiv = document.getElementById(tab + '-content');
    if (contentDiv && !contentDiv.dataset.loaded) {
        fetch(`{{ route('dashboard.tab-data') }}?tab=${tab}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentDiv.innerHTML = data.html;
                    contentDiv.dataset.loaded = 'true';
                }
            })
            .catch(error => {
                console.error('Error loading tab data:', error);
            });
    }
}

// Revenue Chart with real data
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: @json($chartData['months']),
        datasets: [{
            label: 'Revenue',
            data: @json($chartData['revenue']),
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1
        }, {
            label: 'Reservations',
            data: @json($chartData['reservations']),
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' DH';
                    }
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

</script>
@endpush 