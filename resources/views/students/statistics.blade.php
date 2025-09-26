@extends('layouts.app')

@section('title', 'Student Statistics')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Student Statistics</h1>
            <p class="text-gray-600 text-lg">Overview of student data and analytics</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="material-card p-6">
                <div class="flex items-center">
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Students -->
            <div class="material-card p-6">
                <div class="flex items-center">
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-user-check text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Active Students</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Blacklisted Students -->
            <div class="material-card p-6">
                <div class="flex items-center">
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <i class="fas fa-user-times text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Blacklisted</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['blacklisted'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Registrations -->
            <div class="material-card p-6">
                <div class="flex items-center">
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Recent (30 days)</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['recent_registrations'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students by Status Chart -->
        @if(!empty($stats['by_status']))
        <div class="material-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Students by Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($stats['by_status'] as $status => $count)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                        <span class="text-lg font-bold text-gray-900">{{ $count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('students.index') }}" 
                class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                Back to Students
            </a>
        </div>
    </div>
</div>
@endsection
