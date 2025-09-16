@extends('layouts.app')

@section('title', __('app.vehicles'))

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Vehicles Management</h2>
            <p class="text-gray-600">Manage your rental fleet and vehicle information</p>
        </div>
        <a href="{{ route('vehicules.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Vehicle</span>
        </a>
    </div>

    <!-- Vehicle Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Total Vehicles</p>
                    <p class="text-3xl font-bold text-blue-800">{{ number_format($vehicules->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-car text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Available</p>
                    <p class="text-3xl font-bold text-green-800">{{ number_format($vehicules->where('statut', 'disponible')->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">In Maintenance</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ number_format($vehicules->where('statut', 'en_maintenance')->count()) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, registration, color..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, registration, color, or brand
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('statut') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Vehicles</option>
                    <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Available</option>
                    <option value="en_location" {{ request('statut') === 'en_location' ? 'selected' : '' }}>On Rental</option>
                    <option value="en_maintenance" {{ request('statut') === 'en_maintenance' ? 'selected' : '' }}>In Maintenance</option>
                    <option value="hors_service" {{ request('statut') === 'hors_service' ? 'selected' : '' }}>Out of Service</option>
                </select>
                @if(request('statut') !== '')
                <div class="mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sortFilter" name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('sort_by') && request('sort_by') !== 'name' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="brand" {{ request('sort_by') === 'brand' ? 'selected' : '' }}>Brand</option>
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                </select>
                @if(request('sort_by') && request('sort_by') !== 'name')
                <div class="mt-1 text-xs text-purple-600">
                    <i class="fas fa-sort mr-1"></i>Custom sorting
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('statut') !== '' || (request('sort_by') && request('sort_by') !== 'name'))
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <span>Active filters:</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Search: "{{ request('search') }}"
                        <button type="button" onclick="clearFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('statut') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Status: {{ ucfirst(str_replace('_', ' ', request('statut'))) }}
                        <button type="button" onclick="clearFilter('status')" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('sort_by') && request('sort_by') !== 'name')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Sort: {{ ucfirst(request('sort_by')) }}
                        <button type="button" onclick="clearFilter('sort')" class="ml-1 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                <button type="button" onclick="clearAllFilters()" class="text-red-600 hover:text-red-800 text-xs underline">
                    Clear all
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Search Results Counter -->
    <div id="resultsCounter" class="mb-4 text-sm text-gray-600 hidden">
        <span id="resultsCount" class="font-medium">0</span> 
        <span id="resultsText">vehicles found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="statusTerm" class="hidden">with status <span class="font-medium"></span></span>
    </div>

    <!-- Vehicles Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="vehiclesTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehiclesTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($vehicules as $vehicule)
                        <tr class="vehicle-row hover:bg-gray-50" 
                            data-name="{{ strtolower($vehicule->name) }}"
                            data-registration="{{ strtolower($vehicule->immatriculation) }}"
                            data-color="{{ strtolower($vehicule->couleur ?? '') }}"
                            data-brand="{{ strtolower($vehicule->marque->marque ?? '') }}"
                            data-status="{{ $vehicule->statut }}"
                            data-id="{{ $vehicule->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-car text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 vehicle-name">
                                            {{ $vehicule->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $vehicule->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 vehicle-brand">{{ $vehicule->marque->marque ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $vehicule->couleur ?? 'N/A' }} • {{ $vehicule->carburant ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $vehicule->agence->nom_agence ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $vehicule->categorie ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($vehicule->statut == 'disponible')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Available
                                    </span>
                                @elseif($vehicule->statut == 'en_location')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-key mr-1"></i>On Rental
                                    </span>
                                @elseif($vehicule->statut == 'en_maintenance')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-tools mr-1"></i>In Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>Out of Service
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <!-- Main Actions Row -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('vehicules.show', $vehicule) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('vehicules.edit', $vehicule) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Edit Vehicle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('vehicules.destroy', $vehicule) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-900" 
                                                    onclick="showDeleteModal('{{ $vehicule->name }}', '{{ route('vehicules.destroy', $vehicule) }}')" title="Delete Vehicle">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Maintenance Actions Row -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('assurances.create', ['vehicule_id' => $vehicule->id]) }}" 
                                           class="text-green-600 hover:text-green-900 text-xs" title="Add Insurance">
                                            <i class="fas fa-shield-alt mr-1"></i>Assurance
                                        </a>
                                        <a href="{{ route('vidanges.create', ['vehicule_id' => $vehicule->id]) }}" 
                                           class="text-orange-600 hover:text-orange-900 text-xs" title="Add Oil Change">
                                            <i class="fas fa-oil-can mr-1"></i>Vidange
                                        </a>
                                        <a href="{{ route('interventions.create', ['vehicule_id' => $vehicule->id]) }}" 
                                           class="text-purple-600 hover:text-purple-900 text-xs" title="Add Intervention">
                                            <i class="fas fa-tools mr-1"></i>Intervention
                                        </a>
                                        <a href="{{ route('visites.create', ['vehicule_id' => $vehicule->id]) }}" 
                                           class="text-teal-600 hover:text-teal-900 text-xs" title="Add Inspection">
                                            <i class="fas fa-clipboard-check mr-1"></i>Visite
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResultsRow">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No vehicles found</p>
                                    <p class="text-sm">Get started by adding your first vehicle.</p>
                                    <a href="{{ route('vehicules.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        <i class="fas fa-plus mr-2"></i>Add New Vehicle
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Frontend Pagination -->
        <div id="paginationContainer" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button id="prevPageBtn" onclick="goToPreviousPage()" 
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <button id="nextPageBtn" onclick="goToNextPage()" 
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span id="paginationStart" class="font-medium">1</span> to <span id="paginationEnd" class="font-medium">5</span> of <span id="paginationTotal" class="font-medium">0</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button id="prevPageBtnDesktop" onclick="goToPreviousPage()" 
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="paginationNumbers" class="flex">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="nextPageBtnDesktop" onclick="goToNextPage()" 
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300 ease-in-out">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
            <!-- Modal Header -->
            <div class="relative px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 text-center">Confirmer la suppression</h3>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 mb-2">
                        Êtes-vous sûr de vouloir supprimer le véhicule
                    </p>
                    <p class="text-lg font-semibold text-gray-900" id="deleteVehicleName"></p>
                    <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex items-center text-red-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm">Cette action ne peut pas être annulée</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                    <button onclick="hideDeleteModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<style>
    /* Enhanced search responsiveness */
    #searchInput {
        transition: all 0.2s ease-in-out;
    }
    
    #searchInput:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    #searchIndicator {
        transition: all 0.3s ease-in-out;
    }
    
    .vehicle-row {
        transition: all 0.2s ease-in-out;
    }
    
    .vehicle-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Smooth pagination transitions */
    .pagination-btn {
        transition: all 0.2s ease-in-out;
    }
    
    .pagination-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Loading animation for search */
    @keyframes searchPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .searching {
        animation: searchPulse 1.5s ease-in-out infinite;
    }
</style>

<script>
    // Global variables for frontend functionality
    let allVehicles = [];
    let filteredVehicles = [];
    let currentPage = 1;
    const ITEMS_PER_PAGE = 5;
    let totalPages = 1;

    // Debounce function to limit search calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = later;
        };
    }

    // Immediate search function for better responsiveness
    function immediateSearch() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const sortBy = document.getElementById('sortFilter').value;

        // Show search indicator immediately
        updateSearchUI();

        // Filter vehicles
        filteredVehicles = allVehicles.filter(vehicle => {
            const matchesSearch = !searchTerm || 
                vehicle.name.includes(searchTerm) || 
                vehicle.registration.includes(searchTerm) || 
                vehicle.color.includes(searchTerm) ||
                vehicle.brand.includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || vehicle.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        // Sort vehicles
        if (sortBy && sortBy !== '') {
            filteredVehicles.sort((a, b) => {
                switch (sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'brand':
                        return a.brand.localeCompare(b.brand);
                    case 'created_at':
                        return b.id - a.id; // Assuming higher ID = newer
                    default:
                        return 0;
                }
            });
        }

        // Update results counter
        updateResultsCounter(searchTerm, statusFilter);
        
        // Reset to first page
        currentPage = 1;
        
        // Display vehicles immediately
        displayVehicles();
        
        // Update pagination
        updatePagination();
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        initializeVehicles();
        setupEventListeners();
        filterAndDisplayVehicles();
    });

    // Initialize vehicles data from the table
    function initializeVehicles() {
        const vehicleRows = document.querySelectorAll('.vehicle-row');
        allVehicles = Array.from(vehicleRows).map(row => ({
            element: row,
            name: row.dataset.name,
            registration: row.dataset.registration,
            color: row.dataset.color,
            brand: row.dataset.brand,
            status: row.dataset.status,
            id: parseInt(row.dataset.id)
        }));
        
        // Hide the no results row initially
        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const clearSearchButton = document.getElementById('clearSearch');

        // Search input event - immediate response for better UX
        searchInput.addEventListener('input', function() {
            // Show immediate visual feedback
            updateSearchUI();
            
            // Perform immediate search for better responsiveness
            immediateSearch();
        });
        
        // Status filter change event - immediate response
        statusFilter.addEventListener('change', function() {
            immediateSearch();
        });
        
        // Sort filter change event - immediate response
        sortFilter.addEventListener('change', function() {
            immediateSearch();
        });

        // Clear search button event - immediate response
        clearSearchButton.addEventListener('click', function() {
            searchInput.value = '';
            immediateSearch();
        });

        // Keyboard shortcuts
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                this.value = '';
                immediateSearch();
            }
        });
    }

    // Update search UI elements
    function updateSearchUI() {
        const searchInput = document.getElementById('searchInput');
        const clearSearchButton = document.getElementById('clearSearch');
        const searchIndicator = document.getElementById('searchIndicator');
        const searchSuggestions = document.getElementById('searchSuggestions');

        if (searchInput.value.length > 0) {
            searchInput.classList.add('border-blue-300', 'bg-blue-50');
            searchInput.classList.remove('border-gray-300', 'bg-white');
            clearSearchButton.classList.remove('hidden');
            
            // Show search indicator with animation
            searchIndicator.classList.remove('hidden');
            searchIndicator.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Searching...';
            
            searchSuggestions.classList.add('hidden');
        } else {
            searchInput.classList.remove('border-blue-300', 'bg-blue-50');
            searchInput.classList.add('border-gray-300', 'bg-white');
            clearSearchButton.classList.add('hidden');
            searchIndicator.classList.add('hidden');
            searchSuggestions.classList.add('hidden');
        }
    }

    // Filter and display vehicles
    function filterAndDisplayVehicles() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const sortBy = document.getElementById('sortFilter').value;

        // Filter vehicles
        filteredVehicles = allVehicles.filter(vehicle => {
            const matchesSearch = !searchTerm || 
                vehicle.name.includes(searchTerm) || 
                vehicle.registration.includes(searchTerm) || 
                vehicle.color.includes(searchTerm) ||
                vehicle.brand.includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || vehicle.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });

        // Update results counter
        updateResultsCounter(searchTerm, statusFilter);
        
        // Reset to first page
        currentPage = 1;
        
        // Display vehicles
        displayVehicles();
        
        // Update pagination
        updatePagination();
    }

    // Update results counter
    function updateResultsCounter(searchTerm, statusFilter) {
        const resultsCounter = document.getElementById('resultsCounter');
        const resultsCount = document.getElementById('resultsCount');
        const resultsText = document.getElementById('resultsText');
        const searchTermSpan = document.getElementById('searchTerm');
        const statusTermSpan = document.getElementById('statusTerm');
        const searchIndicator = document.getElementById('searchIndicator');

        const count = filteredVehicles.length;
        resultsCount.textContent = count;
        resultsText.textContent = count === 1 ? 'vehicle found' : 'vehicles found';

        // Hide search indicator when results are ready
        if (searchIndicator) {
            searchIndicator.classList.add('hidden');
        }

        // Show search term if searching
        if (searchTerm) {
            searchTermSpan.querySelector('span').textContent = searchTerm;
            searchTermSpan.classList.remove('hidden');
        } else {
            searchTermSpan.classList.add('hidden');
        }

        // Show status term if filtering by status
        if (statusFilter !== '') {
            const statusText = statusFilter.charAt(0).toUpperCase() + statusFilter.slice(1).replace('_', ' ');
            const statusClass = statusFilter === 'disponible' ? 'text-green-600' : 'text-blue-600';
            statusTermSpan.querySelector('span').textContent = statusText;
            statusTermSpan.querySelector('span').className = `font-medium ${statusClass}`;
            statusTermSpan.classList.remove('hidden');
        } else {
            statusTermSpan.classList.add('hidden');
        }

        // Show/hide counter
        if (searchTerm || statusFilter !== '') {
            resultsCounter.classList.remove('hidden');
        } else {
            resultsCounter.classList.add('hidden');
        }
    }

    // Display vehicles for current page
    function displayVehicles() {
        const tableBody = document.getElementById('vehiclesTableBody');
        const noResultsRow = document.getElementById('noResultsRow');
        
        // Hide all vehicle rows first
        allVehicles.forEach(vehicle => {
            vehicle.element.style.display = 'none';
        });

        if (filteredVehicles.length === 0) {
            // Show no results message
            if (noResultsRow) {
                noResultsRow.style.display = '';
            }
            return;
        }

        // Hide no results row
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }

        // Calculate pagination
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const vehiclesToShow = filteredVehicles.slice(startIndex, endIndex);

        // Reorder DOM elements to match the sorted order
        vehiclesToShow.forEach((vehicle, index) => {
            // Move the element to the correct position in the DOM
            if (index === 0) {
                // If it's the first element, insert it after the table header
                tableBody.insertBefore(vehicle.element, tableBody.firstChild);
            } else {
                // Insert after the previous element
                const previousVehicle = vehiclesToShow[index - 1];
                tableBody.insertBefore(vehicle.element, previousVehicle.element.nextSibling);
            }
            vehicle.element.style.display = '';
        });
    }

    // Update pagination controls
    function updatePagination() {
        totalPages = Math.ceil(filteredVehicles.length / ITEMS_PER_PAGE);
        
        // Update pagination info
        const start = filteredVehicles.length === 0 ? 0 : (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const end = Math.min(currentPage * ITEMS_PER_PAGE, filteredVehicles.length);
        
        document.getElementById('paginationStart').textContent = start;
        document.getElementById('paginationEnd').textContent = end;
        document.getElementById('paginationTotal').textContent = filteredVehicles.length;

        // Update pagination buttons
        updatePaginationButtons();
        
        // Generate page numbers
        generatePageNumbers();
    }

    // Update pagination button states
    function updatePaginationButtons() {
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        const prevBtnDesktop = document.getElementById('prevPageBtnDesktop');
        const nextBtnDesktop = document.getElementById('nextPageBtnDesktop');

        const isFirstPage = currentPage === 1;
        const isLastPage = currentPage === totalPages || totalPages === 0;

        [prevBtn, prevBtnDesktop].forEach(btn => {
            btn.disabled = isFirstPage;
        });

        [nextBtn, nextBtnDesktop].forEach(btn => {
            btn.disabled = isLastPage;
        });
    }

    // Generate page number buttons
    function generatePageNumbers() {
        const container = document.getElementById('paginationNumbers');
        container.innerHTML = '';

        if (totalPages <= 1) return;

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = `pagination-btn relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                i === currentPage 
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            }`;
            button.onclick = () => goToPage(i);
            container.appendChild(button);
        }
    }

    // Go to specific page
    function goToPage(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        displayVehicles();
        updatePagination();
    }

    // Go to previous page
    function goToPreviousPage() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    }

    // Go to next page
    function goToNextPage() {
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    }

    // Function to clear a specific filter
    function clearFilter(param) {
        switch (param) {
            case 'search':
                document.getElementById('searchInput').value = '';
                break;
            case 'status':
                document.getElementById('statusFilter').value = '';
                break;
            case 'sort':
                document.getElementById('sortFilter').value = 'name';
                break;
        }
        immediateSearch();
    }

    // Function to clear all filters
    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('sortFilter').value = 'name';
        immediateSearch();
    }

    // Modal functions
    function showDeleteModal(name, actionUrl) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const vehicleName = document.getElementById('deleteVehicleName');
        
        vehicleName.textContent = name;
        document.getElementById('deleteForm').action = actionUrl;
        
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
</script>
@endpush