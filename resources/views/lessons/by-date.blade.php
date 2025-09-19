@extends('layouts.app')

@section('title', 'Lessons by Date')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Lessons by Date</h1>
                    <p class="mt-2 text-gray-600">View lessons for {{ $date->format('M d, Y') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('lessons.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Schedule Lesson
                    </a>
                </div>
            </div>
        </div>

        <!-- Date Navigation -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('lessons.byDate', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" 
                       class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </a>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $date->format('l, M d, Y') }}</h2>
                    <a href="{{ route('lessons.byDate', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" 
                       class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-chevron-right text-xl"></i>
                    </a>
                </div>
                <div>
                    <a href="{{ route('lessons.byDate', ['date' => now()->format('Y-m-d')]) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-calendar-day mr-2"></i>
                        Today
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Lessons List -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Morning Lessons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-sun text-yellow-500 mr-2"></i>
                            Morning Lessons (6:00 AM - 12:00 PM)
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $morningLessons = $lessons->filter(function($lesson) {
                                return $lesson->scheduled_at->hour >= 6 && $lesson->scheduled_at->hour < 12;
                            });
                        @endphp
                        @forelse($morningLessons as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                            <span><i class="fas fa-user mr-1"></i>{{ $lesson->student->name ?? 'No Student' }}</span>
                                            <span><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $lesson->instructor->user->name ?? 'No Instructor' }}</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $lesson->scheduled_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($lesson->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-red-100 text-red-800')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                    </span>
                                    <a href="{{ route('lessons.show', $lesson) }}" 
                                       class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No morning lessons scheduled</p>
                        @endforelse
                    </div>
                </div>

                <!-- Afternoon Lessons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-sun text-orange-500 mr-2"></i>
                            Afternoon Lessons (12:00 PM - 6:00 PM)
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $afternoonLessons = $lessons->filter(function($lesson) {
                                return $lesson->scheduled_at->hour >= 12 && $lesson->scheduled_at->hour < 18;
                            });
                        @endphp
                        @forelse($afternoonLessons as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                            <span><i class="fas fa-user mr-1"></i>{{ $lesson->student->name ?? 'No Student' }}</span>
                                            <span><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $lesson->instructor->user->name ?? 'No Instructor' }}</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $lesson->scheduled_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($lesson->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-red-100 text-red-800')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                    </span>
                                    <a href="{{ route('lessons.show', $lesson) }}" 
                                       class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No afternoon lessons scheduled</p>
                        @endforelse
                    </div>
                </div>

                <!-- Evening Lessons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-moon text-indigo-500 mr-2"></i>
                            Evening Lessons (6:00 PM - 10:00 PM)
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $eveningLessons = $lessons->filter(function($lesson) {
                                return $lesson->scheduled_at->hour >= 18 && $lesson->scheduled_at->hour < 22;
                            });
                        @endphp
                        @forelse($eveningLessons as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                            <span><i class="fas fa-user mr-1"></i>{{ $lesson->student->name ?? 'No Student' }}</span>
                                            <span><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $lesson->instructor->user->name ?? 'No Instructor' }}</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $lesson->scheduled_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($lesson->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-red-100 text-red-800')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                    </span>
                                    <a href="{{ route('lessons.show', $lesson) }}" 
                                       class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No evening lessons scheduled</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Daily Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Summary</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $lessons->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-semibold text-green-600">{{ $lessons->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Scheduled</span>
                            <span class="font-semibold text-blue-600">{{ $lessons->where('status', 'scheduled')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">In Progress</span>
                            <span class="font-semibold text-yellow-600">{{ $lessons->where('status', 'in_progress')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Cancelled</span>
                            <span class="font-semibold text-red-600">{{ $lessons->where('status', 'cancelled')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Time Slots -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Time Slots</h3>
                    <div class="space-y-2">
                        @for($hour = 6; $hour < 22; $hour++)
                            @php
                                $slotLessons = $lessons->filter(function($lesson) use ($hour) {
                                    return $lesson->scheduled_at->hour == $hour;
                                });
                            @endphp
                            <div class="flex items-center justify-between p-2 {{ $slotLessons->count() > 0 ? 'bg-green-50' : 'bg-gray-50' }} rounded">
                                <span class="text-sm font-medium">{{ sprintf('%02d:00', $hour) }}</span>
                                <span class="text-xs {{ $slotLessons->count() > 0 ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $slotLessons->count() }} lesson{{ $slotLessons->count() !== 1 ? 's' : '' }}
                                </span>
                            </div>
                        @endfor
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
                        <a href="{{ route('lessons.availableSlots') }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-calendar-check mr-2"></i>
                            View Available Slots
                        </a>
                        <a href="{{ route('lessons.index') }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-list mr-2"></i>
                            All Lessons
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
