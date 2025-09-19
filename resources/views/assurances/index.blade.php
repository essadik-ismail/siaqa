@extends('layouts.app')

@section('title', 'Assurances')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Gestion des Assurances</h2>
            <p class="text-gray-600 text-lg">Gérer les assurances de votre flotte de véhicules</p>
        </div>
        <a href="{{ route('assurances.create') }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
            <i class="fas fa-plus"></i>
            <span>Nouvelle Assurance</span>
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="content-card p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-3">Recherche</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Compagnie, numéro de police..." 
                           class="form-input w-full pl-4 pr-12 py-3">
                    <button type="button" id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Statut</label>
                <select id="statusFilter" name="statut" class="form-input w-full px-4 py-3 {{ request('statut') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expiree" {{ request('statut') === 'expiree' ? 'selected' : '' }}>Expirée</option>
                    <option value="resiliee" {{ request('statut') === 'resiliee' ? 'selected' : '' }}>Résiliée</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Type</label>
                <select id="typeFilter" name="type_assurance" class="form-input w-full px-4 py-3 {{ request('type_assurance') !== '' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="">Tous les types</option>
                    <option value="responsabilite_civile" {{ request('type_assurance') === 'responsabilite_civile' ? 'selected' : '' }}>Responsabilité civile</option>
                    <option value="tous_risques" {{ request('type_assurance') === 'tous_risques' ? 'selected' : '' }}>Tous risques</option>
                    <option value="vol_incendie" {{ request('type_assurance') === 'vol_incendie' ? 'selected' : '' }}>Vol et incendie</option>
                    <option value="assistance" {{ request('type_assurance') === 'assistance' ? 'selected' : '' }}>Assistance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Véhicule</label>
                <select id="vehiculeFilter" name="vehicule_id" class="form-input w-full px-4 py-3 {{ request('vehicule_id') !== '' ? 'border-blue-300 bg-blue-50' : '' }}">
                    <option value="">Tous les véhicules</option>
                    @foreach($vehicules ?? [] as $vehicule)
                        <option value="{{ $vehicule->id }}" {{ request('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
                            {{ $vehicule->name }} - {{ $vehicule->immatriculation }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request('search') || request('statut') !== '' || request('type_assurance') !== '' || request('vehicule_id'))
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-3 text-sm text-gray-600">
                <span class="font-medium">Filtres actifs :</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Recherche: "{{ request('search') }}"
                        <button type="button" onclick="clearFilter('search')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('statut') !== '')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        Statut: {{ ucfirst(request('statut')) }}
                        <button type="button" onclick="clearFilter('statut')" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('type_assurance') !== '')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                        Type: {{ ucfirst(str_replace('_', ' ', request('type_assurance'))) }}
                        <button type="button" onclick="clearFilter('type_assurance')" class="ml-2 text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('vehicule_id'))
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Véhicule: {{ $vehicules->find(request('vehicule_id'))->name ?? 'Inconnu' }}
                        <button type="button" onclick="clearFilter('vehicule_id')" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                <button type="button" onclick="clearAllFilters()" class="text-red-600 hover:text-red-800 text-xs underline font-medium transition-colors duration-200">
                    Effacer tout
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Search Results Counter -->
    @if(request('search') || request('statut') !== '' || request('type_assurance') !== '' || request('vehicule_id'))
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="text-sm text-gray-700">
            <span class="font-semibold text-blue-800">{{ $assurances->total() }}</span> 
            @if($assurances->total() === 1)
                assurance trouvée
            @else
                assurances trouvées
            @endif
        </div>
    </div>
    @endif

    <!-- Assurances Table -->
    <div class="content-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Véhicule</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assurance</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Période</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($assurances as $assurance)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                        <i class="fas fa-car text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">
                                            {{ $assurance->vehicule->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $assurance->vehicule->immatriculation ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-base font-medium text-gray-900">{{ $assurance->compagnie }}</div>
                                <div class="text-sm text-gray-500">{{ $assurance->numero_police }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $assurance->type_assurance)) }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-base font-medium text-gray-900">
                                    Du {{ \Carbon\Carbon::parse($assurance->date_debut)->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Au {{ \Carbon\Carbon::parse($assurance->date_expiration)->format('d/m/Y') }}
                                </div>
                                @if($assurance->cout_annuel)
                                    <div class="text-sm text-gray-500">{{ number_format($assurance->cout_annuel, 2) }} DH/an</div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($assurance->statut == 'active')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check mr-2"></i>Active
                                    </span>
                                @elseif($assurance->statut == 'expiree')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Expirée
                                    </span>
                                @elseif($assurance->statut == 'resiliee')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        <i class="fas fa-ban mr-2"></i>Résiliée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-clock mr-2"></i>En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex space-x-3">
                                    <a href="{{ route('assurances.show', $assurance) }}" 
                                       class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('assurances.edit', $assurance) }}" 
                                       class="w-8 h-8 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="showDeleteModal({{ $assurance->id }}, '{{ $assurance->vehicule->name ?? 'N/A' }}', '{{ $assurance->compagnie_assurance }}', '{{ $assurance->numero_police }}')" 
                                            class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-400">Aucune assurance trouvée</p>
                                    <p class="text-sm text-gray-400 mt-1">Essayez d'ajuster vos filtres ou de créer une nouvelle assurance</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($assurances->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $assurances->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Search functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('typeFilter').addEventListener('change', applyFilters);
document.getElementById('vehiculeFilter').addEventListener('change', applyFilters);

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const vehicule = document.getElementById('vehiculeFilter').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status) params.append('statut', status);
    if (type) params.append('type_assurance', type);
    if (vehicule) params.append('vehicule_id', vehicule);
    
    window.location.search = params.toString();
}

function clearFilter(filterName) {
    const params = new URLSearchParams(window.location.search);
    params.delete(filterName);
    window.location.search = params.toString();
}

function clearAllFilters() {
    window.location.search = '';
}

// Show/hide clear search button
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
    applyFilters();
});

// Delete confirmation modal functions
function showDeleteModal(assuranceId, vehicleName, companyName, policyNumber) {
    document.getElementById('deleteForm').action = `/assurances/${assuranceId}`;
    document.getElementById('deleteVehicleName').textContent = vehicleName;
    document.getElementById('deleteCompanyName').textContent = companyName;
    document.getElementById('deletePolicyNumber').textContent = policyNumber;
    
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate in
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Focus on cancel button for accessibility
    setTimeout(() => {
        modal.querySelector('button[onclick="hideDeleteModal()"]').focus();
    }, 100);
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    // Animate out
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm hidden z-50 transition-all duration-300 ease-in-out">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="deleteModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Supprimer l'Assurance</h3>
                <p class="text-gray-600 text-center mb-6">
                    Êtes-vous sûr de vouloir supprimer cette assurance ?
                </p>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="text-sm text-red-800">
                        <p><strong>Véhicule:</strong> <span id="deleteVehicleName"></span></p>
                        <p><strong>Compagnie:</strong> <span id="deleteCompanyName"></span></p>
                        <p><strong>Numéro de police:</strong> <span id="deletePolicyNumber"></span></p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        <span class="text-sm text-yellow-700">Cette action est irréversible et supprimera définitivement l'assurance.</span>
                    </div>
                </div>
                
                <form id="deleteForm" method="POST" class="space-y-3">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="hideDeleteModal()" 
                                class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
