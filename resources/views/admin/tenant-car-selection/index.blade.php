@extends('layouts.app')

@section('title', 'Tenant Car Selection Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tenant Car Selection Management</h1>
            <p class="text-gray-600">Manage which cars each tenant displays on their landing page</p>
        </div>
    </div>

    <!-- Tenants Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tenants as $tenant)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tenant->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $tenant->domain }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Agencies:</span>
                        <span class="font-medium text-gray-900">{{ $tenant->agences->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Users:</span>
                        <span class="font-medium text-gray-900">{{ $tenant->users->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subscription:</span>
                        <span class="font-medium text-gray-900 capitalize">{{ $tenant->subscription_plan ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.car-selection.show', $tenant) }}" 
                       class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center text-sm font-medium">
                        <i class="fas fa-car mr-2"></i>
                        Manage Cars
                    </a>
                    <a href="{{ route('admin.agencies.index') }}?tenant={{ $tenant->id }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                        <i class="fas fa-building"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-server text-4xl mb-4"></i>
                    <p class="text-lg">No tenants found</p>
                    <p class="text-sm">Create a tenant to start managing car selections</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
