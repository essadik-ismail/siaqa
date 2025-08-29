@extends('layouts.app')

@section('title', 'Client Search Results')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Client Search Results</h1>
            <p class="text-gray-600">Search results for "{{ $query }}"</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Clients
            </a>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add New Client
            </a>
        </div>
    </div>

    <!-- Search Summary -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-search text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-900">Search Results</h3>
                <p class="text-blue-700">
                    Found <span class="font-bold">{{ $clients->total() }}</span> client(s) matching "{{ $query }}"
                </p>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    @if($clients->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Clients ({{ $clients->total() }})</h3>
                    <div class="text-sm text-gray-600">
                        Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} results
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                            @if($client->image)
                                                <img src="{{ Storage::url($client->image) }}" alt="{{ $client->nom }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-user text-gray-600"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $client->nom }} {{ $client->prenom }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ $client->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $client->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->telephone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $client->numero_permis }}</div>
                                    <div class="text-sm text-gray-500">
                                        Exp: {{ $client->date_expiration_permis ? $client->date_expiration_permis->format('M Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($client->is_blacklisted)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>Blacklisted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('clients.show', $client) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('clients.toggle-blacklist', $client) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900 p-1 rounded" 
                                                    title="{{ $client->is_blacklisted ? 'Remove from blacklist' : 'Add to blacklist' }}"
                                                    onclick="return confirm('Are you sure you want to {{ $client->is_blacklisted ? 'remove' : 'add' }} this client to the blacklist?')">
                                                <i class="fas fa-{{ $client->is_blacklisted ? 'user-check' : 'ban' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this client? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-center">
                        {{ $clients->appends(['q' => $query])->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- No Results -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-gray-500">
                <i class="fas fa-search text-6xl mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">No clients found</h3>
                <p class="text-lg text-gray-600 mb-6">No clients match your search criteria "{{ $query }}"</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="history.back()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Go Back
                    </button>
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-users mr-2"></i>View All Clients
                    </a>
                    <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Client
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Search Tips -->
    <div class="bg-gray-50 rounded-xl p-6 mt-8">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Search Tips</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <h5 class="font-medium text-gray-800 mb-2">What you can search for:</h5>
                <ul class="space-y-1">
                    <li>• Client first or last name</li>
                    <li>• Email address</li>
                    <li>• Phone number</li>
                    <li>• Driver's license number</li>
                    <li>• ID document number</li>
                </ul>
            </div>
            <div>
                <h5 class="font-medium text-gray-800 mb-2">Search suggestions:</h5>
                <ul class="space-y-1">
                    <li>• Use partial names (e.g., "john" for "Johnny")</li>
                    <li>• Search by domain part of email</li>
                    <li>• Try different spellings</li>
                    <li>• Use phone number without spaces</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom pagination styling to match Tailwind */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0 2px;
}

.pagination .page-link {
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #3b82f6;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    text-decoration: none;
}

.pagination .page-link:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.pagination .active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.pagination .disabled .page-link {
    color: #9ca3af;
    pointer-events: none;
    background-color: #fff;
    border-color: #d1d5db;
}
</style>
@endpush
