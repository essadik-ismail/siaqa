@extends('layouts.app')

@section('title', 'Détails du Paiement')

@section('content')
<div class="min-h-screen">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold gradient-text mb-4">Détails du Paiement</h1>
                    <p class="text-gray-600 text-lg">Informations complètes sur le paiement</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('payments.edit', $payment) }}" 
                        class="material-button px-6 py-3">
                        Modifier
                    </a>
                    <a href="{{ route('payments.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type de Paiement</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                @switch($payment->payment_type)
                                    @case('lesson')
                                        Leçon
                                        @break
                                    @case('exam')
                                        Examen
                                        @break
                                    @case('package')
                                        Forfait
                                        @break
                                    @case('registration')
                                        Inscription
                                        @break
                                    @case('other')
                                        Autre
                                        @break
                                    @default
                                        {{ $payment->payment_type }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Montant</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($payment->amount, 2) }} DH
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Méthode de Paiement</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                @switch($payment->payment_method)
                                    @case('cash')
                                        Espèces
                                        @break
                                    @case('bank_transfer')
                                        Virement Bancaire
                                        @break
                                    @case('check')
                                        Chèque
                                        @break
                                    @case('card')
                                        Carte Bancaire
                                        @break
                                    @case('mobile_money')
                                        Mobile Money
                                        @break
                                    @default
                                        {{ $payment->payment_method }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de Paiement</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Non spécifiée' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($payment->status)
                                    @case('pending')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('paid')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('failed')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('refunded')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($payment->status)
                                    @case('pending')
                                        En Attente
                                        @break
                                    @case('paid')
                                        Payé
                                        @break
                                    @case('failed')
                                        Échoué
                                        @break
                                    @case('refunded')
                                        Remboursé
                                        @break
                                    @default
                                        {{ $payment->status }}
                                @endswitch
                            </span>
                        </div>
                        @if($payment->reference)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Référence</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $payment->reference }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($payment->description)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $payment->description }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Student Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Étudiant</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $payment->student->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        @if($payment->student)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $payment->student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $payment->student->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Related Information -->
                @if($payment->lesson)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Leçon Associée</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                @switch($payment->lesson->lesson_type)
                                    @case('theory')
                                        Théorique
                                        @break
                                    @case('practical')
                                        Pratique
                                        @break
                                    @case('simulation')
                                        Simulation
                                        @break
                                    @default
                                        {{ $payment->lesson->lesson_type }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date</label>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $payment->lesson->scheduled_at ? $payment->lesson->scheduled_at->format('d/m/Y à H:i') : 'Non programmé' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if($payment->exam)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Examen Associé</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                @switch($payment->exam->exam_type)
                                    @case('theory')
                                        Théorique
                                        @break
                                    @case('practical')
                                        Pratique
                                        @break
                                    @case('final')
                                        Final
                                        @break
                                    @default
                                        {{ $payment->exam->exam_type }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date</label>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $payment->exam->scheduled_at ? $payment->exam->scheduled_at->format('d/m/Y à H:i') : 'Non programmé' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($payment->status === 'pending')
                        <form action="{{ route('payments.mark-paid', $payment) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                Marquer comme Payé
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection