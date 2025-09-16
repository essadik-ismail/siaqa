@extends('layouts.app')

@section('title', 'Clients')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Clients Management</h2>
            <p class="text-gray-600">Manage your rental clients and their information</p>
        </div>
        <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Client</span>
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, phone..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, email, phone, or ID number
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" name="is_blacklisted" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('is_blacklisted') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Clients</option>
                    <option value="0" {{ request('is_blacklisted') === '0' ? 'selected' : '' }}>Active</option>
                    <option value="1" {{ request('is_blacklisted') === '1' ? 'selected' : '' }}>Blacklisted</option>
                </select>
                @if(request('is_blacklisted') !== '')
                <div class="mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sortFilter" name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('sort_by') && request('sort_by') !== 'nom' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="nom" {{ request('sort_by') === 'nom' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                </select>
                @if(request('sort_by') && request('sort_by') !== 'nom')
                <div class="mt-1 text-xs text-purple-600">
                    <i class="fas fa-sort mr-1"></i>Custom sorting
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('is_blacklisted') !== '' || (request('sort_by') && request('sort_by') !== 'nom'))
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
                @if(request('is_blacklisted') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Status: {{ request('is_blacklisted') ? 'Blacklisted' : 'Active' }}
                        <button type="button" onclick="clearFilter('status')" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('sort_by') && request('sort_by') !== 'nom')
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
        <span id="resultsText">clients found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="statusTerm" class="hidden">with status <span class="font-medium"></span></span>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="clientsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="client-row hover:bg-gray-50" 
                            data-name="{{ strtolower($client->nom . ' ' . $client->prenom) }}"
                            data-email="{{ strtolower($client->email) }}"
                            data-phone="{{ $client->telephone }}"
                            data-id="{{ $client->id }}"
                            data-status="{{ $client->is_blacklisted ? 'blacklisted' : 'active' }}"
                            data-reservations="{{ $client->reservations_count ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 client-name">
                                            {{ $client->nom }} {{ $client->prenom }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $client->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 client-email">{{ $client->email }}</div>
                                <div class="text-sm text-gray-500 client-phone">{{ $client->telephone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $client->numero_permis }}</div>
                                <div class="text-sm text-gray-500">Exp: {{ $client->date_expiration_permis ? $client->date_expiration_permis->format('M Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->is_blacklisted)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>Blacklisted
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $client->reservations_count ?? 0 }} reservations
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('clients.show', $client) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('clients.toggle-blacklist', $client) }}" class="inline">
                                        @csrf
                                        <button type="button" class="text-yellow-600 hover:text-yellow-900" 
                                                onclick="showBlacklistModal('{{ $client->nom }} {{ $client->prenom }}', {{ $client->is_blacklisted ? 'true' : 'false' }}, '{{ route('clients.toggle-blacklist', $client) }}')">
                                            <i class="fas fa-{{ $client->is_blacklisted ? 'user-check' : 'ban' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900" 
                                                onclick="showDeleteModal('{{ $client->nom }} {{ $client->prenom }}', '{{ route('clients.destroy', $client) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResultsRow">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No clients found</p>
                                    <p class="text-sm">Get started by adding your first client.</p>
                                    <a href="{{ route('clients.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        <i class="fas fa-plus mr-2"></i>Add New Client
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
                            Showing <span id="paginationStart" class="font-medium">1</span> to <span id="paginationEnd" class="font-medium">10</span> of <span id="paginationTotal" class="font-medium">0</span> results
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
                        Êtes-vous sûr de vouloir supprimer le client
                    </p>
                    <p class="text-lg font-semibold text-gray-900" id="deleteClientName"></p>
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

<!-- Blacklist Confirmation Modal -->
<div id="blacklistModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300 ease-in-out">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto transform transition-all duration-300 scale-95 opacity-0" id="blacklistModalContent">
            <!-- Modal Header -->
            <div class="relative px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center" id="blacklistIconContainer">
                        <i id="blacklistIcon" class="fas fa-ban text-yellow-500 text-2xl"></i>
                    </div>
                </div>
                <h3 id="blacklistTitle" class="text-xl font-semibold text-gray-900 text-center">Confirmer l'action</h3>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-6">
                <div class="text-center mb-6">
                    <p id="blacklistMessage" class="text-gray-600 mb-4"></p>
                    <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center text-yellow-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm">Cette action peut être annulée plus tard</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <form id="blacklistForm" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" id="blacklistSubmitBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2">
                            <i class="fas fa-check mr-2"></i><span id="blacklistSubmitText">Confirmer</span>
                        </button>
                    </form>
                    <button onclick="hideBlacklistModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('sidebar')
    <!-- Quick Stats -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Client Statistics</h3>
        <div class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Total Clients</p>
                        <p class="text-2xl font-bold text-blue-800">{{ number_format($clients->count()) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Active Clients</p>
                        <p class="text-2xl font-bold text-green-800">{{ number_format($clients->where('is_blacklisted', false)->count()) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-600 font-medium">Blacklisted</p>
                        <p class="text-2xl font-bold text-red-800">{{ number_format($clients->where('is_blacklisted', true)->count()) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-slash text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('clients.create') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-plus mr-2"></i>Add New Client
            </a>
            <a href="{{ route('clients.statistics') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-chart-bar mr-2"></i>View Statistics
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Global variables for frontend functionality
    let allClients = [];
    let filteredClients = [];
    let currentPage = 1;
    const ITEMS_PER_PAGE = 10;
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

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        initializeClients();
        setupEventListeners();
        filterAndDisplayClients();
    });

    // Initialize clients data from the table
    function initializeClients() {
        const clientRows = document.querySelectorAll('.client-row');
        allClients = Array.from(clientRows).map(row => ({
            element: row,
            name: row.dataset.name,
            email: row.dataset.email,
            phone: row.dataset.phone,
            id: parseInt(row.dataset.id),
            status: row.dataset.status,
            reservations: parseInt(row.dataset.reservations)
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

        // Debounced search input handler
        const debouncedSearch = debounce(filterAndDisplayClients, 300);
        
        // Search input event
        searchInput.addEventListener('input', function() {
            debouncedSearch();
            updateSearchUI();
        });
        
        // Status filter change event
        statusFilter.addEventListener('change', filterAndDisplayClients);
        
        // Sort filter change event
        sortFilter.addEventListener('change', filterAndDisplayClients);

        // Clear search button event
        clearSearchButton.addEventListener('click', function() {
            searchInput.value = '';
            filterAndDisplayClients();
            updateSearchUI();
        });

        // Keyboard shortcuts
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                this.value = '';
                filterAndDisplayClients();
                updateSearchUI();
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
            searchInput.classList.add('border-blue-300');
            searchInput.classList.remove('border-gray-300');
            clearSearchButton.classList.remove('hidden');
            searchIndicator.classList.remove('hidden');
            searchSuggestions.classList.add('hidden');
        } else {
            searchInput.classList.remove('border-blue-300');
            searchInput.classList.add('border-gray-300');
            clearSearchButton.classList.add('hidden');
            searchIndicator.classList.add('hidden');
            searchSuggestions.classList.add('hidden');
        }
    }

    // Filter and display clients
    function filterAndDisplayClients() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const sortBy = document.getElementById('sortFilter').value;

        // Filter clients
        filteredClients = allClients.filter(client => {
            const matchesSearch = !searchTerm || 
                client.name.includes(searchTerm) || 
                client.email.includes(searchTerm) || 
                client.phone.includes(searchTerm) ||
                client.id.toString().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || 
                (statusFilter === '0' && client.status === 'active') ||
                (statusFilter === '1' && client.status === 'blacklisted');
            
            return matchesSearch && matchesStatus;
        });

        // Sort clients
        if (sortBy) {
            filteredClients.sort((a, b) => {
                switch (sortBy) {
                    case 'nom':
                        return a.name.localeCompare(b.name);
                    case 'email':
                        return a.email.localeCompare(b.email);
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
        
        // Display clients
        displayClients();
        
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

        const count = filteredClients.length;
        resultsCount.textContent = count;
        resultsText.textContent = count === 1 ? 'client found' : 'clients found';

        // Show search term if searching
        if (searchTerm) {
            searchTermSpan.querySelector('span').textContent = searchTerm;
            searchTermSpan.classList.remove('hidden');
        } else {
            searchTermSpan.classList.add('hidden');
        }

        // Show status term if filtering by status
        if (statusFilter !== '') {
            const statusText = statusFilter === '0' ? 'Active' : 'Blacklisted';
            const statusClass = statusFilter === '0' ? 'text-green-600' : 'text-red-600';
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

    // Display clients for current page
    function displayClients() {
        const tableBody = document.getElementById('clientsTableBody');
        const noResultsRow = document.getElementById('noResultsRow');
        
        // Hide all client rows first
        allClients.forEach(client => {
            client.element.style.display = 'none';
        });

        if (filteredClients.length === 0) {
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
        const clientsToShow = filteredClients.slice(startIndex, endIndex);

        // Show only the clients for current page
        clientsToShow.forEach(client => {
            client.element.style.display = '';
        });
    }

    // Update pagination controls
    function updatePagination() {
        totalPages = Math.ceil(filteredClients.length / ITEMS_PER_PAGE);
        
        // Update pagination info
        const start = filteredClients.length === 0 ? 0 : (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const end = Math.min(currentPage * ITEMS_PER_PAGE, filteredClients.length);
        
        document.getElementById('paginationStart').textContent = start;
        document.getElementById('paginationEnd').textContent = end;
        document.getElementById('paginationTotal').textContent = filteredClients.length;

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
        displayClients();
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
                document.getElementById('sortFilter').value = 'nom';
                break;
        }
        filterAndDisplayClients();
        updateSearchUI();
    }

    // Function to clear all filters
    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('sortFilter').value = 'nom';
        filterAndDisplayClients();
        updateSearchUI();
    }

    // Modal functions
    function showDeleteModal(name, actionUrl) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const clientName = document.getElementById('deleteClientName');
        
        clientName.textContent = name;
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

    function showBlacklistModal(name, isBlacklisted, actionUrl) {
        const modal = document.getElementById('blacklistModal');
        const modalContent = document.getElementById('blacklistModalContent');
        const iconContainer = document.getElementById('blacklistIconContainer');
        const icon = document.getElementById('blacklistIcon');
        const title = document.getElementById('blacklistTitle');
        const message = document.getElementById('blacklistMessage');
        const submitBtn = document.getElementById('blacklistSubmitBtn');
        const submitText = document.getElementById('blacklistSubmitText');
        const form = document.getElementById('blacklistForm');

        if (isBlacklisted) {
            iconContainer.className = 'w-16 h-16 bg-green-100 rounded-full flex items-center justify-center';
            icon.className = 'fas fa-user-check text-green-500 text-2xl';
            title.textContent = 'Unblock Client';
            message.innerHTML = `Do you want to <strong class="text-green-700">unblock</strong> the client <span class="font-semibold text-gray-900">${name}</span>?`;
            submitText.textContent = 'Unblock Client';
            submitBtn.className = 'w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2';
        } else {
            iconContainer.className = 'w-16 h-16 bg-red-100 rounded-full flex items-center justify-center';
            icon.className = 'fas fa-ban text-red-500 text-2xl';
            title.textContent = 'Block Client';
            message.innerHTML = `Do you want to <strong class="text-red-700">block</strong> the client <span class="font-semibold text-gray-900">${name}</span>?`;
            submitText.textContent = 'Block Client';
            submitBtn.className = 'w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2';
        }

        form.action = actionUrl;
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function hideBlacklistModal() {
        const modal = document.getElementById('blacklistModal');
        const modalContent = document.getElementById('blacklistModalContent');
        
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
</script>
@endpush

