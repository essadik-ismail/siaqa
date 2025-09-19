@extends('layouts.app')

@section('title', 'Theory Class Details - ' . $class->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('theory-classes.index') }}" 
                       class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $class->title }}</h1>
                        <p class="mt-2 text-gray-600">{{ $class->scheduled_at->format('M d, Y H:i') }} • {{ $class->duration_minutes }} minutes</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('theory-classes.edit', $class) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Class
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Class Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
                            <p class="text-gray-900">{{ $class->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                $class->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                ($class->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                ($class->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                'bg-red-100 text-red-800')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $class->status)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Scheduled Date</label>
                            <p class="text-gray-900">{{ $class->scheduled_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Duration</label>
                            <p class="text-gray-900">{{ $class->duration_minutes }} minutes</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">License Category</label>
                            <p class="text-gray-900">{{ ucfirst($class->license_category) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Room</label>
                            <p class="text-gray-900">{{ $class->room ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Instructor Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor</h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $class->instructor->user->name ?? 'No Instructor' }}</h4>
                            <p class="text-sm text-gray-500">{{ $class->instructor->user->email ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">Phone: {{ $class->instructor->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Class Description -->
                @if($class->description)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                        <p class="text-gray-700">{{ $class->description }}</p>
                    </div>
                @endif

                <!-- Class Materials -->
                @if($class->materials)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Materials</h3>
                        <div class="space-y-2">
                            @foreach(json_decode($class->materials, true) as $material)
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-alt text-gray-400"></i>
                                    <span class="text-gray-700">{{ $material }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Class Statistics -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Students</span>
                            <span class="font-semibold text-gray-900">{{ $class->students_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-semibold text-gray-900">{{ $class->duration_minutes }} min</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">License Category</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($class->license_category) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Created</span>
                            <span class="font-semibold text-gray-900">{{ $class->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('theory-classes.edit', $class) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Class
                        </a>
                        @if($class->instructor)
                            <a href="{{ route('instructors.show', $class->instructor) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                View Instructor
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Class Details -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Details</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chalkboard text-gray-400"></i>
                            <span class="text-gray-900">{{ $class->title }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar text-gray-400"></i>
                            <span class="text-gray-900">{{ $class->scheduled_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-gray-400"></i>
                            <span class="text-gray-900">{{ $class->scheduled_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-door-open text-gray-400"></i>
                            <span class="text-gray-900">{{ $class->room ?? 'No room assigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
