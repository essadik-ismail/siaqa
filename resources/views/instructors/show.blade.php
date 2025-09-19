@extends('layouts.app')

@section('title', 'Instructor Details - ' . ($instructor->user->name ?? 'Unknown'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('instructors.index') }}" 
                       class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $instructor->user->name ?? 'Unknown' }}</h1>
                        <p class="mt-2 text-gray-600">{{ $instructor->user->email ?? 'N/A' }} • {{ $instructor->license_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('instructors.edit', $instructor) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Instructor
                    </a>
                    <a href="{{ route('instructors.performance', $instructor) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-chart-line mr-2"></i>
                        View Performance
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Instructor Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $instructor->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $instructor->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $instructor->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">License Number</label>
                            <p class="text-gray-900">{{ $instructor->license_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Years of Experience</label>
                            <p class="text-gray-900">{{ $instructor->years_experience ?? 0 }} years</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                $instructor->status == 'active' ? 'bg-green-100 text-green-800' : 
                                ($instructor->status == 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                'bg-red-100 text-red-800') 
                            }}">
                                {{ ucfirst($instructor->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- License Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">License Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">License Categories</label>
                            <div class="flex flex-wrap gap-2">
                                @if($instructor->license_categories)
                                    @foreach(json_decode($instructor->license_categories, true) as $category)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">No categories assigned</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">License Expiry</label>
                            <p class="text-gray-900">{{ $instructor->license_expiry_date ? $instructor->license_expiry_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Specializations</label>
                            <p class="text-gray-900">{{ $instructor->specializations ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Certifications</label>
                            <p class="text-gray-900">{{ $instructor->certifications ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $instructor->lessons_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Total Lessons</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $instructor->completed_lessons_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $instructor->average_rating ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Average Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Lessons -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Lessons</h3>
                        <a href="{{ route('lessons.index', ['instructor_id' => $instructor->id]) }}" 
                           class="text-green-600 hover:text-green-700 text-sm font-medium">
                            View All
                        </a>
                    </div>
                    <div class="space-y-3">
                        @forelse($instructor->lessons->take(5) as $lesson)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $lesson->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $lesson->student->name ?? 'No Student' }} • {{ $lesson->scheduled_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                    $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                    ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800') 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent lessons found</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $instructor->lessons->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $instructor->lessons->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Exams</span>
                            <span class="font-semibold text-gray-900">{{ $instructor->exams->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Current Students</span>
                            <span class="font-semibold text-gray-900">{{ $instructor->lessons->where('status', 'scheduled')->distinct('student_id')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Availability Status -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability Status</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 {{ $instructor->is_available ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-{{ $instructor->is_available ? 'check' : 'times' }} text-2xl {{ $instructor->is_available ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <p class="text-lg font-semibold {{ $instructor->is_available ? 'text-green-600' : 'text-red-600' }}">
                            {{ $instructor->is_available ? 'Available' : 'Busy' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Currently accepting new lessons</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('instructors.schedule', $instructor) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-calendar mr-2"></i>
                            View Schedule
                        </a>
                        <a href="{{ route('instructors.performance', $instructor) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-chart-line mr-2"></i>
                            View Performance
                        </a>
                        <form method="POST" action="{{ route('instructors.toggleAvailability', $instructor) }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full {{ $instructor->is_available ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-{{ $instructor->is_available ? 'pause' : 'play' }} mr-2"></i>
                                {{ $instructor->is_available ? 'Mark as Busy' : 'Mark as Available' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <div class="space-y-3">
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
            </div>
        </div>
    </div>
</div>
@endsection