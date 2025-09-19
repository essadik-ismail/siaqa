@extends('layouts.app')

@section('title', 'Instructor Schedule - ' . ($instructor->user->name ?? 'Unknown'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('instructors.show', $instructor) }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Instructor Schedule</h1>
                    <p class="mt-2 text-gray-600">{{ $instructor->user->name ?? 'Unknown' }}'s lessons and exams schedule</p>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" class="flex items-center space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Schedule -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Lessons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Lessons</h3>
                            <a href="{{ route('lessons.create', ['instructor_id' => $instructor->id]) }}" 
                               class="text-green-600 hover:text-green-700 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i>
                                Schedule Lesson
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse($lessons as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $lesson->scheduled_at->format('M d, Y H:i') }} • 
                                            {{ $lesson->duration_minutes }} minutes
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Student: {{ $lesson->student->name ?? 'N/A' }}
                                            @if($lesson->vehicle)
                                                • Vehicle: {{ $lesson->vehicle->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($lesson->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-gray-100 text-gray-800')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('lessons.show', $lesson) }}" 
                                           class="text-green-600 hover:text-green-700 text-sm font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No lessons scheduled for this period</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Exams -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Exams</h3>
                            <a href="{{ route('exams.create', ['instructor_id' => $instructor->id]) }}" 
                               class="text-green-600 hover:text-green-700 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i>
                                Schedule Exam
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse($exams as $exam)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-orange-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $exam->title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $exam->scheduled_at->format('M d, Y H:i') }} • 
                                            {{ $exam->duration_minutes }} minutes
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Student: {{ $exam->student->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $exam->status == 'passed' ? 'bg-green-100 text-green-800' : 
                                        ($exam->status == 'failed' ? 'bg-red-100 text-red-800' : 
                                        ($exam->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-gray-100 text-gray-800')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $exam->status)) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('exams.show', $exam) }}" 
                                           class="text-green-600 hover:text-green-700 text-sm font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No exams scheduled for this period</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Schedule Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule Summary</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $lessons->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed Lessons</span>
                            <span class="font-semibold text-green-600">{{ $lessons->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Scheduled Lessons</span>
                            <span class="font-semibold text-blue-600">{{ $lessons->where('status', 'scheduled')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Exams</span>
                            <span class="font-semibold text-gray-900">{{ $exams->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Passed Exams</span>
                            <span class="font-semibold text-green-600">{{ $exams->where('status', 'passed')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Instructor Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor Info</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user text-gray-400"></i>
                            <span class="text-gray-900">{{ $instructor->user->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                            <span class="text-gray-900">{{ $instructor->user->email ?? 'N/A' }}</span>
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

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('lessons.create', ['instructor_id' => $instructor->id]) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-plus mr-2"></i>
                            Schedule Lesson
                        </a>
                        <a href="{{ route('exams.create', ['instructor_id' => $instructor->id]) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Schedule Exam
                        </a>
                        <a href="{{ route('instructors.performance', $instructor) }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-chart-line mr-2"></i>
                            View Performance
                        </a>
                    </div>
                </div>

                <!-- Date Range Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Date Range</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">From</span>
                            <span class="font-medium">{{ $startDate->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">To</span>
                            <span class="font-medium">{{ $endDate->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium">{{ $startDate->diffInDays($endDate) + 1 }} days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
