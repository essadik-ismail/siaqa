@extends('layouts.app')

@section('title', 'Vehicles')

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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                <select id="statusFilter" name="status" class="form-input w-full px-4 py-3 {{ request('status') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Vehicles</option>
                    <option value="disponible" {{ request('status') === 'disponible' ? 'selected' : '' }}>Available</option>
                    <option value="en_location" {{ request('status') === 'en_location' ? 'selected' : '' }}>On Rental</option>
                    <option value="en_maintenance" {{ request('status') === 'en_maintenance' ? 'selected' : '' }}>In Maintenance</option>
                    <option value="hors_service" {{ request('status') === 'hors_service' ? 'selected' : '' }}>Out of Service</option>
                </select>
                @if(request('status') !== '')
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Brand</label>
                <select id="brandFilter" name="brand" class="form-input w-full px-4 py-3 {{ request('brand') && request('brand') !== '' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="">All Brands</option>
                    @foreach($marques as $marque)
                        <option value="{{ $marque->id }}" {{ request('brand') == $marque->id ? 'selected' : '' }}>{{ $marque->marque }}</option>
                    @endforeach
                </select>
                @if(request('brand') && request('brand') !== '')
                <div class="mt-2 text-xs text-purple-600 flex items-center">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('status') !== '' || request('brand'))
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
                @if(request('status') !== '')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        Status: {{ ucfirst(request('status')) }}
                        <button type="button" onclick="clearFilter('status')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('brand'))
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                        Brand: {{ $marques->find(request('brand'))->marque ?? 'Unknown' }}
                        <button type="button" onclick="clearFilter('brand')" class="ml-2 text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                <button type="button" onclick="clearAllFilters()" class="text-red-600 hover:text-red-800 text-xs underline font-medium transition-colors duration-200">
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
            <table class="min-w-full divide-y divide-gray-200">
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
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                        <i class="fas fa-car text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">
                                            {{ $vehicule->name }}
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

        <!-- Pagination -->
        @if($vehicules->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $vehicules->appends(request()->query())->links() }}
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

<script>
// Filter functionality
function clearFilter(type) {
    switch(type) {
        case 'search':
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            break;
        case 'status':
            document.getElementById('statusFilter').value = '';
            break;
        case 'brand':
            document.getElementById('brandFilter').value = '';
            break;
    }
    // Reload page with cleared filters
    const url = new URL(window.location);
    url.searchParams.delete(type);
    window.location.href = url.toString();
}

function clearAllFilters() {
    window.location.href = '{{ route("vehicules.index") }}';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const clearBtn = document.getElementById('clearSearch');
    if (this.value) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }
});

document.getElementById('clearSearch').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    this.classList.add('hidden');
});

// Auto-submit filters
document.getElementById('statusFilter').addEventListener('change', function() {
    this.closest('form')?.submit();
});

document.getElementById('brandFilter').addEventListener('change', function() {
    this.closest('form')?.submit();
});

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
            <a href="{{ route('vehicules.index') }}" class="block w-full bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-4 rounded-2xl font-medium text-center transition-all duration-200 hover:scale-105 shadow-lg">
                <i class="fas fa-list mr-3"></i>View All Vehicles
            </a>
        </div>
    </div>
@endsection 