@extends('layouts.app')

@section('title', __('app.charges'))

@section('content')
<div class="main-content">
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
            <!-- Header with Actions -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-gray-600 mt-2">Suivez et gérez toutes les dépenses de votre flotte</p>
                </div>
            <div class="flex space-x-3">
                    <a href="{{ route('charges.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Charge</span>
                </a>
                    <a href="{{ route('charges.export') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-colors duration-200">
                    <i class="fas fa-download"></i>
                    <span>Exporter</span>
                </a>
            </div>
        </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-euro-sign text-2xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total des Charges</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($charges->sum('montant'), 2) }} DH</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-day text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Ce Mois</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($charges->where('date', '>=', now()->startOfMonth())->sum('montant'), 2) }} DH</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-2xl text-orange-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Moyenne par Charge</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $charges->count() > 0 ? number_format($charges->avg('montant'), 2) : '0.00' }} DH</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list text-2xl text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total des Charges</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $charges->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Search -->
                    <div>
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <input type="text" id="searchInput" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Désignation, description...">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <select id="sortBy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="designation">Désignation</option>
                            <option value="date">Date</option>
                            <option value="montant">Montant</option>
                            <option value="created_at">Date de création</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <div id="activeFilters" class="mt-4 hidden">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm text-gray-600">Filtres actifs:</span>
                        <div id="activeFiltersList" class="flex flex-wrap gap-2"></div>
                        <button id="clearAllFilters" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Effacer tout
                        </button>
                    </div>
                </div>
            </div>

        <!-- Charges Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Charge
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Montant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                            </th>
                        </tr>
                    </thead>
                        <tbody id="chargesTableBody" class="bg-white divide-y divide-gray-200">
                            @foreach($charges as $charge)
                            <tr class="hover:bg-gray-50" 
                                data-designation="{{ strtolower($charge->designation) }}"
                                data-description="{{ strtolower($charge->description ?? '') }}"
                                data-type="{{ strtolower($charge->designation) }}"
                                data-date="{{ $charge->date ? $charge->date->format('Y-m-d') : '' }}"
                                data-montant="{{ $charge->montant }}"
                                data-created-at="{{ $charge->created_at->format('Y-m-d') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-euro-sign text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $charge->designation }}</div>
                                @if($charge->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($charge->description, 50) }}</div>
                                @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($charge->montant, 2) }} DH</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $charge->date ? $charge->date->format('d/m/Y') : '-' }}
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeColors = [
                                            'carburant' => 'bg-green-100 text-green-800',
                                            'maintenance' => 'bg-blue-100 text-blue-800',
                                            'assurance' => 'bg-purple-100 text-purple-800',
                                            'réparation' => 'bg-red-100 text-red-800',
                                            'autre' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $typeKey = strtolower($charge->designation);
                                        $colorClass = $typeColors[$typeKey] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $charge->designation }}
                                    </span>
                                </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                        <a href="{{ route('charges.show', $charge) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                        <a href="{{ route('charges.edit', $charge) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                        <form method="POST" action="{{ route('charges.destroy', $charge) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-900" 
                                                    onclick="showDeleteModal('{{ $charge->designation }}', '{{ route('charges.destroy', $charge) }}')" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                </div>
                            </td>
                        </tr>
                            @endforeach
                    </tbody>
                </table>
                </div>

                <!-- No Results -->
                <div id="noResultsRow" class="hidden">
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune charge trouvée</h3>
                        <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="mt-8 flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </button>
                    <button id="nextPageMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span id="showingFrom">1</span> à <span id="showingTo">5</span> sur <span id="totalItems">{{ $charges->count() }}</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button id="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="pageNumbers" class="flex">
                                <!-- Page numbers will be generated here -->
                            </div>
                            <button id="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir supprimer la charge "<span id="deleteChargeName"></span>" ? Cette action est irréversible.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Supprimer
                    </button>
                    <button type="button" onclick="hideDeleteModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ITEMS_PER_PAGE = 5;
    let currentPage = 1;
    let allCharges = [];
    let filteredCharges = [];

    // Initialize
    function init() {
        allCharges = Array.from(document.querySelectorAll('#chargesTableBody tr')).filter(row => !row.id);
        filteredCharges = [...allCharges];
        displayCharges();
        setupEventListeners();
    }

    // Setup event listeners
    function setupEventListeners() {
        // Search input
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', debounce(filterAndDisplayCharges, 300));

        // Type filter
        const typeFilter = document.getElementById('typeFilter');
        typeFilter.addEventListener('change', filterAndDisplayCharges);

        // Sort by
        const sortBy = document.getElementById('sortBy');
        sortBy.addEventListener('change', filterAndDisplayCharges);

        // Pagination buttons
        document.getElementById('prevPage').addEventListener('click', () => goToPage(currentPage - 1));
        document.getElementById('nextPage').addEventListener('click', () => goToPage(currentPage + 1));
        document.getElementById('prevPageMobile').addEventListener('click', () => goToPage(currentPage - 1));
        document.getElementById('nextPageMobile').addEventListener('click', () => goToPage(currentPage + 1));

        // Clear all filters
        document.getElementById('clearAllFilters').addEventListener('click', clearAllFilters);
    }

    // Filter and display charges
    function filterAndDisplayCharges() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
        const sortBy = document.getElementById('sortBy').value;

        // Filter charges
        filteredCharges = allCharges.filter(charge => {
            const designation = charge.dataset.designation || '';
            const description = charge.dataset.description || '';
            const type = charge.dataset.type || '';

            const matchesSearch = designation.includes(searchTerm) || description.includes(searchTerm);
            const matchesType = !typeFilter || type === typeFilter;

            return matchesSearch && matchesType;
        });

        // Sort charges
        filteredCharges.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortBy) {
                case 'designation':
                    aValue = a.dataset.designation || '';
                    bValue = b.dataset.designation || '';
                    break;
                case 'date':
                    aValue = a.dataset.date || '';
                    bValue = b.dataset.date || '';
                    break;
                case 'montant':
                    aValue = parseFloat(a.dataset.montant) || 0;
                    bValue = parseFloat(b.dataset.montant) || 0;
                    break;
                case 'created_at':
                    aValue = a.dataset.createdAt || '';
                    bValue = b.dataset.createdAt || '';
                    break;
                default:
                    return 0;
            }

            if (sortBy === 'montant') {
                return bValue - aValue; // Descending for amount
            } else {
                return aValue.localeCompare(bValue);
            }
        });

        currentPage = 1;
        displayCharges();
        updateActiveFilters();
    }

    // Display charges for current page
    function displayCharges() {
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const chargesToShow = filteredCharges.slice(startIndex, endIndex);

        // Hide all charges first
        allCharges.forEach(charge => charge.style.display = 'none');

        // Show charges for current page
        chargesToShow.forEach(charge => charge.style.display = '');

        // Show/hide no results
        const noResultsRow = document.getElementById('noResultsRow');
        if (filteredCharges.length === 0) {
            noResultsRow.classList.remove('hidden');
        } else {
            noResultsRow.classList.add('hidden');
        }

        updatePagination();
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredCharges.length / ITEMS_PER_PAGE);
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = Math.min(startIndex + ITEMS_PER_PAGE, filteredCharges.length);

        // Update showing info
        document.getElementById('showingFrom').textContent = filteredCharges.length > 0 ? startIndex + 1 : 0;
        document.getElementById('showingTo').textContent = endIndex;
        document.getElementById('totalItems').textContent = filteredCharges.length;

        // Update pagination buttons
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
        document.getElementById('prevPageMobile').disabled = currentPage === 1;
        document.getElementById('nextPageMobile').disabled = currentPage === totalPages;

        // Generate page numbers
        generatePageNumbers(totalPages);
    }

    // Generate page number buttons
    function generatePageNumbers(totalPages) {
        const container = document.getElementById('pageNumbers');
        container.innerHTML = '';

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                i === currentPage
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            }`;
            button.addEventListener('click', () => goToPage(i));
            container.appendChild(button);
        }
    }

    // Go to specific page
    function goToPage(page) {
        const totalPages = Math.ceil(filteredCharges.length / ITEMS_PER_PAGE);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayCharges();
        }
    }

    // Update active filters display
    function updateActiveFilters() {
        const activeFiltersContainer = document.getElementById('activeFilters');
        const activeFiltersList = document.getElementById('activeFiltersList');
        
        const filters = [];
        
        const searchTerm = document.getElementById('searchInput').value;
        if (searchTerm) {
            filters.push(`<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Recherche: ${searchTerm} <button onclick="clearFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">×</button></span>`);
        }
        
        const typeFilter = document.getElementById('typeFilter').value;
        if (typeFilter) {
            filters.push(`<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Type: ${typeFilter} <button onclick="clearFilter('type')" class="ml-1 text-green-600 hover:text-green-800">×</button></span>`);
        }
        
        if (filters.length > 0) {
            activeFiltersList.innerHTML = filters.join('');
            activeFiltersContainer.classList.remove('hidden');
        } else {
            activeFiltersContainer.classList.add('hidden');
        }
    }

    // Clear specific filter
    window.clearFilter = function(filterType) {
        if (filterType === 'search') {
            document.getElementById('searchInput').value = '';
        } else if (filterType === 'type') {
            document.getElementById('typeFilter').value = '';
        }
        filterAndDisplayCharges();
    };

    // Clear all filters
    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('sortBy').value = 'designation';
        filterAndDisplayCharges();
    }

    // Debounce function
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

    // Delete modal functions
    window.showDeleteModal = function(chargeName, deleteUrl) {
        document.getElementById('deleteChargeName').textContent = chargeName;
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    window.hideDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    // Initialize the page
    init();
});
</script>
@endsection