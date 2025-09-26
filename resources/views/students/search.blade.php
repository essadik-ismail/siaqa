@extends('layouts.app')

@section('title', 'Search Students')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Search Students</h1>
            <p class="text-gray-600 text-lg">Find students by name, email, or phone</p>
        </div>

        <!-- Search Form -->
        <div class="material-card p-8 mb-8">
            <form method="GET" action="{{ route('students.search') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="Search by name, email, or phone..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <button type="submit" 
                        class="material-button px-6 py-3">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </form>
        </div>

        <!-- Search Results -->
        @if($query)
        <div class="material-card p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Search Results for "{{ $query }}"
                </h2>
                <span class="text-sm text-gray-500">
                    {{ $students->total() }} result(s) found
                </span>
            </div>

            @if($students->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($students as $student)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $student->email }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            @if($student->phone)
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-2"></i>{{ $student->phone }}
                            </p>
                            @endif
                            
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-tag mr-2"></i>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ ucfirst($student->status ?? 'active') }}
                                </span>
                            </p>
                            
                            @if($student->is_blacklisted)
                            <p class="text-sm text-red-600">
                                <i class="fas fa-ban mr-2"></i>Blacklisted
                            </p>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('students.show', $student) }}" 
                               class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $students->appends(['q' => $query])->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No students found</h3>
                    <p class="text-gray-500">Try adjusting your search terms</p>
                </div>
            @endif
        </div>
        @else
        <div class="material-card p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-gray-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Search for students</h3>
            <p class="text-gray-500">Enter a name, email, or phone number to get started</p>
        </div>
        @endif
    </div>
</div>
@endsection
