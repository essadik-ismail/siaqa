@extends('layouts.app')

@section('title', 'Détails du Véhicule')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $vehicule->name }}</h2>
            <p class="text-gray-600 text-lg">{{ $vehicule->immatriculation }} - {{ $vehicule->marque }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('vehicules.edit', $vehicule) }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('vehicules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Vehicle Status Badge -->
    <div class="mb-6">
        @if($vehicule->statut == 'disponible')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                <i class="fas fa-check mr-2"></i>Disponible
            </span>
        @elseif($vehicule->statut == 'en_location')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                <i class="fas fa-key mr-2"></i>En location
            </span>
        @elseif($vehicule->statut == 'en_maintenance')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                <i class="fas fa-tools mr-2"></i>En maintenance
            </span>
        @else
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                <i class="fas fa-ban mr-2"></i>Hors service
            </span>
        @endif
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('vehicle-info')" id="tab-vehicle-info" class="tab-button active border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600">
                <i class="fas fa-car mr-2"></i>Informations Véhicule
            </button>
            <button onclick="showTab('interventions')" id="tab-interventions" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-tools mr-2"></i>Interventions
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    
    <!-- Vehicle Information Tab -->
    <div id="tab-content-vehicle-info" class="tab-content active">
        <div class="content-card p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Vehicle Details -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Générales</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nom du véhicule</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Immatriculation</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->immatriculation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Marque</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->marque }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Modèle</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->modele ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Année</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->annee ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Couleur</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->couleur ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Technical Details -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Détails Techniques</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Carburant</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->carburant ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Catégorie</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->categorie ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Kilométrage</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($vehicule->kilometrage ?? 0) }} km</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Agence</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->agence->nom_agence ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Statut</label>
                            <p class="text-lg font-medium text-gray-900">{{ ucfirst($vehicule->statut) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Prix de location/jour</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($vehicule->prix_location ?? 0, 2) }} DH</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($vehicule->description)
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                <p class="text-gray-700">{{ $vehicule->description }}</p>
            </div>
            @endif
        </div>
    </div>




    <!-- Interventions functionality removed -->
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('active');
    });

    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    const selectedTabContent = document.getElementById('tab-content-' + tabName);
    if (selectedTabContent) {
        selectedTabContent.classList.remove('hidden');
        selectedTabContent.classList.add('active');
    }

    // Activate selected tab button
    const selectedTabButton = document.getElementById('tab-' + tabName);
    if (selectedTabButton) {
        selectedTabButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        selectedTabButton.classList.remove('border-transparent', 'text-gray-500');
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('vehicle-info');
});
</script>

<style>
.tab-button.active {
    border-bottom-color: #3b82f6;
    color: #2563eb;
}

.tab-content.active {
    display: block;
}

.tab-content {
    display: none;
}
</style>
@endsection 