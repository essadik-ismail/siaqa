@extends('layouts.app')

@section('title', 'Détails de l\'Examen')

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
                    <h1 class="text-4xl font-bold gradient-text mb-4">Détails de l'Examen</h1>
                    <p class="text-gray-600 text-lg">Informations complètes sur l'examen</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('exams.edit', $exam) }}" 
                        class="material-button px-6 py-3">
                        Modifier
                    </a>
                    <a href="{{ route('exams.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Exam Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type d'Examen</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                @switch($exam->exam_type)
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
                                        {{ $exam->exam_type }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Catégorie de Permis</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                Catégorie {{ $exam->license_category }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date et Heure</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $exam->scheduled_at ? $exam->scheduled_at->format('d/m/Y à H:i') : 'Non programmé' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Durée</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $exam->duration }} minutes
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($exam->status)
                                    @case('scheduled')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('in_progress')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('passed')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('failed')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('cancelled')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($exam->status)
                                    @case('scheduled')
                                        Programmé
                                        @break
                                    @case('in_progress')
                                        En Cours
                                        @break
                                    @case('passed')
                                        Réussi
                                        @break
                                    @case('failed')
                                        Échoué
                                        @break
                                    @case('cancelled')
                                        Annulé
                                        @break
                                    @default
                                        {{ $exam->status }}
                                @endswitch
                            </span>
                        </div>
                        @if($exam->result)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Résultat</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $exam->result }}%
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($exam->description)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $exam->description }}</p>
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
                                {{ $exam->student->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        @if($exam->student)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $exam->student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $exam->student->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Instructor Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Instructeur</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $exam->instructor->user->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        @if($exam->instructor)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $exam->instructor->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Expérience</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $exam->instructor->years_experience }} ans</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($exam->status === 'scheduled')
                        <form action="{{ route('exams.start', $exam) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors">
                                Commencer l'Examen
                            </button>
                        </form>
                        @endif

                        @if($exam->status === 'in_progress')
                        <form action="{{ route('exams.complete', $exam) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                Terminer l'Examen
                            </button>
                        </form>
                        @endif

                        @if(in_array($exam->status, ['scheduled', 'in_progress']))
                        <form action="{{ route('exams.cancel', $exam) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                                Annuler l'Examen
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?')">
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