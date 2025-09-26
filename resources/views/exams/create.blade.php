@extends('layouts.app')

@section('title', 'Créer un Examen')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Créer un Examen</h1>
            <p class="text-gray-600 text-lg">Programmez un nouvel examen de conduite</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('exams.store') }}" method="POST" class="space-y-6" id="exam-form">
                @csrf
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div class="md:col-span-2">
                            <label for="student_ids" class="block text-sm font-medium text-gray-700 mb-2">
                                Étudiants * (Sélection multiple)
                            </label>
                            <div class="relative">
                                <select name="student_ids[]" id="student_ids" multiple required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-h-[120px]">
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                            {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->student_number ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs étudiants
                            </p>
                            @error('student_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('student_ids.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructor Selection -->
                        <div>
                            <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructeur
                            </label>
                            <select name="instructor_id" id="instructor_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner un instructeur</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Exam Type -->
                        <div>
                            <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type d'Examen *
                            </label>
                            <select name="exam_type" id="exam_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner le type</option>
                                <option value="theory" {{ old('exam_type') == 'theory' ? 'selected' : '' }}>Théorique</option>
                                <option value="practical" {{ old('exam_type') == 'practical' ? 'selected' : '' }}>Pratique</option>
                                <option value="final" {{ old('exam_type') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                            @error('exam_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Category -->
                        <div>
                            <label for="license_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie de Permis *
                            </label>
                            <select name="license_category" id="license_category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner la catégorie</option>
                                <option value="A" {{ old('license_category') == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                                <option value="B" {{ old('license_category') == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                                <option value="C" {{ old('license_category') == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                                <option value="D" {{ old('license_category') == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
                            </select>
                            @error('license_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Date -->
                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et Heure *
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                                value="{{ old('scheduled_at') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('scheduled_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Durée (minutes) *
                            </label>
                            <input type="number" name="duration" id="duration" required min="30" max="180"
                                value="{{ old('duration', 60) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Décrivez les détails de l'examen...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('exams.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="material-button px-6 py-3">
                        Créer l'Examen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#student_ids {
    background-image: none;
}

#student_ids option {
    padding: 8px 12px;
}

#student_ids option:checked {
    background-color: #3b82f6;
    color: white;
}

.student-selection-container {
    position: relative;
}

.student-selection-container::after {
    content: "Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs étudiants";
    position: absolute;
    bottom: -20px;
    left: 0;
    font-size: 12px;
    color: #6b7280;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_ids');
    const selectedCount = document.createElement('div');
    selectedCount.className = 'mt-2 text-sm text-blue-600 font-medium';
    studentSelect.parentNode.appendChild(selectedCount);
    
    function updateSelectedCount() {
        const selected = Array.from(studentSelect.selectedOptions);
        selectedCount.textContent = `${selected.length} étudiant(s) sélectionné(s)`;
        
        if (selected.length === 0) {
            selectedCount.textContent = 'Aucun étudiant sélectionné';
            selectedCount.className = 'mt-2 text-sm text-red-600 font-medium';
        } else {
            selectedCount.className = 'mt-2 text-sm text-blue-600 font-medium';
        }
    }
    
    studentSelect.addEventListener('change', updateSelectedCount);
    updateSelectedCount();
    
    
    // Add keyboard shortcuts
    studentSelect.addEventListener('keydown', function(e) {
        if (e.key === 'a' && e.ctrlKey) {
            e.preventDefault();
            Array.from(studentSelect.options).forEach(option => {
                option.selected = true;
            });
            updateSelectedCount();
        }
    });
    
    // Add select all button
    const selectAllBtn = document.createElement('button');
    selectAllBtn.type = 'button';
    selectAllBtn.textContent = 'Sélectionner tout';
    selectAllBtn.className = 'mt-2 px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors';
    selectAllBtn.onclick = function() {
        Array.from(studentSelect.options).forEach(option => {
            option.selected = true;
        });
        updateSelectedCount();
    };
    studentSelect.parentNode.appendChild(selectAllBtn);
    
    // Add clear selection button
    const clearBtn = document.createElement('button');
    clearBtn.type = 'button';
    clearBtn.textContent = 'Effacer la sélection';
    clearBtn.className = 'mt-2 ml-2 px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors';
    clearBtn.onclick = function() {
        Array.from(studentSelect.options).forEach(option => {
            option.selected = false;
        });
        updateSelectedCount();
    };
    studentSelect.parentNode.appendChild(clearBtn);
});
</script>
@endsection