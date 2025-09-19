@extends('layouts.app')

@section('title', 'Gestion des Vidanges')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Gestion des Vidanges</h2>
            <p class="text-gray-600 text-lg">Planification et suivi des vidanges du parc automobile</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('vidanges.create') }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-plus"></i>
                <span>Nouvelle Vidange</span>
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="content-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <div class="relative">
                    <input type="text" id="search" placeholder="Rechercher par véhicule, statut..." 
                           class="form-input w-full pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select id="status_filter" class="form-select w-full">
                    <option value="">Tous les statuts</option>
                    <option value="planifiee">Planifiée</option>
                    <option value="en_cours">En cours</option>
                    <option value="terminee">Terminée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            <!-- Vehicle Filter -->
            <div>
                <label for="vehicle_filter" class="block text-sm font-medium text-gray-700 mb-2">Véhicule</label>
                <select id="vehicle_filter" class="form-select w-full">
                    <option value="">Tous les véhicules</option>
                    @foreach($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}">
                            {{ $vehicule->marque ? $vehicule->marque->nom : 'Marque inconnue' }} 
                            {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div id="active-filters" class="mt-4 flex flex-wrap gap-2 hidden">
            <span class="text-sm text-gray-600">Filtres actifs:</span>
        </div>
    </div>

    <!-- Results Counter -->
    <div class="flex justify-between items-center mb-4">
        <p class="text-sm text-gray-600">
            <span id="results-count">{{ $vidanges->total() }}</span> résultat(s) trouvé(s)
        </p>
    </div>

    <!-- Vidanges Table -->
    <div class="content-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Planifiée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kilométrage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût Estimé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="vidanges-table-body">
                    @forelse($vidanges as $vidange)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-car text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $vidange->vehicule->marque->nom }} {{ $vidange->vehicule->modele }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $vidange->vehicule->immatriculation }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $vidange->date_planifiee ? $vidange->date_planifiee->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($vidange->kilometrage_actuel) }} km
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'planifiee' => 'bg-yellow-100 text-yellow-800',
                                        'en_cours' => 'bg-blue-100 text-blue-800',
                                        'terminee' => 'bg-green-100 text-green-800',
                                        'annulee' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusClass = $statusClasses[$vidange->statut] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($vidange->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $vidange->cout_estime ? number_format($vidange->cout_estime, 2) . ' DH' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('vidanges.show', $vidange) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('vidanges.edit', $vidange) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteVidange({{ $vidange->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-oil-can text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Aucune vidange planifiée</p>
                                    <p class="text-sm">Commencez par planifier une nouvelle vidange</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($vidanges->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $vidanges->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer la suppression</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Êtes-vous sûr de vouloir supprimer cette vidange ? Cette action est irréversible.
                    </p>
                </div>
                <div class="flex justify-center space-x-4 mt-4">
                    <button id="confirm-delete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Supprimer
                    </button>
                    <button onclick="closeDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentFilters = {};
let deleteVidangeId = null;

// Search functionality
document.getElementById('search').addEventListener('input', debounce(function() {
    currentFilters.search = this.value;
    applyFilters();
}, 300));

// Status filter
document.getElementById('status_filter').addEventListener('change', function() {
    currentFilters.status = this.value;
    applyFilters();
});

// Vehicle filter
document.getElementById('vehicle_filter').addEventListener('change', function() {
    currentFilters.vehicle = this.value;
    applyFilters();
});

function applyFilters() {
    const searchTerm = currentFilters.search || '';
    const statusFilter = currentFilters.status || '';
    const vehicleFilter = currentFilters.vehicle || '';

    // Show active filters
    showActiveFilters();
    
    // Filter table rows
    const rows = document.querySelectorAll('#vidanges-table-body tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const vehicleText = row.querySelector('td:first-child')?.textContent?.toLowerCase() || '';
        const statusText = row.querySelector('td:nth-child(4)')?.textContent?.toLowerCase() || '';
        
        const matchesSearch = !searchTerm || vehicleText.includes(searchTerm.toLowerCase());
        const matchesStatus = !statusFilter || statusText.includes(statusFilter.toLowerCase());
        const matchesVehicle = !vehicleFilter || row.dataset.vehicleId === vehicleFilter;

        if (matchesSearch && matchesStatus && matchesVehicle) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update results count
    document.getElementById('results-count').textContent = visibleCount;
}

function showActiveFilters() {
    const activeFiltersDiv = document.getElementById('active-filters');
    const filters = Object.entries(currentFilters).filter(([key, value]) => value);
    
    if (filters.length === 0) {
        activeFiltersDiv.classList.add('hidden');
        return;
    }

    activeFiltersDiv.classList.remove('hidden');
    activeFiltersDiv.innerHTML = '<span class="text-sm text-gray-600">Filtres actifs:</span>';
    
    filters.forEach(([key, value]) => {
        const filterChip = document.createElement('span');
        filterChip.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
        filterChip.innerHTML = `
            ${getFilterLabel(key, value)}
            <button onclick="removeFilter('${key}')" class="ml-2 text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        activeFiltersDiv.appendChild(filterChip);
    });
}

function getFilterLabel(key, value) {
    const labels = {
        search: `Recherche: "${value}"`,
        status: `Statut: ${value}`,
        vehicle: `Véhicule: ${value}`
    };
    return labels[key] || value;
}

function removeFilter(key) {
    delete currentFilters[key];
    
    // Reset corresponding input
    if (key === 'search') {
        document.getElementById('search').value = '';
    } else if (key === 'status') {
        document.getElementById('status_filter').value = '';
    } else if (key === 'vehicle') {
        document.getElementById('vehicle_filter').value = '';
    }
    
    applyFilters();
}

function deleteVidange(id) {
    deleteVidangeId = id;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    deleteVidangeId = null;
}

document.getElementById('confirm-delete').addEventListener('click', function() {
    if (deleteVidangeId) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/vidanges/${deleteVidangeId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
});

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

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    applyFilters();
});
</script>
@endsection
