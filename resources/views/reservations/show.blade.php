@extends('layouts.app')

@section('title', 'Détails de la Réservation')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Détails de la Réservation</h2>
                    <p class="text-gray-600">Réservation #{{ $reservation->numero_reservation }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('reservations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux Réservations
                    </a>
                    @if($reservation->statut === 'en_attente')
                        <form method="POST" action="{{ route('reservations.confirm', $reservation) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-check mr-2"></i>Confirmer
                            </button>
                        </form>
                    @endif
                    @if($reservation->statut !== 'annulee' && $reservation->statut !== 'terminee')
                        <a href="{{ route('reservations.edit', $reservation) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Reservation Status -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statut de la Réservation</h3>
                    <div class="flex items-center">
                        @php
                            $statusColors = [
                                'en_attente' => 'bg-yellow-100 text-yellow-800',
                                'confirmee' => 'bg-green-100 text-green-800',
                                'annulee' => 'bg-red-100 text-red-800',
                                'terminee' => 'bg-blue-100 text-blue-800'
                            ];
                            $statusLabels = [
                                'en_attente' => 'En Attente',
                                'confirmee' => 'Confirmée',
                                'annulee' => 'Annulée',
                                'terminee' => 'Terminée'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$reservation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$reservation->statut] ?? ucfirst($reservation->statut) }}
                        </span>
                    </div>
                </div>

                <!-- Reservation Details -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Détails de la Réservation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de Réservation</label>
                            <p class="text-gray-900 font-mono">{{ $reservation->numero_reservation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix Total</label>
                            <p class="text-gray-900 font-semibold">{{ number_format($reservation->prix_total, 2) }} DH</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Caution</label>
                            <p class="text-gray-900">{{ number_format($reservation->caution, 2) }} DH</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de Passagers</label>
                            <p class="text-gray-900">{{ $reservation->nombre_passagers }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de Début</label>
                            <p class="text-gray-900">{{ $reservation->date_debut->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de Fin</label>
                            <p class="text-gray-900">{{ $reservation->date_fin->format('d/m/Y') }}</p>
                        </div>
                        @if($reservation->heure_debut)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure de Début</label>
                            <p class="text-gray-900">{{ $reservation->heure_debut->format('H:i') }}</p>
                        </div>
                        @endif
                        @if($reservation->heure_fin)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure de Fin</label>
                            <p class="text-gray-900">{{ $reservation->heure_fin->format('H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de Départ</label>
                            <p class="text-gray-900">{{ $reservation->lieu_depart }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de Retour</label>
                            <p class="text-gray-900">{{ $reservation->lieu_retour }}</p>
                        </div>
                    </div>
                    @if($reservation->options)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($reservation->options as $option)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $option }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($reservation->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $reservation->notes }}</p>
                    </div>
                    @endif
                    @if($reservation->motif_annulation)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'Annulation</label>
                        <p class="text-red-600 bg-red-50 p-3 rounded-lg">{{ $reservation->motif_annulation }}</p>
                    </div>
                    @endif
                </div>

                <!-- Vehicle Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du Véhicule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Véhicule</label>
                            <p class="text-gray-900 font-semibold">{{ $reservation->vehicule->marque->nom ?? 'N/A' }} {{ $reservation->vehicule->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plaque d'Immatriculation</label>
                            <p class="text-gray-900 font-mono">{{ $reservation->vehicule->plaque_immatriculation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type de Carburant</label>
                            <p class="text-gray-900">{{ $reservation->vehicule->type_carburant }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de Places</label>
                            <p class="text-gray-900">{{ $reservation->vehicule->nombre_places }}</p>
                        </div>
                        @if($reservation->vehicule->agence)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                            <p class="text-gray-900">{{ $reservation->vehicule->agence->nom_agence }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Client Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du Client</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom Complet</label>
                            <p class="text-gray-900 font-semibold">{{ $reservation->client->prenom }} {{ $reservation->client->nom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $reservation->client->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <p class="text-gray-900">{{ $reservation->client->telephone }}</p>
                        </div>
                        @if($reservation->client->adresse)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <p class="text-gray-900">{{ $reservation->client->adresse }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contract Information -->
                @if($reservation->contrat)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contrat Associé</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de Contrat</label>
                            <p class="text-gray-900 font-mono">{{ $reservation->contrat->number_contrat }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                {{ ucfirst($reservation->contrat->etat_contrat ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="pt-3">
                            <a href="{{ route('contrats.show', $reservation->contrat) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                                <i class="fas fa-file-contract mr-2"></i>Voir le Contrat
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contrat</h3>
                    <p class="text-gray-600 text-sm mb-4">Aucun contrat n'est associé à cette réservation.</p>
                    @if($reservation->statut === 'confirmee')
                    <a href="{{ route('contrats.create', ['reservation_id' => $reservation->id]) }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                        <i class="fas fa-plus mr-2"></i>Créer un Contrat
                    </a>
                    @endif
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('reservations.index') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                            <i class="fas fa-list mr-2"></i>Toutes les Réservations
                        </a>
                        <a href="{{ route('reservations.edit', $reservation) }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                            <i class="fas fa-edit mr-2"></i>Modifier la Réservation
                        </a>
                        @if($reservation->statut === 'en_attente')
                        <form method="POST" action="{{ route('reservations.confirm', $reservation) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-medium">
                                <i class="fas fa-check mr-2"></i>Confirmer la Réservation
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Reservation Timeline -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Historique</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Réservation créée</p>
                                <p class="text-xs text-gray-500">{{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @if($reservation->statut !== 'en_attente')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Statut mis à jour</p>
                                <p class="text-xs text-gray-500">{{ $reservation->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
