@extends('layouts.app')

@section('title', 'Créer une Leçon')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Créer une Leçon</h1>
            <p class="text-gray-600 text-lg">Programmez une nouvelle leçon de conduite</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('lessons.store') }}" method="POST" class="space-y-6">
                @csrf
                
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
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vehicle Selection -->
                        <div>
                            <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Véhicule *
                            </label>
                            <select name="vehicle_id" id="vehicle_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner un véhicule</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->marque }} {{ $vehicle->modele }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lesson Type -->
                        <div>
                            <label for="lesson_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de Leçon *
                            </label>
                            <select name="lesson_type" id="lesson_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner le type</option>
                                <option value="theory" {{ old('lesson_type') == 'theory' ? 'selected' : '' }}>Théorique</option>
                                <option value="practical" {{ old('lesson_type') == 'practical' ? 'selected' : '' }}>Pratique</option>
                                <option value="simulation" {{ old('lesson_type') == 'simulation' ? 'selected' : '' }}>Simulation</option>
                            </select>
                            @error('lesson_type')
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
                            placeholder="Décrivez les détails de la leçon...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('lessons.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="material-button px-6 py-3">
                        Créer la Leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection