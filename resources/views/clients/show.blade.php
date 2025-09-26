@extends('layouts.app')

@section('title', 'Détails Client')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Détails Client</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Client Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $client->full_name }}</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('clients.edit', $client) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('clients.toggle-blacklist', $client) }}" class="inline">
                            @csrf
                            <button type="button" class="bg-{{ $client->is_blacklisted ? 'green' : 'red' }}-600 hover:bg-{{ $client->is_blacklisted ? 'green' : 'red' }}-700 text-white px-3 py-1 rounded text-sm"
                                onclick="showBlacklistModal('{{ $client->full_name }}', {{ $client->is_blacklisted ? 'true' : 'false' }}, '{{ route('clients.toggle-blacklist', $client) }}')">
                                <i class="fas fa-{{ $client->is_blacklisted ? 'check' : 'ban' }} mr-1"></i>{{ $client->is_blacklisted ? 'Déblacklister' : 'Blacklister' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                                <dd class="text-sm text-gray-900">{{ $client->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($client->type == 'client') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($client->type) }}
                                    </span>
                                </dd>
                            </div>
                            @if($client->type == 'societe')
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom de la société</dt>
                                <dd class="text-sm text-gray-900">{{ $client->nom_societe ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ICE</dt>
                                <dd class="text-sm text-gray-900">{{ $client->ice_societe ?? 'Non spécifié' }}</dd>
                            </div>
                            @else
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                                <dd class="text-sm text-gray-900">{{ $client->date_naissance ? $client->date_naissance->format('d/m/Y') : 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lieu de naissance</dt>
                                <dd class="text-sm text-gray-900">{{ $client->lieu_de_naissance ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nationalité</dt>
                                <dd class="text-sm text-gray-900">{{ $client->nationalite ?? 'Non spécifié' }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $client->email ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="text-sm text-gray-900">{{ $client->telephone ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="text-sm text-gray-900">{{ $client->adresse ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ville</dt>
                                <dd class="text-sm text-gray-900">{{ $client->ville ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Code postal</dt>
                                <dd class="text-sm text-gray-900">{{ $client->postal_code ?? 'Non spécifié' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($client->type == 'client')
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de permis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Numéro de permis</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->numero_permis ?? 'Non spécifié' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date d'obtention</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->date_permis ? $client->date_permis->format('d/m/Y') : 'Non spécifié' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Numéro CIN</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->numero_cin ?? 'Non spécifié' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date d'expiration CIN</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->date_cin_expiration ? $client->date_cin_expiration->format('d/m/Y') : 'Non spécifié' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Passeport</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->passport ?? 'Non spécifié' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                @endif

                @if($client->description)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                    <p class="text-sm text-gray-900">{{ $client->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Statut</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($client->is_blacklisted) bg-red-100 text-red-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $client->is_blacklisted ? 'Blacklisté' : 'Actif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Membre depuis</span>
                        <span class="text-sm text-gray-900">{{ $client->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dernière mise à jour</span>
                        <span class="text-sm text-gray-900">{{ $client->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Réservations</span>
                        <span class="text-sm font-medium text-gray-900">{{ $client->reservations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Section -->
    @if($client->reservations->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réservations récentes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($client->reservations->take(5) as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($reservation->vehicule && $reservation->vehicule->marque)
                                    {{ $reservation->vehicule->marque }} {{ $reservation->vehicule->name ?? 'N/A' }}
                                    <br><span class="text-xs text-gray-500">{{ $reservation->vehicule->immatriculation }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reservation->date_debut ? $reservation->date_debut->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reservation->prix_total ? number_format($reservation->prix_total, 2) . ' €' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($reservation->statut == 'confirmee') bg-green-100 text-green-800
                                    @elseif($reservation->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->statut == 'terminee') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
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
        const blacklistModal = document.getElementById('blacklistModal');
        
        // Close modal when clicking outside
        if (event.target === blacklistModal) {
            hideBlacklistModal();
        }
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideBlacklistModal();
        }
        
        // Tab navigation within modal
        if (event.key === 'Tab') {
            const activeModal = document.querySelector('#blacklistModal:not(.hidden)');
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
        const blacklistForm = document.getElementById('blacklistForm');
        
        if (blacklistForm) {
            blacklistForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const submitText = submitBtn.querySelector('#blacklistSubmitText');
                const originalText = submitText.textContent;
                
                submitText.textContent = 'Traitement...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        }
    });
</script>
@endpush 