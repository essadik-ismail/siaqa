@extends('layouts.app')

@section('title', 'Packages')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Packages</h1>
                    <p class="mt-2 text-gray-600">Manage your driving school packages</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('packages.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create Package
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
                           placeholder="Search packages..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Category</label>
                    <select name="license_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Categories</option>
                        <option value="A" {{ request('license_category') == 'A' ? 'selected' : '' }}>Category A</option>
                        <option value="B" {{ request('license_category') == 'B' ? 'selected' : '' }}>Category B</option>
                        <option value="C" {{ request('license_category') == 'C' ? 'selected' : '' }}>Category C</option>
                        <option value="D" {{ request('license_category') == 'D' ? 'selected' : '' }}>Category D</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Package Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $package->name }}</h3>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' 
                            }}">
                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ $package->description }}</p>
                        <div class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($package->price, 2) }} DH</div>
                        <div class="text-sm text-gray-500">{{ $package->license_category }} License</div>
                    </div>

                    <!-- Package Details -->
                    <div class="px-6 pb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Theory Hours:</span>
                                <p class="font-medium">{{ $package->theory_hours ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Practical Hours:</span>
                                <p class="font-medium">{{ $package->practical_hours ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Exams:</span>
                                <p class="font-medium">{{ $package->exams_included ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Validity:</span>
                                <p class="font-medium">{{ $package->validity_days ?? 0 }} days</p>
                            </div>
                        </div>
                    </div>

                    <!-- Package Features -->
                    @if($package->features)
                        <div class="px-6 pb-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach(json_decode($package->features, true) as $feature)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('packages.show', $package) }}" 
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-eye mr-1"></i>
                                View
                            </a>
                            <a href="{{ route('packages.edit', $package) }}" 
                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('packages.destroy', $package) }}" 
                                  class="flex-1" 
                                  onsubmit="return confirm('Are you sure you want to delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No packages found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first package.</p>
                        <a href="{{ route('packages.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Package
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
            <div class="mt-8">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection