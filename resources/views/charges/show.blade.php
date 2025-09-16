@extends('layouts.app')

@section('title', 'Détails de la Charge')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <p class="text-gray-600">Informations complètes sur la charge</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('charges.edit', $charge) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('charges.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de la charge</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Désignation</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $charge->designation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <p class="text-gray-900">{{ $charge->date ? $charge->date->format('d/m/Y') : 'Non définie' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Montant</label>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($charge->montant, 2) }} DH</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fichier</label>
                            <p class="text-gray-900">{{ $charge->fichier ?: 'Aucun fichier' }}</p>
                        </div>
                    </div>
                </div>

                @if($charge->description)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                    <p class="text-gray-700">{{ $charge->description }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('charges.edit', $charge) }}" class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                            <i class="fas fa-edit mr-2"></i>Modifier la charge
                        </a>
                        <a href="{{ route('charges.index') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                            <i class="fas fa-list mr-2"></i>Voir toutes les charges
                        </a>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations supplémentaires</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Créé le</span>
                            <span class="text-gray-900">{{ $charge->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($charge->updated_at != $charge->created_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Modifié le</span>
                            <span class="text-gray-900">{{ $charge->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
