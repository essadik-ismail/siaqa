@extends('layouts.app')

@section('title', 'Réservations')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des réservations</h2>
            <p class="text-gray-600">Gérez les réservations et réservations de véhicules</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reservations.statistics') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-medium flex items-center space-x-2">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques</span>
            </a>
            <a href="{{ route('reservations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Nouvelle réservation</span>
            </a>
        </div>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['confirmees'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['en_attente'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['annulees'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-flag-checkered text-2xl text-gray-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Terminées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['terminees'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" id="searchInput" placeholder="N° réservation, client, véhicule..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div id="searchIndicator" class="hidden mt-1">
                    <div class="flex items-center text-sm text-gray-500">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500 mr-2"></div>
                        Recherche en cours...
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select id="statutFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="confirmee">Confirmée</option>
                    <option value="annulee">Annulée</option>
                    <option value="terminee">Terminée</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                <select id="clientFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-select">
                    <option value="">Tous les clients</option>
                    <option value="blacklisted">Clients blacklistés</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->nom }} {{ $client->prenom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Véhicule</label>
                <select id="vehiculeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-select">
                    <option value="">Tous les véhicules</option>
                    @foreach($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}">{{ $vehicule->marque->marque }} {{ $vehicule->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                <input type="date" id="dateDebutFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-select">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                <input type="date" id="dateFinFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-select">
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div id="activeFilters" class="mt-4 flex flex-wrap gap-2 hidden">
            <span class="text-sm text-gray-600">Filtres actifs:</span>
        </div>
        
        <!-- Clear Filters Button -->
        <div class="mt-4 flex justify-between items-center">
            <button id="clearFilters" class="text-blue-600 hover:text-blue-800 text-sm font-medium hidden">
                <i class="fas fa-times mr-1"></i>Effacer tous les filtres
            </button>
            <div class="text-sm text-gray-500">
                <span id="resultsCount">{{ $reservations->total() }}</span> réservation(s) trouvée(s)
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50 {{ $reservation->client->isBlacklisted() ? 'blacklist-warning' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $reservation->numero_reservation }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 {{ $reservation->client->isBlacklisted() ? 'bg-red-300' : 'bg-gray-300' }} rounded-full flex items-center justify-center mr-3">
                                        <i class="fas {{ $reservation->client->isBlacklisted() ? 'fa-ban text-red-600' : 'fa-user text-gray-600' }} text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reservation->client->full_name }}
                                            </div>
                                            @if($reservation->client->isBlacklisted())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium blacklist-badge">
                                                    <i class="fas fa-ban mr-1"></i>Blacklisté
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $reservation->client->email }}</div>
                                        @if($reservation->client->isBlacklisted())
                                            <div class="mt-1 p-3 blacklist-info">
                                                <div class="text-xs text-red-700 font-bold mb-2 flex items-center">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Client Blacklisté
                                                </div>
                                                <div class="text-xs text-red-600 space-y-1">
                                                    <div><strong>Nom complet:</strong> {{ $reservation->client->full_name }}</div>
                                                    <div><strong>Email:</strong> {{ $reservation->client->email }}</div>
                                                    <div><strong>Téléphone:</strong> {{ $reservation->client->telephone ?? 'N/A' }}</div>
                                                    <div><strong>Adresse:</strong> {{ $reservation->client->adresse ?? 'N/A' }}</div>
                                                    @if($reservation->client->motif_blacklist)
                                                        <div><strong>Motif:</strong> {{ $reservation->client->motif_blacklist }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $reservation->vehicule->marque->marque }} {{ $reservation->vehicule->name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $reservation->vehicule->immatriculation }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $reservation->date_debut->format('d/m') }} - {{ $reservation->date_fin->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $reservation->date_debut->diffInDays($reservation->date_fin) }} jour(s)
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($reservation->prix_total, 2) }} DH</div>
                                @if($reservation->caution > 0)
                                <div class="text-sm text-gray-500">Caution: {{ number_format($reservation->caution, 2) }} DH</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reservation->statut === 'en_attente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @elseif($reservation->statut === 'confirmee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-check mr-1"></i>Confirmée
                                    </span>
                                @elseif($reservation->statut === 'terminee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-flag-checkered mr-1"></i>Terminée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Annulée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('reservations.show', $reservation) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reservations.edit', $reservation) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($reservation->statut === 'en_attente')
                                        <button onclick="showConfirmModal({{ $reservation->id }}, '{{ $reservation->numero_reservation }}')" 
                                                class="text-green-600 hover:text-green-900" title="Confirmer">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($reservation->statut !== 'annulee' && $reservation->statut !== 'terminee')
                                        <button onclick="showCancelModal({{ $reservation->id }}, '{{ $reservation->numero_reservation }}')" 
                                                class="text-red-600 hover:text-red-900" title="Annuler">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-calendar-check text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">Aucune réservation trouvée</p>
                                    <p class="text-sm">Commencez par créer votre première réservation.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>

    <!-- Confirm Reservation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm hidden z-50 transition-all duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="confirmModalContent" class="bg-white rounded-xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Confirmer la réservation</h3>
                    <p class="text-gray-600 text-center mb-6">Êtes-vous sûr de vouloir confirmer la réservation <span id="confirmReservationNumber" class="font-semibold"></span> ?</p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700">Cette action changera le statut de la réservation à "Confirmée"</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button onclick="hideConfirmModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </button>
                        <form id="confirmForm" method="POST" class="flex-1" onsubmit="console.log('Form submitting with method:', this.method, 'to action:', this.action);">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors duration-200">
                                Confirmer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Reservation Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm hidden z-50 transition-all duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="cancelModalContent" class="bg-white rounded-xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-times text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Annuler la réservation</h3>
                    <p class="text-gray-600 text-center mb-6">Êtes-vous sûr de vouloir annuler la réservation <span id="cancelReservationNumber" class="font-semibold"></span> ?</p>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700">Cette action est irréversible et changera le statut à "Annulée"</span>
                        </div>
                    </div>
                    
                    <form id="cancelForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'annulation *</label>
                            <textarea name="motif_annulation" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Veuillez fournir un motif d'annulation..."></textarea>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideCancelModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                                Annuler
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors duration-200">
                                Confirmer l'annulation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Modal animations */
.modal-enter {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal-exit {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

/* Button animations */
.btn-primary {
    transition: all 0.2s ease-in-out;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Modal content animations */
.modal-content-enter {
    animation: modalContentEnter 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes modalContentEnter {
    from {
        transform: scale(0.95) translateY(10px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* Icon animations */
.modal-icon {
    animation: iconBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes iconBounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0, 0, 0);
    }
    40%, 43% {
        transform: translate3d(0, -8px, 0);
    }
    70% {
        transform: translate3d(0, -4px, 0);
    }
    90% {
        transform: translate3d(0, -2px, 0);
    }
}

/* Enhanced shadows */
.modal-shadow {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive modal sizing */
@media (max-width: 640px) {
    .modal-content {
        margin: 1rem;
        max-width: calc(100vw - 2rem);
    }
}

/* Loading spinner */
.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Pulse animation for loading states */
.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

/* Blacklist warning styles */
.blacklist-warning {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #dc2626;
    animation: blacklistPulse 2s ease-in-out infinite;
}

@keyframes blacklistPulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(220, 38, 38, 0);
    }
}

.blacklist-info {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fca5a5;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
}

.blacklist-badge {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
}
</style>
@endpush

@push('scripts')
<script>
// Debounce function for search input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Function to update URL with current filters
function updateURL(filters) {
    const url = new URL(window.location);
    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            url.searchParams.set(key, filters[key]);
        } else {
            url.searchParams.delete(key);
        }
    });
    window.history.pushState({}, '', url);
}

// Function to get current filters
function getCurrentFilters() {
    return {
        search: document.getElementById('searchInput').value,
        statut: document.getElementById('statutFilter').value,
        client_id: document.getElementById('clientFilter').value,
        vehicule_id: document.getElementById('vehiculeFilter').value,
        date_debut: document.getElementById('dateDebutFilter').value,
        date_fin: document.getElementById('dateFinFilter').value
    };
}

// Function to update active filters display
function updateActiveFilters(filters) {
    const activeFiltersDiv = document.getElementById('activeFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    let hasActiveFilters = false;
    let filterHTML = '<span class="text-sm text-gray-600">Filtres actifs:</span>';
    
    Object.entries(filters).forEach(([key, value]) => {
        if (value) {
            hasActiveFilters = true;
            let label = '';
            let displayValue = value;
            
            // Get human-readable labels
            switch(key) {
                case 'statut':
                    label = 'Statut';
                    const statutLabels = {
                        'en_attente': 'En attente',
                        'confirmee': 'Confirmée',
                        'annulee': 'Annulée',
                        'terminee': 'Terminée'
                    };
                    displayValue = statutLabels[value] || value;
                    break;
                case 'client_id':
                    label = 'Client';
                    if (value === 'blacklisted') {
                        displayValue = 'Clients blacklistés';
                    } else {
                        const clientSelect = document.getElementById('clientFilter');
                        const clientOption = clientSelect.querySelector(`option[value="${value}"]`);
                        displayValue = clientOption ? clientOption.textContent : value;
                    }
                    break;
                case 'vehicule_id':
                    label = 'Véhicule';
                    const vehiculeSelect = document.getElementById('vehiculeFilter');
                    const vehiculeOption = vehiculeSelect.querySelector(`option[value="${value}"]`);
                    displayValue = vehiculeOption ? vehiculeOption.textContent : value;
                    break;
                case 'date_debut':
                    label = 'Date début';
                    break;
                case 'date_fin':
                    label = 'Date fin';
                    break;
                case 'search':
                    label = 'Recherche';
                    break;
                default:
                    label = key;
            }
            
            filterHTML += `
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    ${label}: ${displayValue}
                    <button onclick="clearFilter('${key}')" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            `;
        }
    });
    
    if (hasActiveFilters) {
        activeFiltersDiv.innerHTML = filterHTML;
        activeFiltersDiv.classList.remove('hidden');
        clearFiltersBtn.classList.remove('hidden');
    } else {
        activeFiltersDiv.classList.add('hidden');
        clearFiltersBtn.classList.add('hidden');
    }
}

// Function to clear a specific filter
function clearFilter(filterName) {
    switch(filterName) {
        case 'search':
            document.getElementById('searchInput').value = '';
            break;
        case 'statut':
            document.getElementById('statutFilter').value = '';
            break;
        case 'client_id':
            document.getElementById('clientFilter').value = '';
            break;
        case 'vehicule_id':
            document.getElementById('vehiculeFilter').value = '';
            break;
        case 'date_debut':
            document.getElementById('dateDebutFilter').value = '';
            break;
        case 'date_fin':
            document.getElementById('dateFinFilter').value = '';
            break;
    }
    performSearch();
}

// Function to clear all filters
function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statutFilter').value = '';
    document.getElementById('clientFilter').value = '';
    document.getElementById('vehiculeFilter').value = '';
    document.getElementById('dateDebutFilter').value = '';
    document.getElementById('dateFinFilter').value = '';
    performSearch();
}

// Function to perform search and update results
function performSearch() {
    const filters = getCurrentFilters();
    const searchIndicator = document.getElementById('searchIndicator');
    
    // Show loading indicator
    searchIndicator.classList.remove('hidden');
    
    // Update URL
    updateURL(filters);
    
    // Build query string
    const queryString = Object.entries(filters)
        .filter(([key, value]) => value)
        .map(([key, value]) => `${key}=${encodeURIComponent(value)}`)
        .join('&');
    
    // Fetch results
    fetch(`{{ route('reservations.index') }}?${queryString}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create a temporary div to parse the HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Update the reservations table
        const tableBody = document.querySelector('tbody');
        if (tableBody) {
            const newTableBody = tempDiv.querySelector('tbody');
            if (newTableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
            }
        }
        
        // Update pagination
        const pagination = document.querySelector('.bg-white.px-4.py-3.border-t');
        if (pagination) {
            const newPagination = tempDiv.querySelector('.bg-white.px-4.py-3.border-t');
            if (newPagination) {
                pagination.innerHTML = newPagination.innerHTML;
            }
        }
        
        // Update results count
        const resultsCount = document.getElementById('resultsCount');
        const newResultsCount = tempDiv.querySelector('#resultsCount');
        if (resultsCount && newResultsCount) {
            resultsCount.textContent = newResultsCount.textContent;
        }
        
        // Update active filters display
        updateActiveFilters(filters);
        
        // Hide loading indicator
        searchIndicator.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        searchIndicator.classList.add('hidden');
    });
}

// Debounced search function
const debouncedSearch = debounce(performSearch, 300);

// Modal functions
function showConfirmModal(reservationId, reservationNumber) {
    const form = document.getElementById('confirmForm');
    form.action = `/reservations/${reservationId}/confirm`;
    console.log('Setting form action to:', form.action, 'method:', form.method);
    document.getElementById('confirmReservationNumber').textContent = reservationNumber;
    
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('confirmModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate in
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Focus on cancel button for accessibility
    setTimeout(() => {
        modal.querySelector('button[onclick="hideConfirmModal()"]').focus();
    }, 100);
}

function hideConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('confirmModalContent');
    
    // Animate out
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function showCancelModal(reservationId, reservationNumber) {
    document.getElementById('cancelForm').action = `/reservations/${reservationId}/cancel`;
    document.getElementById('cancelReservationNumber').textContent = reservationNumber;
    
    const modal = document.getElementById('cancelModal');
    const modalContent = document.getElementById('cancelModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate in
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Focus on cancel button for accessibility
    setTimeout(() => {
        modal.querySelector('button[onclick="hideCancelModal()"]').focus();
    }, 100);
}

function hideCancelModal() {
    const modal = document.getElementById('cancelModal');
    const modalContent = document.getElementById('cancelModalContent');
    
    // Animate out
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search input
    document.getElementById('searchInput').addEventListener('input', debouncedSearch);
    
    // Filter selects and inputs
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', performSearch);
    });
    
    // Clear filters button
    document.getElementById('clearFilters').addEventListener('click', clearAllFilters);
    
    // Initialize active filters display
    updateActiveFilters(getCurrentFilters());
    
    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        document.getElementById('searchInput').value = urlParams.get('search');
    }
    if (urlParams.get('statut')) {
        document.getElementById('statutFilter').value = urlParams.get('statut');
    }
    if (urlParams.get('client_id')) {
        document.getElementById('clientFilter').value = urlParams.get('client_id');
    }
    if (urlParams.get('vehicule_id')) {
        document.getElementById('vehiculeFilter').value = urlParams.get('vehicule_id');
    }
    if (urlParams.get('date_debut')) {
        document.getElementById('dateDebutFilter').value = urlParams.get('date_debut');
    }
    if (urlParams.get('date_fin')) {
        document.getElementById('dateFinFilter').value = urlParams.get('date_fin');
    }
    
    // Update active filters display with initial values
    updateActiveFilters(getCurrentFilters());
    
    // Keyboard navigation for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('confirmModal').classList.contains('hidden')) {
                hideConfirmModal();
            }
            if (!document.getElementById('cancelModal').classList.contains('hidden')) {
                hideCancelModal();
            }
        }
    });
});
</script>
@endpush 