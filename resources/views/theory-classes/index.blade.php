@extends('layouts.app')

@section('title', 'Theory Classes')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Theory Classes</h1>
                    <p class="mt-2 text-gray-600">Manage your driving school theory classes</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('theory-classes.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Schedule Class
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search classes..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Theory Classes List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">All Theory Classes</h3>
            </div>
            <div class="p-6">
                @forelse($theoryClasses as $class)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $class->title }}</h4>
                                <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                    <span><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $class->instructor->user->name ?? 'No Instructor' }}</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $class->scheduled_at->format('M d, Y H:i') }}</span>
                                    <span><i class="fas fa-clock mr-1"></i>{{ $class->duration_minutes }} min</span>
                                </div>
                                <div class="flex items-center space-x-4 text-xs text-gray-400 mt-1">
                                    <span><i class="fas fa-users mr-1"></i>{{ $class->students_count ?? 0 }} students</span>
                                    <span><i class="fas fa-tag mr-1"></i>{{ ucfirst($class->license_category) }}</span>
                                    @if($class->room)
                                        <span><i class="fas fa-door-open mr-1"></i>{{ $class->room }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                $class->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                ($class->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                ($class->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                'bg-red-100 text-red-800')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $class->status)) }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('theory-classes.show', $class) }}" 
                                   class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('theory-classes.edit', $class) }}" 
                                   class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chalkboard text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No theory classes found</h3>
                        <p class="text-gray-500 mb-6">Get started by scheduling your first theory class.</p>
                        <a href="{{ route('theory-classes.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Schedule Class
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($theoryClasses->hasPages())
            <div class="mt-8">
                {{ $theoryClasses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection