@extends('layouts.app')

@section('title', 'Schedule New Theory Class')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('theory-classes.index') }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Schedule New Theory Class</h1>
                    <p class="mt-2 text-gray-600">Create a new theory class</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('theory-classes.store') }}" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Class Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Class Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               placeholder="e.g., Traffic Rules and Regulations"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="license_category" class="block text-sm font-medium text-gray-700 mb-2">License Category *</label>
                        <select id="license_category" name="license_category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('license_category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="A" {{ old('license_category') == 'A' ? 'selected' : '' }}>Category A (Motorcycle)</option>
                            <option value="B" {{ old('license_category') == 'B' ? 'selected' : '' }}>Category B (Car)</option>
                            <option value="C" {{ old('license_category') == 'C' ? 'selected' : '' }}>Category C (Truck)</option>
                            <option value="D" {{ old('license_category') == 'D' ? 'selected' : '' }}>Category D (Bus)</option>
                        </select>
                        @error('license_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date & Time *</label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('scheduled_at') border-red-500 @enderror">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                        <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required min="15" max="480"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('duration_minutes') border-red-500 @enderror">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="room" class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                        <input type="text" id="room" name="room" value="{{ old('room') }}"
                               placeholder="e.g., Room 101, Main Hall"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('room') border-red-500 @enderror">
                        @error('room')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror">
                            <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="Describe what this class covers"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Instructor Selection -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Instructor</h3>
                <div>
                    <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">Select Instructor *</label>
                    <select id="instructor_id" name="instructor_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('instructor_id') border-red-500 @enderror">
                        <option value="">Select Instructor</option>
                        @foreach(\App\Models\Instructor::with('user')->get() as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->user->name ?? 'Unknown' }}
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Class Materials -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Class Materials</h3>
                <div>
                    <label for="materials" class="block text-sm font-medium text-gray-700 mb-2">Materials (one per line)</label>
                    <textarea id="materials" name="materials" rows="4"
                              placeholder="Enter materials, one per line&#10;e.g.,&#10;Traffic Rules Handbook&#10;Road Signs Guide&#10;Safety Manual"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('materials') border-red-500 @enderror">{{ old('materials') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Enter each material on a new line</p>
                    @error('materials')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('theory-classes.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Schedule Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection