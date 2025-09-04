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
    @if(request('search') || request('is_blacklisted') !== '')
    <div class="mb-4 text-sm text-gray-600">
        <span class="font-medium">{{ $clients->total() }}</span> 
        @if($clients->total() === 1)
            client found
        @else
            clients found
        @endif
        
        @if(request('search'))
            for "<span class="font-medium text-blue-600">{{ request('search') }}</span>"
        @endif
        
        @if(request('is_blacklisted') !== '')
            with status 
            <span class="font-medium {{ request('is_blacklisted') ? 'text-red-600' : 'text-green-600' }}">
                {{ request('is_blacklisted') ? 'Blacklisted' : 'Active' }}
            </span>
        @endif
    </div>
    @endif

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
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $client->nom }} {{ $client->prenom }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $client->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $client->email }}</div>
                                <div class="text-sm text-gray-500">{{ $client->telephone }}</div>
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
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    @if(request('search') || request('is_blacklisted') !== '')
                                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">No clients found</p>
                                        <p class="text-sm">Try adjusting your search criteria or filters.</p>
                                        <button onclick="clearAllFilters()" class="mt-3 text-blue-600 hover:text-blue-800 underline">
                                            Clear all filters
                                        </button>
                                    @else
                                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">No clients found</p>
                                        <p class="text-sm">Get started by adding your first client.</p>
                                        <a href="{{ route('clients.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                            <i class="fas fa-plus mr-2"></i>Add New Client
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($clients->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $clients->links() }}
            </div>
        @endif
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
                        <p class="text-2xl font-bold text-blue-800">{{ number_format($clients->total()) }}</p>
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
<style>
    /* Enhanced Modal Animations */
    .modal-backdrop {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    
    /* Modal entrance animation */
    .modal-enter {
        opacity: 0;
        transform: scale(0.8);
    }
    
    .modal-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    /* Modal exit animation */
    .modal-exit {
        opacity: 1;
        transform: scale(1);
    }
    
    .modal-exit-active {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Enhanced button animations */
    .btn-primary {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .btn-primary:active {
        transform: translateY(0);
        transition: transform 0.1s;
    }
    
    /* Focus states for accessibility */
    .btn-primary:focus {
        outline: none;
        ring: 2px;
        ring-offset: 2px;
    }
    
    /* Modal content animations */
    .modal-content-enter {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
    }
    
    .modal-content-enter-active {
        opacity: 1;
        transform: scale(1) translateY(0);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    /* Icon animations */
    .modal-icon {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    .modal-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }
    
    /* Enhanced shadows and depth */
    .modal-shadow {
        box-shadow: 
            0 20px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04),
            0 0 0 1px rgba(0, 0, 0, 0.05);
    }
    
    /* Responsive modal sizing */
    @media (max-width: 640px) {
        .modal-content {
            margin: 1rem;
            max-width: calc(100vw - 2rem);
        }
    }
    
    /* Loading state animations */
    .loading-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Pulse animation for important elements */
    .pulse-important {
        animation: pulse-important 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse-important {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>

<script>
    // Debounce function to limit API calls
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

    // Function to update URL and reload page with new filters
    function updateFilters() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const sortBy = document.getElementById('sortFilter').value;
        
        const params = new URLSearchParams();
        
        if (search) params.append('search', search);
        if (status !== '') params.append('is_blacklisted', status);
        if (sortBy) params.append('sort_by', sortBy);
        
        const url = new URL(window.location);
        url.search = params.toString();
        
        // Show loading state
        showLoadingState();
        
        // Navigate to new URL
        window.location.href = url.toString();
    }

    // Function to show loading state
    function showLoadingState() {
        const tableBody = document.querySelector('tbody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-4"></div>
                            <p class="text-lg font-medium">Loading...</p>
                            <p class="text-sm">Please wait while we fetch your results.</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Add event listeners for dynamic filtering
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const clearSearchButton = document.getElementById('clearSearch');
        const searchIndicator = document.getElementById('searchIndicator');
        const searchSuggestions = document.getElementById('searchSuggestions');

        // Debounced search input handler
        const debouncedSearch = debounce(updateFilters, 500);
        
        // Search input event (with debouncing)
        searchInput.addEventListener('input', debouncedSearch);
        
        // Status filter change event
        statusFilter.addEventListener('change', updateFilters);
        
        // Sort filter change event
        sortFilter.addEventListener('change', updateFilters);

        // Clear search button event
        clearSearchButton.addEventListener('click', function() {
            searchInput.value = '';
            updateFilters(); // Update URL and reload
            clearSearchButton.classList.add('hidden');
            searchIndicator.classList.add('hidden');
        });

        // Keyboard shortcuts
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateFilters();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.value = '';
                updateFilters();
            }
        });

        // Focus management for better UX
        searchInput.addEventListener('focus', function() {
            if (this.value === '') {
                searchSuggestions.classList.remove('hidden');
            }
        });

        searchInput.addEventListener('blur', function() {
            // Hide suggestions after a short delay to allow for clicks
            setTimeout(() => {
                searchSuggestions.classList.add('hidden');
            }, 200);
        });

        // Add loading indicator to search input
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-blue-300');
                this.classList.remove('border-gray-300');
                clearSearchButton.classList.remove('hidden');
                searchIndicator.classList.remove('hidden');
                searchSuggestions.classList.add('hidden');
            } else {
                this.classList.remove('border-blue-300');
                this.classList.add('border-gray-300');
                clearSearchButton.classList.add('hidden');
                searchIndicator.classList.add('hidden');
                searchSuggestions.classList.add('hidden');
            }
        });

        // Show suggestions if search input is empty
        if (searchInput.value === '') {
            searchSuggestions.classList.remove('hidden');
        }
    });

    // Function to clear a specific filter
    function clearFilter(param) {
        const params = new URLSearchParams(window.location.search);
        
        // Map the display parameter names to actual URL parameter names
        const paramMap = {
            'search': 'search',
            'status': 'is_blacklisted',
            'sort': 'sort_by'
        };
        
        const actualParam = paramMap[param] || param;
        params.delete(actualParam);
        
        // Update URL and reload
        const url = new URL(window.location);
        url.search = params.toString();
        window.location.href = url.toString();
    }

    // Function to clear all filters
    function clearAllFilters() {
        const url = new URL(window.location);
        url.search = '';
        window.location.href = url.toString();
    }

    // Blacklist modal functionality
    function showBlacklistModal(clientId, clientName, currentStatus) {
        const modal = document.getElementById('blacklistModal');
        const modalContent = document.getElementById('blacklistModalContent');
        
        // Update modal content based on current status
        const isCurrentlyBlacklisted = currentStatus === 'true' || currentStatus === true;
        
        document.getElementById('blacklistClientName').textContent = clientName;
        document.getElementById('blacklistForm').action = `/clients/${clientId}/blacklist`;
        
        // Update modal text and button based on current status
        const modalTitle = document.querySelector('#blacklistModal h3');
        const modalMessage = document.querySelector('#blacklistModal p');
        const submitButton = document.querySelector('#blacklistModal button[type="submit"]');
        
        if (isCurrentlyBlacklisted) {
            modalTitle.textContent = 'Unblock Client';
            modalMessage.textContent = `Are you sure you want to unblock ${clientName}? They will be able to make reservations again.`;
            submitButton.textContent = 'Unblock Client';
            submitButton.className = 'w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded';
        } else {
            modalTitle.textContent = 'Block Client';
            modalMessage.textContent = `Are you sure you want to block ${clientName}? They will not be able to make new reservations.`;
            submitButton.textContent = 'Block Client';
            submitButton.className = 'w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded';
        }
        
        modal.classList.remove('hidden');
        
        // Animate in
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideBlacklistModal() {
        const modal = document.getElementById('blacklistModal');
        const modalContent = document.getElementById('blacklistModalContent');
        
        // Animate out
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('blacklistModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideBlacklistModal();
        }
    });

    // Form submission handling
    document.getElementById('blacklistForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });

    // Enhanced function to show delete confirmation modal
    function showDeleteModal(name, actionUrl) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const clientName = document.getElementById('deleteClientName');
        
        // Set client name
        clientName.textContent = name;
        
        // Set form action
        document.getElementById('deleteForm').action = actionUrl;
        
        // Show modal with backdrop
        modal.classList.remove('hidden');
        
        // Trigger entrance animation
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
        
        // Add backdrop blur effect
        document.body.style.overflow = 'hidden';
        
        // Focus management for accessibility
        setTimeout(() => {
            const cancelBtn = modal.querySelector('button[onclick="hideDeleteModal()"]');
            if (cancelBtn) cancelBtn.focus();
        }, 300);
    }

    // Enhanced function to hide delete confirmation modal
    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        
        // Trigger exit animation
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    // Enhanced function to show blacklist confirmation modal
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

        // Configure modal based on current status
        if (isBlacklisted) {
            // Currently blacklisted - Show unblacklist option (Green theme)
            iconContainer.className = 'w-16 h-16 bg-green-100 rounded-full flex items-center justify-center';
            icon.className = 'fas fa-user-check text-green-500 text-2xl';
            title.textContent = 'Unblock Client';
            message.innerHTML = `Do you want to <strong class="text-green-700">unblock</strong> the client <span class="font-semibold text-gray-900">${name}</span>?`;
            submitText.textContent = 'Unblock Client';
            submitBtn.className = 'w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2';
        } else {
            // Currently active - Show blacklist option (Red theme)
            iconContainer.className = 'w-16 h-16 bg-red-100 rounded-full flex items-center justify-center';
            icon.className = 'fas fa-ban text-red-500 text-2xl';
            title.textContent = 'Block Client';
            message.innerHTML = `Do you want to <strong class="text-red-700">block</strong> the client <span class="font-semibold text-gray-900">${name}</span>?`;
            submitText.textContent = 'Block Client';
            submitBtn.className = 'w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2';
        }

        // Set form action
        form.action = actionUrl;

        // Show modal with backdrop
        modal.classList.remove('hidden');
        
        // Trigger entrance animation
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
        
        // Add backdrop blur effect
        document.body.style.overflow = 'hidden';
        
        // Focus management for accessibility
        setTimeout(() => {
            const cancelBtn = modal.querySelector('button[onclick="hideBlacklistModal()"]');
            if (cancelBtn) cancelBtn.focus();
        }, 300);
    }

    // Enhanced function to hide blacklist confirmation modal
    function hideBlacklistModal() {
        const modal = document.getElementById('blacklistModal');
        const modalContent = document.getElementById('blacklistModalContent');
        
        // Trigger exit animation
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    // Enhanced modal interaction handlers
    document.addEventListener('click', function(event) {
        const deleteModal = document.getElementById('deleteModal');
        const blacklistModal = document.getElementById('blacklistModal');
        
        // Close modals when clicking outside
        if (event.target === deleteModal) {
            hideDeleteModal();
        }
        if (event.target === blacklistModal) {
            hideBlacklistModal();
        }
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideDeleteModal();
            hideBlacklistModal();
        }
        
        // Tab navigation within modals
        if (event.key === 'Tab') {
            const activeModal = document.querySelector('#deleteModal:not(.hidden), #blacklistModal:not(.hidden)');
            if (activeModal) {
                const focusableElements = activeModal.querySelectorAll('button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (event.shiftKey && document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                } else if (!event.shiftKey && document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });

    // Add loading states to form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteForm');
        const blacklistForm = document.getElementById('blacklistForm');
        
        if (deleteForm) {
            deleteForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Suppression...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        }
        
        if (blacklistForm) {
            blacklistForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        }
    });
</script>
@endpush 