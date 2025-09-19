@extends('layouts.app')

@section('title', 'Available Slots')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Available Slots</h1>
                    <p class="mt-2 text-gray-600">Find available time slots for scheduling lessons</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('lessons.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Schedule Lesson
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructor</label>
                    <select name="instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Instructors</option>
                        @foreach(\App\Models\Instructor::with('user')->get() as $instructor)
                            <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->user->name ?? 'Unknown' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <select name="duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="60" {{ request('duration', 60) == 60 ? 'selected' : '' }}>60 minutes</option>
                        <option value="90" {{ request('duration') == 90 ? 'selected' : '' }}>90 minutes</option>
                        <option value="120" {{ request('duration') == 120 ? 'selected' : '' }}>120 minutes</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Available Slots -->
            <div class="lg:col-span-2 space-y-6">
                @if($instructor)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Slots for {{ $instructor->user->name ?? 'Unknown' }}</h3>
                        <p class="text-gray-600 mb-4">{{ $date->format('l, M d, Y') }} • {{ $duration }} minutes</p>
                        
                        @if($slots->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($slots as $slot)
                                    <a href="{{ route('lessons.create', [
                                        'instructor_id' => $instructor->id,
                                        'scheduled_at' => $slot->format('Y-m-d\TH:i'),
                                        'duration_minutes' => $duration
                                    ]) }}" 
                                       class="block p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg text-center transition-colors duration-200">
                                        <div class="text-lg font-semibold text-green-800">{{ $slot->format('H:i') }}</div>
                                        <div class="text-sm text-green-600">{{ $slot->format('M d') }}</div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No available slots found for this instructor and date</p>
                                <p class="text-sm text-gray-400 mt-2">Try selecting a different date or instructor</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select an Instructor</h3>
                        <p class="text-gray-600">Please select an instructor to view available time slots.</p>
                    </div>
                @endif

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">How to Use Available Slots</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Select an instructor and date to view available time slots</li>
                        <li>• Green slots indicate available times for the selected duration</li>
                        <li>• Click on a time slot to schedule a lesson at that time</li>
                        <li>• Slots are automatically filtered based on existing lessons and instructor availability</li>
                    </ul>
                </div>
            </div>

            <!-- Right Column - Summary & Actions -->
            <div class="space-y-6">
                <!-- Search Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Summary</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Instructor</span>
                            <span class="font-medium text-gray-900">{{ $instructor->user->name ?? 'All Instructors' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Date</span>
                            <span class="font-medium text-gray-900">{{ $date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium text-gray-900">{{ $duration }} minutes</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Available Slots</span>
                            <span class="font-medium text-green-600">{{ $slots->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('lessons.create') }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-plus mr-2"></i>
                            Schedule Lesson
                        </a>
                        <a href="{{ route('lessons.byDate', ['date' => $date->format('Y-m-d')]) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-calendar-day mr-2"></i>
                            View Day Schedule
                        </a>
                        <a href="{{ route('lessons.index') }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-list mr-2"></i>
                            All Lessons
                        </a>
                    </div>
                </div>

                <!-- Instructor Info -->
                @if($instructor)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor Info</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $instructor->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $instructor->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-gray-400"></i>
                                <span class="text-gray-900">{{ $instructor->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-id-card text-gray-400"></i>
                                <span class="text-gray-900">{{ $instructor->license_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tips -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-2">Tips</h3>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• Book slots in advance to secure your preferred time</li>
                        <li>• Consider instructor availability when scheduling</li>
                        <li>• Check for conflicts with existing lessons</li>
                        <li>• Contact instructors directly for special requests</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
