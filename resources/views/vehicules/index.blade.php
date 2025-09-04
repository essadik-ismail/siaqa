@extends('layouts.app')

@section('title', 'Vehicles')

@push('styles')
<style>
    /* Enhanced Pagination Styles */
    .pagination-enhanced {
        @apply flex items-center space-x-2;
    }
    
    .pagination-enhanced .page-link {
        @apply px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm;
    }
    
    .pagination-enhanced .page-link:hover {
        @apply transform scale-105 shadow-md;
    }
    
    .pagination-enhanced .page-link.active {
        @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg border border-blue-600;
    }
    
    .pagination-enhanced .page-link.disabled {
        @apply text-gray-400 bg-gray-100 cursor-not-allowed border border-gray-200;
    }
    
    /* Pagination button hover effects */
    .pagination-btn {
        @apply px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm;
    }
    
    .pagination-btn:hover {
        @apply transform scale-105 shadow-md;
    }
    
    .pagination-btn.active {
        @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg border border-blue-600;
    }
    
    .pagination-btn.disabled {
        @apply text-gray-400 bg-gray-100 cursor-not-allowed border border-gray-200;
    }
    
    /* Sidebar Enhancements */
    .sidebar-card {
        @apply bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200;
    }
    
    .sidebar-card:hover {
        @apply transform translate-y-[-2px];
    }
    
    /* Fleet Overview Progress Bar */
    .progress-bar {
        @apply w-full bg-white/20 rounded-full h-2 overflow-hidden;
    }
    
    .progress-fill {
        @apply bg-white rounded-full h-2 transition-all duration-500 ease-out;
    }
    
    /* Quick Action Buttons */
    .quick-action-btn {
        @apply block w-full px-4 py-3 rounded-xl font-medium text-center transition-all duration-200 hover:scale-105 shadow-lg;
    }
    
    .quick-action-btn:hover {
        @apply shadow-xl;
    }
    
    /* Status Indicators */
    .status-indicator {
        @apply w-3 h-3 rounded-full;
    }
    
    .status-available { @apply bg-green-500; }
    .status-rental { @apply bg-blue-500; }
    .status-maintenance { @apply bg-yellow-500; }
    .status-out-of-service { @apply bg-red-500; }
</style>
@endpush

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Vehicles Management</h2>
            <p class="text-gray-600 text-lg">Manage your rental fleet and vehicle information</p>
        </div>
        <a href="{{ route('vehicules.create') }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
            <i class="fas fa-plus"></i>
            <span>Add New Vehicle</span>
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="content-card p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-3">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, registration, color..." 
                           class="form-input w-full pl-4 pr-12 py-3">
                    <button type="button" id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-2 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, registration, color, or brand
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                <select id="statusFilter" name="statut" class="form-input w-full px-4 py-3 {{ request('statut') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Vehicles</option>
                    <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Available</option>
                    <option value="en_location" {{ request('statut') === 'en_location' ? 'selected' : '' }}>On Rental</option>
                    <option value="en_maintenance" {{ request('statut') === 'en_maintenance' ? 'selected' : '' }}>In Maintenance</option>
                    <option value="hors_service" {{ request('statut') === 'hors_service' ? 'selected' : '' }}>Out of Service</option>
                </select>
                @if(request('statut') !== '')
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Brand</label>
                <input type="text" id="brandFilter" name="brand" value="{{ request('brand') }}" 
                       class="form-input w-full px-4 py-3 {{ request('brand') && request('brand') !== '' ? 'border-purple-300 bg-purple-50' : '' }}"
                       placeholder="Search by brand name...">
                @if(request('brand') && request('brand') !== '')
                <div class="mt-2 text-xs text-purple-600 flex items-center">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Landing Page</label>
                <select id="landingFilter" name="landing_display" class="form-input w-full px-4 py-3 {{ request('landing_display') !== '' ? 'border-orange-300 bg-orange-50' : '' }}">
                    <option value="">All Vehicles</option>
                    <option value="1" {{ request('landing_display') === '1' ? 'selected' : '' }}>Show on Landing</option>
                    <option value="0" {{ request('landing_display') === '0' ? 'selected' : '' }}>Hide from Landing</option>
                </select>
                @if(request('landing_display') !== '')
                <div class="mt-2 text-xs text-orange-600 flex items-center">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('statut') !== '' || request('brand') || request('landing_display') !== '')
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-3 text-sm text-gray-600">
                <span class="font-medium">Active filters:</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Search: "{{ request('search') }}"
                        <button type="button" onclick="clearFilter('search')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('statut') !== '')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        Status: {{ ucfirst(request('statut')) }}
                        <button type="button" onclick="console.log('Status filter X clicked'); clearFilter('statut')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('brand'))
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                        Brand: "{{ request('brand') }}"
                        <button type="button" onclick="clearFilter('brand')" class="ml-2 text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('landing_display') !== '')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                        Landing: {{ request('landing_display') === '1' ? 'Show on Landing' : 'Hide from Landing' }}
                        <button type="button" onclick="console.log('Landing filter X clicked'); clearFilter('landing_display')" class="ml-2 text-orange-600 hover:text-orange-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                <button type="button" onclick="console.log('Clear all clicked'); clearAllFilters()" class="text-red-600 hover:text-red-800 text-xs underline font-medium transition-colors duration-200">
                    Clear all
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Search Results Counter -->
    @if(request('search') || request('status') !== '' || request('brand'))
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="text-sm text-gray-700">
            <span class="font-semibold text-blue-800">{{ $vehicules->total() }}</span> 
            @if($vehicules->total() === 1)
                vehicle found
            @else
                vehicles found
            @endif
            
            @if(request('search'))
                for "<span class="font-semibold text-blue-600">{{ request('search') }}</span>"
            @endif
            
            @if(request('status') !== '')
                with status 
                <span class="font-semibold text-green-600">
                    {{ ucfirst(request('status')) }}
                </span>
            @endif
            
            @if(request('brand'))
                from brand 
                <span class="font-semibold text-purple-600">
                    {{ $marques->find(request('brand'))->marque ?? 'Unknown' }}
                </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Vehicles Table -->
    <div class="content-card overflow-hidden">
        <div class="overflow-x-auto">
            <table id="vehiclesTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Information</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Agency</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($vehicules as $vehicule)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-14 h-14 rounded-xl mr-4 shadow-sm overflow-hidden">
                                        <img src="{{ $vehicule->image_url }}" 
                                             alt="{{ $vehicule->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                        <div class="text-base font-semibold text-gray-900">
                                            {{ $vehicule->name }}
                                            </div>
                                            @if($vehicule->landing_display)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <i class="fas fa-globe mr-1"></i>Landing
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $vehicule->immatriculation }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-base font-medium text-gray-900">{{ $vehicule->marque->marque }}</div>
                                <div class="text-sm text-gray-500">{{ $vehicule->couleur ?? 'N/A' }} • {{ $vehicule->carburant ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-base font-medium text-gray-900">{{ $vehicule->agence->nom_agence ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $vehicule->categorie ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($vehicule->statut == 'disponible')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check mr-2"></i>Available
                                    </span>
                                @elseif($vehicule->statut == 'en_location')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fas fa-key mr-2"></i>On Rental
                                    </span>
                                @elseif($vehicule->statut == 'en_maintenance')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-tools mr-2"></i>In Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                        <i class="fas fa-ban mr-2"></i>Out of Service
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex space-x-3 mb-3">
                                    <a href="{{ route('vehicules.show', $vehicule) }}" 
                                       class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('vehicules.edit', $vehicule) }}" 
                                       class="w-8 h-8 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="showDeleteModal({{ $vehicule->id }}, '{{ $vehicule->name }}')" 
                                            class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="console.log('Green eye clicked for vehicle {{ $vehicule->id }}'); toggleLandingDisplay({{ $vehicule->id }}, '{{ $vehicule->landing_display ? 'true' : 'false' }}')"
                                            class="w-7 h-7 {{ $vehicule->landing_display ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 hover:bg-gray-500' }} text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm"
                                            title="{{ $vehicule->landing_display ? 'Hide from Landing Page' : 'Show on Landing Page' }}">
                                        <i class="fas {{ $vehicule->landing_display ? 'fa-eye' : 'fa-eye-slash' }} text-xs"></i>
                                    </button>
                                    <a href="{{ route('assurances.create', ['vehicule_id' => $vehicule->id]) }}"
                                       class="w-7 h-7 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm"
                                       title="Add Insurance">
                                        <i class="fas fa-shield-alt text-xs"></i>
                                    </a>
                                    <a href="{{ route('vidanges.create', ['vehicule_id' => $vehicule->id]) }}"
                                       class="w-7 h-7 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm"
                                       title="Add Oil Change">
                                        <i class="fas fa-oil-can text-xs"></i>
                                    </a>
                                    <a href="{{ route('visites.create', ['vehicule_id' => $vehicule->id]) }}"
                                       class="w-7 h-7 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm"
                                       title="Add Inspection">
                                        <i class="fas fa-clipboard-check text-xs"></i>
                                    </a>
                                    <a href="{{ route('interventions.create', ['vehicule_id' => $vehicule->id]) }}"
                                       class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm"
                                       title="Add Intervention">
                                        <i class="fas fa-tools text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-400">No vehicles found</p>
                                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        @if($vehicules->hasPages())
        <div class="bg-white px-8 py-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <!-- Results Info -->
                <div class="text-sm text-gray-600">
                    Showing 
                    <span class="font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded-md">{{ $vehicules->firstItem() }}</span>
                    to 
                    <span class="font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded-md">{{ $vehicules->lastItem() }}</span>
                    of 
                    <span class="font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded-md">{{ $vehicules->total() }}</span>
                    results
                </div>
                
                <!-- Pagination Links -->
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($vehicules->onFirstPage())
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed border border-gray-200">
                            <i class="fas fa-chevron-left mr-2"></i>Previous
                        </span>
                    @else
                        <a href="{{ $vehicules->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-chevron-left mr-2"></i>Previous
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($vehicules->getUrlRange(1, $vehicules->lastPage()) as $page => $url)
                        @if ($page == $vehicules->currentPage())
                            <span class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg border border-blue-600">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($vehicules->hasMorePages())
                        <a href="{{ $vehicules->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            Next<i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed border border-gray-200">
                            Next<i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Enhanced Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-8">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 text-center mb-3">Confirm Deletion</h3>
                <p class="text-gray-600 text-center mb-8">Are you sure you want to delete the vehicle <span id="deleteVehicleName" class="font-semibold text-gray-800"></span>?</p>

                <form id="deleteForm" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-4">
                        <button type="button" onclick="hideDeleteModal()"
                                class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-all duration-200 hover:scale-105">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/frontend-filters.js') }}"></script>
<script>
// Initialize frontend filtering for vehicles page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing vehicle filters...');
    
    // The global frontend filters system will handle everything automatically
    // Just need to ensure the table has the correct ID
    const table = document.getElementById('vehiclesTable');
    if (!table) {
        console.warn('Vehicles table not found');
    }
});

// Toggle landing page display functionality
function toggleLandingDisplay(vehicleId, currentStatus) {
    console.log('toggleLandingDisplay called with:', { vehicleId, currentStatus });
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    button.disabled = true;
    
    // Determine new status
    const isCurrentlyShown = currentStatus === 'true' || currentStatus === true;
    const newStatus = !isCurrentlyShown;
    
    console.log('Current status (parsed):', isCurrentlyShown);
    console.log('New status will be:', newStatus);
    
    // Make the request
    const url = `/vehicules/${vehicleId}/toggle-landing`;
    const requestData = { landing_display: newStatus };
    
    console.log('Making request to:', url);
    console.log('Request data:', requestData);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification('Vehicle ' + (newStatus ? 'shown on' : 'hidden from') + ' landing page successfully', 'success');
            // Reload the page to update the UI
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating landing page visibility: ' + error.message, 'error');
        
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Enhanced delete modal functionality
function showDeleteModal(vehicleId, vehicleName) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    document.getElementById('deleteVehicleName').textContent = vehicleName;
    document.getElementById('deleteForm').action = `/vehicules/${vehicleId}`;
    
    modal.classList.remove('hidden');
    
    // Animate in
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animate out
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// Toggle landing page display
function toggleLandingDisplay(vehicleId, currentStatus) {
    console.log('toggleLandingDisplay called with:', { vehicleId, currentStatus });
    
    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    button.disabled = true;
    
    // Handle both string and boolean inputs
    const isCurrentlyShown = currentStatus === 'true' || currentStatus === true;
    const newStatus = !isCurrentlyShown;
    console.log('Current status (parsed):', isCurrentlyShown);
    console.log('New status will be:', newStatus);
    
    const url = `/vehicules/${vehicleId}/toggle-landing`;
    const requestData = {
        landing_display: newStatus
    };
    
    console.log('Making request to:', url);
    console.log('Request data:', requestData);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message
            showNotification('Vehicle ' + (newStatus ? 'shown on' : 'hidden from') + ' landing page successfully', 'success');
            // Reload the page to show updated status
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating landing page visibility: ' + error.message, 'error');
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}
</script>
@endsection

@section('sidebar')
    <!-- Enhanced Fleet Statistics -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Fleet Statistics</h3>
        <div class="space-y-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 font-medium mb-1">Total Vehicles</p>
                        <p class="text-3xl font-bold text-blue-800">{{ number_format($vehicules->total()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-car text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 font-medium mb-1">Available</p>
                        <p class="text-3xl font-bold text-green-800">{{ number_format($vehicules->where('statut', 'disponible')->count()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 font-medium mb-1">On Rental</p>
                        <p class="text-3xl font-bold text-blue-800">{{ number_format($vehicules->where('statut', 'en_location')->count()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-key text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-2xl border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-700 font-medium mb-1">In Maintenance</p>
                        <p class="text-3xl font-bold text-yellow-800">{{ number_format($vehicules->where('statut', 'en_maintenance')->count()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tools text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Quick Actions -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Quick Actions</h3>
        <div class="space-y-4">
            <a href="{{ route('vehicules.create') }}" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-2xl font-medium text-center transition-all duration-200 hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-3"></i>Add New Vehicle
            </a>
        </div>
    </div>
@endsection 