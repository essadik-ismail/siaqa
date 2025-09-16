@extends('layouts.app')

@section('title', 'Contrats')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des contrats</h1>
        <a href="{{ route('contrats.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nouveau Contrat
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-contract text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des Contrats</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $contrats->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Cours</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $contrats->where('etat_contrat', 'en cours')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-flag-checkered text-2xl text-gray-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Terminés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $contrats->where('etat_contrat', 'termine')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-euro-sign text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Valeur Totale</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($contrats->sum('prix'), 2) }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" id="searchInput" placeholder="N° contrat, client, véhicule..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div id="searchIndicator" class="hidden mt-1">
                    <div class="flex items-center text-sm text-gray-500">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500 mr-2"></div>
                        Recherche en cours...
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">État</label>
                <select id="etatFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 filter-select">
                    <option value="">Tous les états</option>
                    <option value="en cours">En cours</option>
                    <option value="termine">Terminé</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                <select id="clientFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 filter-select">
                    <option value="">Tous les clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->nom }} {{ $client->prenom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Véhicule</label>
                <select id="vehiculeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 filter-select">
                    <option value="">Tous les véhicules</option>
                    @foreach($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}">{{ $vehicule->marque->marque }} {{ $vehicule->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                <input type="date" id="dateDebutFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 filter-select">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                <input type="date" id="dateFinFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 filter-select">
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
                <span id="resultsCount">{{ $contrats->total() }}</span> contrat(s) trouvé(s)
            </div>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'number_contrat', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                N° Contrat
                                @if(request('sort_by') == 'number_contrat')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client(s)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Contrat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contrats as $contrat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $contrat->number_contrat }}
                            <br><span class="text-xs text-gray-500">{{ $contrat->numero_document }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <div class="font-medium">{{ $contrat->clientOne->nom }} {{ $contrat->clientOne->prenom }}</div>
                                @if($contrat->clientTwo)
                                    <div class="text-xs text-gray-500">+ {{ $contrat->clientTwo->nom }} {{ $contrat->clientTwo->prenom }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $contrat->vehicule->marque->marque }} {{ $contrat->vehicule->name }}
                            <br><span class="text-xs text-gray-500">{{ $contrat->vehicule->immatriculation }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($contrat->date_contrat)
                                {{ $contrat->date_contrat->format('d/m/Y') }}
                                @if($contrat->heure_contrat)
                                    <br><span class="text-xs text-gray-500">{{ $contrat->heure_contrat->format('H:i') }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Non définie</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($contrat->prix)
                                {{ number_format($contrat->prix, 2) }} DH
                                @if($contrat->total_ttc)
                                    <br><span class="text-xs text-gray-500">TTC: {{ number_format($contrat->total_ttc, 2) }} DH</span>
                                @endif
                            @else
                                <span class="text-gray-400">Non défini</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($contrat->etat_contrat == 'en cours') bg-green-100 text-green-800
                                @elseif($contrat->etat_contrat == 'termine') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($contrat->etat_contrat ?? 'Non défini') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('contrats.show', $contrat) }}" class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contrats.edit', $contrat) }}" class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('contrats.print', $contrat) }}" class="text-green-600 hover:text-green-900" title="Imprimer" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <button onclick="showRetourModal({{ $contrat->id }}, '{{ $contrat->number_contrat }}')" 
                                        class="text-orange-600 hover:text-orange-900" title="Retour du contrat">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <form method="POST" action="{{ route('contrats.destroy', $contrat) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-file-contract text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Aucun contrat trouvé</p>
                                <p class="text-sm">Commencez par créer votre premier contrat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contrats->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $contrats->links() }}
            </div>
        @endif
    </div>
</div>


<!-- Retour du Contrat Modal -->
<div id="retourModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm hidden z-50 transition-all duration-300 ease-in-out">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="retourModalContent" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-undo text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Retour du Contrat</h3>
                <p class="text-gray-600 text-center mb-6">Enregistrer le retour du contrat <span id="retourContractNumber" class="font-semibold"></span></p>
                
                <form id="retourForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="retourContratId" name="contrat_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_retour" class="block text-sm font-medium text-gray-700 mb-2">Date de retour *</label>
                            <input type="datetime-local" id="date_retour" name="date_retour" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="kilometrage_retour" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage de retour *</label>
                            <input type="number" id="kilometrage_retour" name="kilometrage_retour" required min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="niveau_carburant" class="block text-sm font-medium text-gray-700 mb-2">Niveau de carburant *</label>
                            <select id="niveau_carburant" name="niveau_carburant" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="vide">Vide</option>
                                <option value="1/4">1/4</option>
                                <option value="1/2">1/2</option>
                                <option value="3/4">3/4</option>
                                <option value="plein">Plein</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="etat_vehicule" class="block text-sm font-medium text-gray-700 mb-2">État du véhicule *</label>
                            <select id="etat_vehicule" name="etat_vehicule" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="excellent">Excellent</option>
                                <option value="bon">Bon</option>
                                <option value="moyen">Moyen</option>
                                <option value="mauvais">Mauvais</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">Observations</label>
                        <textarea id="observations" name="observations" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                  placeholder="Observations sur l'état du véhicule, dommages éventuels..."></textarea>
                    </div>
                    
                    <div>
                        <label for="frais_supplementaires" class="block text-sm font-medium text-gray-700 mb-2">Frais supplémentaires (DH)</label>
                        <input type="number" id="frais_supplementaires" name="frais_supplementaires" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="0.00">
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="hideRetourModal()" 
                                class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors duration-200">
                            Enregistrer le retour
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
        etat_contrat: document.getElementById('etatFilter').value,
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
                case 'etat_contrat':
                    label = 'État';
                    const etatLabels = {
                        'en cours': 'En cours',
                        'termine': 'Terminé'
                    };
                    displayValue = etatLabels[value] || value;
                    break;
                case 'client_id':
                    label = 'Client';
                    const clientSelect = document.getElementById('clientFilter');
                    const clientOption = clientSelect.querySelector(`option[value="${value}"]`);
                    displayValue = clientOption ? clientOption.textContent : value;
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
        case 'etat_contrat':
            document.getElementById('etatFilter').value = '';
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
    document.getElementById('etatFilter').value = '';
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
    fetch(`{{ route('contrats.index') }}?${queryString}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create a temporary div to parse the HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Update the contracts table
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

// Delete functionality is now handled by direct form submission

// Function to show retour modal
function showRetourModal(contractId, contractNumber) {
    document.getElementById('retourModal').classList.remove('hidden');
    document.getElementById('retourContractNumber').textContent = contractNumber;
    document.getElementById('retourContratId').value = contractId;
    
    // Set default date to now
    const now = new Date();
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    document.getElementById('date_retour').value = localDateTime;
    
    // Animate modal content
    setTimeout(() => {
        const modalContent = document.getElementById('retourModalContent');
        modalContent.classList.add('scale-100', 'opacity-100');
        modalContent.classList.remove('scale-95', 'opacity-0');
    }, 100);
}

// Function to hide retour modal
function hideRetourModal() {
    const modalContent = document.getElementById('retourModalContent');
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        document.getElementById('retourModal').classList.add('hidden');
    }, 300);
}

// Handle retour form submission
document.getElementById('retourForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Debug: Log form data
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
    submitBtn.disabled = true;
    
    // Submit to retour-contrats route
    fetch(`{{ route('retour-contrats.store') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (response.ok) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Server error response:', text);
                throw new Error('Server error: ' + text);
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            hideRetourModal();
            // Show success message
            alert('Retour du contrat enregistré avec succès!');
            // Reload page to show updated data
            location.reload();
        } else {
            alert('Erreur lors de l\'enregistrement: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'enregistrement du retour: ' + error.message);
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

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
    if (urlParams.get('etat_contrat')) {
        document.getElementById('etatFilter').value = urlParams.get('etat_contrat');
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
            if (!document.getElementById('retourModal').classList.contains('hidden')) {
                hideRetourModal();
            }
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('retourModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideRetourModal();
        }
    });
});
</script>
@endpush 