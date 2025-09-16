@extends('layouts.app')

@section('title', 'Agency Statistics: ' . $agency->nom_agence)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <div class="flex items-center mb-2">
                <a href="{{ route('admin.agencies.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Agency Statistics</h1>
            </div>
            <p class="text-gray-600">{{ $agency->nom_agence }} - Performance metrics and analytics</p>
        </div>
    </div>

    <!-- Agency Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Agency Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agency Name</label>
                    <p class="text-gray-900 font-medium">{{ $agency->nom_agence }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <p class="text-gray-900">{{ $agency->ville }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $agency->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $agency->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                    <p class="text-gray-900">{{ $agency->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-car text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Vehicles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_vehicles'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Reservations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Reservations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reservations'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.agencies.users', $agency) }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-900">View Users</p>
                        <p class="text-xs text-blue-700">{{ $stats['total_users'] }} users</p>
                    </div>
                </a>

                <a href="{{ route('vehicules.index') }}?agency_id={{ $agency->id }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-car text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-900">View Vehicles</p>
                        <p class="text-xs text-purple-700">{{ $stats['total_vehicles'] }} vehicles</p>
                    </div>
                </a>

                <a href="{{ route('reservations.index') }}?agency_id={{ $agency->id }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-orange-900">View Reservations</p>
                        <p class="text-xs text-orange-700">{{ $stats['total_reservations'] }} reservations</p>
                    </div>
                </a>

                <a href="{{ route('admin.agencies.edit', $agency) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Edit Agency</p>
                        <p class="text-xs text-gray-700">Update information</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


