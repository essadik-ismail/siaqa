@extends('layouts.app')

@section('title', 'Agency Details: ' . $agency->nom_agence)

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.agencies.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Agency Details</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Agency Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $agency->nom_agence }}</h2>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $agency->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $agency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($agency->tenant)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $agency->tenant->company_name ?? 'Tenant' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.agencies.edit', $agency) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.agencies.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>

            <!-- Agency Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agency Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->nom_agence }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->adresse ?? 'Not specified' }}</p>
                    </div>

                    @if($agency->ville)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->ville }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $agency->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $agency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Business Information</h3>
                    
                    @if($agency->rc)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RC (Registration Number)</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->rc }}</p>
                    </div>
                    @endif

                    @if($agency->patente)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patente</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->patente }}</p>
                    </div>
                    @endif

                    @if($agency->IF)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IF (Tax Number)</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->IF }}</p>
                    </div>
                    @endif

                    @if($agency->n_cnss)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CNSS Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->n_cnss }}</p>
                    </div>
                    @endif

                    @if($agency->ICE)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ICE</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->ICE }}</p>
                    </div>
                    @endif

                    @if($agency->n_compte_bancaire)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bank Account Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $agency->n_compte_bancaire }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Associated Users -->
            @if($agency->users && $agency->users->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Associated Users ({{ $agency->users->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($agency->users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="mt-8">
                <div class="text-center py-8">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Associated</h3>
                    <p class="text-gray-500">This agency doesn't have any associated users yet.</p>
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                    <div>
                        <span class="font-medium">Created:</span> {{ $agency->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> {{ $agency->updated_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


