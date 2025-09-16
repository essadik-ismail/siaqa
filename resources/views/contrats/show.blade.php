@extends('layouts.app')

@section('title', 'Détails du Contrat')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Contrat {{ $contrat->number_contrat }}</h1>
                <p class="text-gray-600">{{ $contrat->numero_document }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contrats.edit', $contrat) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('contrats.print', $contrat) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </a>
                <button onclick="showRetourModal({{ $contrat->id }}, '{{ $contrat->number_contrat }}')" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-undo mr-2"></i>Retour du contrat
                </button>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Contract Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations du contrat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro de contrat</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $contrat->number_contrat }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro de document</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $contrat->numero_document }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date du contrat</label>
                            <p class="text-gray-900">
                                @if($contrat->date_contrat)
                                    {{ $contrat->date_contrat->format('d/m/Y') }}
                                    @if($contrat->heure_contrat)
                                        à {{ $contrat->heure_contrat->format('H:i') }}
                                    @endif
                                @else
                                    <span class="text-gray-400">Non définie</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">État du contrat</label>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full
                                @if($contrat->etat_contrat == 'en cours') bg-green-100 text-green-800
                                @elseif($contrat->etat_contrat == 'termine') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($contrat->etat_contrat ?? 'Non défini') }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prix</label>
                            <p class="text-gray-900">
                                @if($contrat->prix)
                                    {{ number_format($contrat->prix, 2) }} DH
                                @else
                                    <span class="text-gray-400">Non défini</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total TTC</label>
                            <p class="text-gray-900">
                                @if($contrat->total_ttc)
                                    {{ number_format($contrat->total_ttc, 2) }} DH
                                @else
                                    <span class="text-gray-400">Non défini</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Client Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations du client</h2>
                    <div class="space-y-4">
                        <!-- Primary Client -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Client principal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                                    <p class="text-gray-900">{{ $contrat->clientOne->nom }} {{ $contrat->clientOne->prenom }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-gray-900">{{ $contrat->clientOne->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <p class="text-gray-900">{{ $contrat->clientOne->telephone }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Permis de conduire</label>
                                    <p class="text-gray-900">{{ $contrat->clientOne->numero_permis ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Secondary Client -->
                        @if($contrat->clientTwo)
                        <div class="border-l-4 border-green-500 pl-4">
                            <h3 class="font-medium text-gray-900 mb-2">Client secondaire</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                                    <p class="text-gray-900">{{ $contrat->clientTwo->nom }} {{ $contrat->clientTwo->prenom }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-gray-900">{{ $contrat->clientTwo->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <p class="text-gray-900">{{ $contrat->clientTwo->telephone }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Permis de conduire</label>
                                    <p class="text-gray-900">{{ $contrat->clientTwo->numero_permis ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations du véhicule</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Marque</label>
                            <p class="text-gray-900">{{ $contrat->vehicule->marque->marque }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Modèle</label>
                            <p class="text-gray-900">{{ $contrat->vehicule->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Immatriculation</label>
                            <p class="text-gray-900">{{ $contrat->vehicule->immatriculation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full
                                @if($contrat->vehicule->statut == 'disponible') bg-green-100 text-green-800
                                @elseif($contrat->vehicule->statut == 'en_location') bg-blue-100 text-blue-800
                                @elseif($contrat->vehicule->statut == 'en_maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($contrat->vehicule->statut ?? 'Non défini') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($contrat->description || $contrat->lieu_depart || $contrat->lieu_livraison)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations supplémentaires</h2>
                    <div class="space-y-4">
                        @if($contrat->lieu_depart)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de départ</label>
                            <p class="text-gray-900">{{ $contrat->lieu_depart }}</p>
                        </div>
                        @endif
                        
                        @if($contrat->lieu_livraison)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de livraison</label>
                            <p class="text-gray-900">{{ $contrat->lieu_livraison }}</p>
                        </div>
                        @endif
                        
                        @if($contrat->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="text-gray-900">{{ $contrat->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Contract Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statut du contrat</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">État</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($contrat->etat_contrat == 'en cours') bg-green-100 text-green-800
                                @elseif($contrat->etat_contrat == 'termine') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($contrat->etat_contrat ?? 'Non défini') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Véhicule</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($contrat->vehicule->statut == 'disponible') bg-green-100 text-green-800
                                @elseif($contrat->vehicule->statut == 'en_location') bg-blue-100 text-blue-800
                                @elseif($contrat->vehicule->statut == 'en_maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($contrat->vehicule->statut ?? 'Non défini') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <label for="frais_supplementaires" class="block text-sm font-medium text-gray-700 mb-2">Frais supplémentaires (€)</label>
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

<script>
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
    const contractId = formData.get('contrat_id');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Enregistrement...';
    submitBtn.disabled = true;
    
    // Submit to retour-contrats route
    fetch(`/retour-contrats`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            contrat_id: contractId,
            date_retour: formData.get('date_retour'),
            kilometrage_retour: formData.get('kilometrage_retour'),
            niveau_carburant: formData.get('niveau_carburant'),
            etat_vehicule: formData.get('etat_vehicule'),
            observations: formData.get('observations'),
            frais_supplementaires: formData.get('frais_supplementaires') || 0,
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
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
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Close modal when clicking outside
document.getElementById('retourModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRetourModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('retourModal').classList.contains('hidden')) {
        hideRetourModal();
    }
});
</script>
@endsection
