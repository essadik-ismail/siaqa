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

                <!-- Tabs for Lessons, Exams, and Payments -->
                <div class="material-card p-6">
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button onclick="switchTab('lessons')" id="lessons-tab" 
                                class="tab-button active py-2 px-1 border-b-2 font-medium text-sm">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Leçons ({{ $student->lessons->count() ?? 0 }})
                            </button>
                            <button onclick="switchTab('exams')" id="exams-tab" 
                                class="tab-button py-2 px-1 border-b-2 font-medium text-sm">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Examens ({{ $student->exams->count() ?? 0 }})
                            </button>
                            <button onclick="switchTab('payments')" id="payments-tab" 
                                class="tab-button py-2 px-1 border-b-2 font-medium text-sm">
                                <i class="fas fa-credit-card mr-2"></i>
                                Paiements ({{ $student->payments->count() ?? 0 }})
                            </button>
                        </nav>
                    </div>

                    <!-- Lessons Tab Content -->
                    <div id="lessons-content" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Leçons de l'Étudiant</h3>
                            <a href="{{ route('lessons.create', ['student_id' => $student->id]) }}" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Nouvelle Leçon
                            </a>
                        </div>
                        
                        @if(isset($student->lessons) && $student->lessons->count() > 0)
                            <div class="space-y-4">
                                @foreach($student->lessons as $lesson)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        Leçon du {{ $lesson->scheduled_at ? $lesson->scheduled_at->format('d/m/Y à H:i') : 'Date non définie' }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        @if($lesson->instructor)
                                                            Instructeur: {{ $lesson->instructor->user->name ?? 'Non assigné' }}
                                                        @endif
                                                        @if($lesson->vehicule)
                                                            • Véhicule: {{ $lesson->vehicule->name }}
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Durée: {{ $lesson->duration ?? 60 }} minutes
                                                        @if($lesson->status)
                                                            • Statut: 
                                                            <span class="font-medium
                                                                @if($lesson->status === 'completed') text-green-600
                                                                @elseif($lesson->status === 'in_progress') text-blue-600
                                                                @elseif($lesson->status === 'cancelled') text-red-600
                                                                @else text-gray-600
                                                                @endif">
                                                                {{ ucfirst($lesson->status) }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('lessons.show', $lesson) }}" 
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('lessons.edit', $lesson) }}" 
                                                class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                                <p>Aucune leçon enregistrée pour cet étudiant.</p>
                                <a href="{{ route('lessons.create', ['student_id' => $student->id]) }}" 
                                    class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Créer la première leçon
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Exams Tab Content -->
                    <div id="exams-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Examens de l'Étudiant</h3>
                            <a href="{{ route('exams.create', ['student_id' => $student->id]) }}" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Nouvel Examen
                            </a>
                        </div>
                        
                        @if(isset($student->exams) && $student->exams->count() > 0)
                            <div class="space-y-4">
                                @foreach($student->exams as $exam)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-clipboard-check text-green-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        {{ ucfirst($exam->exam_type ?? 'N/A') }} - {{ $exam->scheduled_at ? $exam->scheduled_at->format('d/m/Y à H:i') : ($exam->created_at ? $exam->created_at->format('d/m/Y à H:i') : 'Date non définie') }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        @if($exam->instructor)
                                                            Instructeur: {{ $exam->instructor->user->name ?? 'Non assigné' }}
                                                        @endif
                                                        @if($exam->location)
                                                            • Lieu: {{ $exam->location }}
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if($exam->score !== null)
                                                            Score: {{ $exam->score }}/100
                                                        @endif
                                                        @if($exam->status)
                                                            • Statut: 
                                                            <span class="font-medium
                                                                @if($exam->status === 'passed') text-green-600
                                                                @elseif($exam->status === 'failed') text-red-600
                                                                @elseif($exam->status === 'in_progress') text-blue-600
                                                                @else text-gray-600
                                                                @endif">
                                                                {{ ucfirst($exam->status) }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('exams.show', $exam) }}" 
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('exams.edit', $exam) }}" 
                                                class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-clipboard-check text-4xl mb-4"></i>
                                <p>Aucun examen enregistré pour cet étudiant.</p>
                                <a href="{{ route('exams.create', ['student_id' => $student->id]) }}" 
                                    class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    Créer le premier examen
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Payments Tab Content -->
                    <div id="payments-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Paiements de l'Étudiant</h3>
                            <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" 
                                class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Nouveau Paiement
                            </a>
                        </div>
                        
                        @if(isset($student->payments) && $student->payments->count() > 0)
                            <div class="space-y-4">
                                @foreach($student->payments as $payment)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-credit-card text-yellow-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        Paiement du {{ $payment->created_at->format('d/m/Y') }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        Montant: <span class="font-semibold">{{ number_format($payment->amount, 2) }} DH</span>
                                                        @if($payment->method)
                                                            • Méthode: {{ ucfirst($payment->method) }}
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if($payment->description)
                                                            {{ $payment->description }}
                                                        @endif
                                                        • Statut: 
                                                        <span class="font-medium
                                                            @if($payment->status === 'paid') text-green-600
                                                            @elseif($payment->status === 'pending') text-yellow-600
                                                            @elseif($payment->status === 'failed') text-red-600
                                                            @else text-gray-600
                                                            @endif">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('payments.show', $payment) }}" 
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('payments.edit', $payment) }}" 
                                                class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-credit-card text-4xl mb-4"></i>
                                <p>Aucun paiement enregistré pour cet étudiant.</p>
                                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" 
                                    class="mt-4 inline-block bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                                    Enregistrer le premier paiement
                                </a>
                            </div>
                        @endif
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

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>

<style>
.tab-button {
    transition: all 0.2s ease-in-out;
}

.tab-button:hover {
    color: #374151;
}

.tab-button.active {
    border-color: #3b82f6;
    color: #2563eb;
}
</style>
@endsection