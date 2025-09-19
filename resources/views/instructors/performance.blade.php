@extends('layouts.app')

@section('title', 'Instructor Performance - ' . ($instructor->user->name ?? 'Unknown'))

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
                    <h1 class="text-3xl font-bold text-gray-900">Instructor Performance</h1>
                    <p class="mt-2 text-gray-600">{{ $instructor->user->name ?? 'Unknown' }}'s performance statistics and achievements</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Performance Overview -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overall Performance -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Overall Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_lessons'] }}</div>
                            <div class="text-sm text-gray-600">Total Lessons</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ $stats['completed_lessons'] }}</div>
                            <div class="text-sm text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-3xl font-bold text-yellow-600">{{ $stats['average_rating'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Average Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Lessons Performance -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Lessons Performance</h3>
                    <div class="space-y-4">
                        <!-- Completion Rate -->
                        <div>
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Completion Rate</span>
                                <span>{{ $stats['total_lessons'] > 0 ? round(($stats['completed_lessons'] / $stats['total_lessons']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ $stats['total_lessons'] > 0 ? ($stats['completed_lessons'] / $stats['total_lessons']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Lessons Breakdown -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-gray-900">{{ $stats['total_lessons'] }}</div>
                                <div class="text-xs text-gray-600">Total</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">{{ $stats['completed_lessons'] }}</div>
                                <div class="text-xs text-gray-600">Completed</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-xl font-bold text-yellow-600">{{ $stats['cancelled_lessons'] }}</div>
                                <div class="text-xs text-gray-600">Cancelled</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">{{ $stats['current_students'] }}</div>
                                <div class="text-xs text-gray-600">Current Students</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exams Performance -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Exams Performance</h3>
                    <div class="space-y-4">
                        <!-- Pass Rate -->
                        <div>
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Pass Rate</span>
                                <span>{{ $stats['total_exams'] > 0 ? round(($stats['passed_exams'] / $stats['total_exams']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ $stats['total_exams'] > 0 ? ($stats['passed_exams'] / $stats['total_exams']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Exams Breakdown -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-gray-900">{{ $stats['total_exams'] }}</div>
                                <div class="text-xs text-gray-600">Total</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">{{ $stats['passed_exams'] }}</div>
                                <div class="text-xs text-gray-600">Passed</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <div class="text-xl font-bold text-red-600">{{ $stats['failed_exams'] }}</div>
                                <div class="text-xs text-gray-600">Failed</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">{{ $stats['total_hours_taught'] ?? 0 }}</div>
                                <div class="text-xs text-gray-600">Hours Taught</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
                    <div class="space-y-3">
                        @forelse($instructor->lessons->take(5) as $lesson)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $lesson->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $lesson->student->name ?? 'No Student' }} • {{ $lesson->scheduled_at->format('M d, Y') }}</p>
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
                            <p class="text-gray-500 text-sm">No recent activity</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Performance Score -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Score</h3>
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4">
                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                <circle cx="60" cy="60" r="50" stroke="#10b981" stroke-width="8" fill="none" 
                                        stroke-dasharray="314" stroke-dashoffset="{{ 314 - (314 * ($stats['average_rating'] ?? 0) / 5) }}" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] ?? 0 }}/5</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Overall Rating</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $stats['total_lessons'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed Lessons</span>
                            <span class="font-semibold text-gray-900">{{ $stats['completed_lessons'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Exams</span>
                            <span class="font-semibold text-gray-900">{{ $stats['total_exams'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Passed Exams</span>
                            <span class="font-semibold text-gray-900">{{ $stats['passed_exams'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Hours Taught</span>
                            <span class="font-semibold text-gray-900">{{ $stats['total_hours_taught'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Current Students</span>
                            <span class="font-semibold text-gray-900">{{ $stats['current_students'] }}</span>
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
                        <a href="{{ route('instructors.schedule', $instructor) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-calendar mr-2"></i>
                            View Schedule
                        </a>
                    </div>
                </div>

                <!-- Performance Trends -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Trends</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">This Month</span>
                            <span class="font-semibold text-green-600">+12%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Last Month</span>
                            <span class="font-semibold text-gray-900">85%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Best Month</span>
                            <span class="font-semibold text-blue-600">92%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
