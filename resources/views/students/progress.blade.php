@extends('layouts.app')

@section('title', 'Student Progress - ' . $student->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('students.show', $student) }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Student Progress</h1>
                    <p class="mt-2 text-gray-600">{{ $student->name }}'s learning progress and achievements</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Progress Overview -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Theory Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Theory Progress</h3>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600">{{ $progress['theory_hours']['completed'] }}</span>
                            <span class="text-gray-500">/ {{ $progress['theory_hours']['required'] }} hours</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Completion</span>
                            <span>{{ $progress['theory_hours']['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-4 rounded-full transition-all duration-500" 
                                 style="width: {{ $progress['theory_hours']['percentage'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $progress['theory_hours']['completed'] }}</div>
                            <div class="text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600">{{ $progress['theory_hours']['required'] - $progress['theory_hours']['completed'] }}</div>
                            <div class="text-gray-600">Remaining</div>
                        </div>
                    </div>
                </div>

                <!-- Practical Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Practical Progress</h3>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-green-600">{{ $progress['practical_hours']['completed'] }}</span>
                            <span class="text-gray-500">/ {{ $progress['practical_hours']['required'] }} hours</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Completion</span>
                            <span>{{ $progress['practical_hours']['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full transition-all duration-500" 
                                 style="width: {{ $progress['practical_hours']['percentage'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $progress['practical_hours']['completed'] }}</div>
                            <div class="text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600">{{ $progress['practical_hours']['required'] - $progress['practical_hours']['completed'] }}</div>
                            <div class="text-gray-600">Remaining</div>
                        </div>
                    </div>
                </div>

                <!-- Lessons Overview -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Lessons Overview</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $progress['lessons']['total'] }}</div>
                            <div class="text-sm text-gray-600">Total Lessons</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $progress['lessons']['completed'] }}</div>
                            <div class="text-sm text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $progress['lessons']['scheduled'] }}</div>
                            <div class="text-sm text-gray-600">Scheduled</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $progress['lessons']['cancelled'] }}</div>
                            <div class="text-sm text-gray-600">Cancelled</div>
                        </div>
                    </div>
                </div>

                <!-- Exams Overview -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Exams Overview</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $progress['exams']['total'] }}</div>
                            <div class="text-sm text-gray-600">Total Exams</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $progress['exams']['passed'] }}</div>
                            <div class="text-sm text-gray-600">Passed</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $progress['exams']['failed'] }}</div>
                            <div class="text-sm text-gray-600">Failed</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $progress['exams']['scheduled'] }}</div>
                            <div class="text-sm text-gray-600">Scheduled</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Overall Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Overall Progress</h3>
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4">
                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                <circle cx="60" cy="60" r="50" stroke="#10b981" stroke-width="8" fill="none" 
                                        stroke-dasharray="314" stroke-dashoffset="{{ 314 - (314 * ($progress['theory_hours']['percentage'] + $progress['practical_hours']['percentage']) / 200) }}" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-900">{{ round(($progress['theory_hours']['percentage'] + $progress['practical_hours']['percentage']) / 2) }}%</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Overall Completion</p>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Due</span>
                            <span class="font-semibold text-gray-900">{{ number_format($progress['payments']['total_due'], 2) }} DH</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Paid</span>
                            <span class="font-semibold text-green-600">{{ number_format($progress['payments']['total_paid'], 2) }} DH</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Balance</span>
                            <span class="font-semibold {{ $progress['payments']['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($progress['payments']['balance'], 2) }} DH
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('lessons.create', ['student_id' => $student->id]) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-plus mr-2"></i>
                            Schedule Lesson
                        </a>
                        <a href="{{ route('exams.create', ['student_id' => $student->id]) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Schedule Exam
                        </a>
                        <a href="{{ route('students.schedule', $student) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-calendar mr-2"></i>
                            View Schedule
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @forelse($student->lessons->take(3) as $lesson)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $lesson->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $lesson->scheduled_at->format('M d, Y') }}</p>
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
        </div>
    </div>
</div>
@endsection
