@extends('layouts.app')

@section('title', 'Modifier la Réservation')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Modifier la Réservation</h2>
                    <p class="text-gray-600">Réservation #{{ $reservation->numero_reservation }}</p>
                </div>
                <a href="{{ route('reservations.show', $reservation) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux Détails
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <h4 class="font-bold">Erreurs de validation :</h4>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Client and Vehicle Selection -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Sélection du Client et du Véhicule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                            <select id="client_id" name="client_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                                <option value="">Sélectionnez un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (old('client_id', $reservation->client_id) == $client->id) ? 'selected' : '' }}>
                                        {{ $client->prenom }} {{ $client->nom }} - {{ $client->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                            <select id="vehicule_id" name="vehicule_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                                <option value="">Sélectionnez un véhicule</option>
                                @foreach($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}" {{ (old('vehicule_id', $reservation->vehicule_id) == $vehicule->id) ? 'selected' : '' }}>
                                        {{ $vehicule->marque->nom ?? 'N/A' }} {{ $vehicule->name }} - {{ $vehicule->plaque_immatriculation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicule_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates and Times -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Dates et Heures</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de Début *</label>
                            <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', $reservation->date_debut->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_debut') border-red-500 @enderror">
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Date de Fin *</label>
                            <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin', $reservation->date_fin->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_fin') border-red-500 @enderror">
                            @error('date_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-2">Heure de Début</label>
                            <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut', $reservation->heure_debut ? $reservation->heure_debut->format('H:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('heure_debut') border-red-500 @enderror">
                            @error('heure_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-2">Heure de Fin</label>
                            <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin', $reservation->heure_fin ? $reservation->heure_fin->format('H:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('heure_fin') border-red-500 @enderror">
                            @error('heure_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Locations -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Lieux</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="lieu_depart" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Départ *</label>
                            <input type="text" id="lieu_depart" name="lieu_depart" value="{{ old('lieu_depart', $reservation->lieu_depart) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu_depart') border-red-500 @enderror"
                                   placeholder="Entrez le lieu de départ">
                            @error('lieu_depart')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="lieu_retour" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Retour *</label>
                            <input type="text" id="lieu_retour" name="lieu_retour" value="{{ old('lieu_retour', $reservation->lieu_retour) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu_retour') border-red-500 @enderror"
                                   placeholder="Entrez le lieu de retour">
                            @error('lieu_retour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing and Details -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tarification et Détails</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="prix_total" class="block text-sm font-medium text-gray-700 mb-2">Prix Total *</label>
                            <input type="number" id="prix_total" name="prix_total" value="{{ old('prix_total', $reservation->prix_total) }}" step="0.01" min="0" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prix_total') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('prix_total')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="caution" class="block text-sm font-medium text-gray-700 mb-2">Caution</label>
                            <input type="number" id="caution" name="caution" value="{{ old('caution', $reservation->caution) }}" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('caution') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('caution')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nombre_passagers" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Passagers *</label>
                            <input type="number" id="nombre_passagers" name="nombre_passagers" value="{{ old('nombre_passagers', $reservation->nombre_passagers) }}" min="1" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre_passagers') border-red-500 @enderror"
                                   placeholder="1">
                            @error('nombre_passagers')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Statut</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut de la Réservation *</label>
                            <select id="statut" name="statut" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                                <option value="en_attente" {{ (old('statut', $reservation->statut) === 'en_attente') ? 'selected' : '' }}>En Attente</option>
                                <option value="confirmee" {{ (old('statut', $reservation->statut) === 'confirmee') ? 'selected' : '' }}>Confirmée</option>
                                <option value="annulee" {{ (old('statut', $reservation->statut) === 'annulee') ? 'selected' : '' }}>Annulée</option>
                                <option value="terminee" {{ (old('statut', $reservation->statut) === 'terminee') ? 'selected' : '' }}>Terminée</option>
                            </select>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Cancellation Reason (shown when status is cancelled) -->
                    <div id="cancellation-reason" class="mt-4" style="display: none;">
                        <label for="motif_annulation" class="block text-sm font-medium text-gray-700 mb-2">Motif d'Annulation *</label>
                        <textarea id="motif_annulation" name="motif_annulation" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motif_annulation') border-red-500 @enderror"
                                  placeholder="Veuillez fournir un motif d'annulation...">{{ old('motif_annulation', $reservation->motif_annulation) }}</textarea>
                        @error('motif_annulation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Options -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Options</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $options = old('options', $reservation->options ?? []);
                            $availableOptions = [
                                'GPS' => 'GPS',
                                'Siège bébé' => 'Siège bébé',
                                'Chauffeur' => 'Chauffeur',
                                'Assurance complète' => 'Assurance complète',
                                'Kilométrage illimité' => 'Kilométrage illimité',
                                'Climatisation' => 'Climatisation',
                                'Radio' => 'Radio',
                                'Autres' => 'Autres'
                            ];
                        @endphp
                        @foreach($availableOptions as $value => $label)
                            <div class="flex items-center">
                                <input type="checkbox" id="option_{{ $loop->index }}" name="options[]" value="{{ $value }}"
                                       {{ in_array($value, $options) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="option_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Notes</h3>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes Supplémentaires</label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Entrez des notes supplémentaires sur la réservation...">{{ old('notes', $reservation->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('reservations.show', $reservation) }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const vehiculeSelect = document.getElementById('vehicule_id');
    const prixTotal = document.getElementById('prix_total');
    const statutSelect = document.getElementById('statut');
    const cancellationReason = document.getElementById('cancellation-reason');
    const motifAnnulation = document.getElementById('motif_annulation');
    
    // Show/hide cancellation reason based on status
    function toggleCancellationReason() {
        if (statutSelect.value === 'annulee') {
            cancellationReason.style.display = 'block';
            motifAnnulation.required = true;
        } else {
            cancellationReason.style.display = 'none';
            motifAnnulation.required = false;
        }
    }
    
    // Auto-calculate price based on vehicle selection and dates
    function calculatePrice() {
        if (vehiculeSelect.value && dateDebut.value && dateFin.value) {
            const startDate = new Date(dateDebut.value);
            const endDate = new Date(dateFin.value);
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            
            // Get vehicle price from selected option (you might need to fetch this via AJAX)
            // For now, we'll just show a placeholder
            if (days > 0) {
                prixTotal.placeholder = `Calculé sur ${days} jour(s)`;
            }
        }
    }
    
    // Add event listeners
    dateDebut.addEventListener('change', calculatePrice);
    dateFin.addEventListener('change', calculatePrice);
    vehiculeSelect.addEventListener('change', calculatePrice);
    statutSelect.addEventListener('change', toggleCancellationReason);
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const startDate = new Date(dateDebut.value);
        const endDate = new Date(dateFin.value);
        
        if (endDate <= startDate) {
            e.preventDefault();
            alert('La date de fin doit être postérieure à la date de début.');
            dateFin.focus();
            return false;
        }
        
        if (startDate < new Date()) {
            if (!confirm('La date de début est dans le passé. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Check if cancellation reason is required
        if (statutSelect.value === 'annulee' && !motifAnnulation.value.trim()) {
            e.preventDefault();
            alert('Le motif d\'annulation est requis quand la réservation est annulée.');
            motifAnnulation.focus();
            return false;
        }
    });
    
    // Initialize
    calculatePrice();
    toggleCancellationReason();
});
</script>
@endsection
