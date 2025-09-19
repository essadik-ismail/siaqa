@extends('layouts.app')

@section('title', 'Détails de l\'Étudiant')

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
                    <h1 class="text-4xl font-bold gradient-text mb-4">Détails de l'Étudiant</h1>
                    <p class="text-gray-600 text-lg">Informations complètes sur l'étudiant</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('students.edit', $student) }}" 
                        class="material-button px-6 py-3">
                        Modifier
                    </a>
                    <a href="{{ route('students.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Personnelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom Complet</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->name }}</p>
                        </div>
                        @if($student->name_ar)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom en Arabe</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->name_ar }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">CIN</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->cin }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de Naissance</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'Non spécifiée' }}
                            </p>
                        </div>
                        @if($student->birth_place)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Lieu de Naissance</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->birth_place }}</p>
                        </div>
                        @endif
                        @if($student->address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Adresse</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Driving School Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Auto-École</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Catégorie de Permis</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                Catégorie {{ $student->license_category }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($student->status)
                                    @case('registered')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('active')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('suspended')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('graduated')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @case('dropped')
                                        bg-red-100 text-red-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($student->status)
                                    @case('registered')
                                        Inscrit
                                        @break
                                    @case('active')
                                        Actif
                                        @break
                                    @case('suspended')
                                        Suspendu
                                        @break
                                    @case('graduated')
                                        Diplômé
                                        @break
                                    @case('dropped')
                                        Abandonné
                                        @break
                                    @default
                                        {{ $student->status }}
                                @endswitch
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date d'Inscription</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $student->registration_date ? $student->registration_date->format('d/m/Y') : 'Non spécifiée' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Numéro d'Étudiant</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->student_number ?? 'Non assigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Progrès de Formation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Theory Progress -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Heures Théoriques</span>
                                <span class="text-sm text-gray-500">
                                    {{ $student->theory_hours_completed ?? 0 }} / {{ $student->required_theory_hours ?? 0 }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" 
                                     style="width: {{ $student->required_theory_hours > 0 ? (($student->theory_hours_completed ?? 0) / $student->required_theory_hours) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Practical Progress -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Heures Pratiques</span>
                                <span class="text-sm text-gray-500">
                                    {{ $student->practical_hours_completed ?? 0 }} / {{ $student->required_practical_hours ?? 0 }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" 
                                     style="width: {{ $student->required_practical_hours > 0 ? (($student->practical_hours_completed ?? 0) / $student->required_practical_hours) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($student->notes)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Notes</h3>
                    <p class="text-gray-700">{{ $student->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Emergency Contact -->
                @if($student->emergency_contact_name || $student->emergency_contact_phone)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact d'Urgence</h3>
                    <div class="space-y-3">
                        @if($student->emergency_contact_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $student->emergency_contact_name }}</p>
                        </div>
                        @endif
                        @if($student->emergency_contact_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $student->emergency_contact_phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Financial Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Financières</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Payé</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($student->total_paid ?? 0, 2) }} DH
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Dû</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($student->total_due ?? 0, 2) }} DH
                            </p>
                        </div>
                        @if(($student->total_due ?? 0) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Solde</label>
                            <p class="mt-1 text-lg font-semibold {{ ($student->total_due ?? 0) - ($student->total_paid ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format(($student->total_due ?? 0) - ($student->total_paid ?? 0), 2) }} DH
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('students.edit', $student) }}" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-center block">
                            Modifier l'Étudiant
                        </a>
                        
                        <a href="{{ route('students.progress', $student) }}" 
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors text-center block">
                            Voir le Progrès
                        </a>
                        
                        <a href="{{ route('students.schedule', $student) }}" 
                            class="w-full px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors text-center block">
                            Voir le Planning
                        </a>
                        
                        <a href="{{ route('students.payments', $student) }}" 
                            class="w-full px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors text-center block">
                            Voir les Paiements
                        </a>

                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
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