@extends('layouts.app')

@section('title', 'Modifier l\'Examen')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Modifier l'Examen</h1>
            <p class="text-gray-600 text-lg">Modifiez les informations de l'examen</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('exams.update', $exam) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Étudiant *
                            </label>
                            <select name="student_id" id="student_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $exam->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructor Selection -->
                        <div>
                            <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructeur *
                            </label>
                            <select name="instructor_id" id="instructor_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner un instructeur</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id', $exam->instructor_id) == $instructor->id ? 'selected' : '' }}>
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
                                <option value="theory" {{ old('exam_type', $exam->exam_type) == 'theory' ? 'selected' : '' }}>Théorique</option>
                                <option value="practical" {{ old('exam_type', $exam->exam_type) == 'practical' ? 'selected' : '' }}>Pratique</option>
                                <option value="final" {{ old('exam_type', $exam->exam_type) == 'final' ? 'selected' : '' }}>Final</option>
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
                                <option value="A" {{ old('license_category', $exam->license_category) == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                                <option value="B" {{ old('license_category', $exam->license_category) == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                                <option value="C" {{ old('license_category', $exam->license_category) == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                                <option value="D" {{ old('license_category', $exam->license_category) == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
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
                                value="{{ old('scheduled_at', $exam->scheduled_at ? $exam->scheduled_at->format('Y-m-d\TH:i') : '') }}"
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
                                value="{{ old('duration', $exam->duration) }}"
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
                            placeholder="Décrivez les détails de l'examen...">{{ old('description', $exam->description) }}</textarea>
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
                        Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection