@extends('layouts.app')

@section('title', 'Edit Agency: ' . $agency->nom_agence)

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.agencies.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Agency</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.agencies.update', $agency) }}" enctype="multipart/form-data" id="agencyForm">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom_agence" class="block text-sm font-medium text-gray-700 mb-2">Agency Name *</label>
                            <input type="text" name="nom_agence" id="nom_agence" value="{{ old('nom_agence', $agency->nom_agence) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom_agence')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant *</label>
                            <select name="tenant_id" id="tenant_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id', $agency->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $agency->adresse) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $agency->ville) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Legal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rc" class="block text-sm font-medium text-gray-700 mb-2">RC Number</label>
                            <input type="text" name="rc" id="rc" value="{{ old('rc', $agency->rc) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('rc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="patente" class="block text-sm font-medium text-gray-700 mb-2">Patente</label>
                            <input type="text" name="patente" id="patente" value="{{ old('patente', $agency->patente) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('patente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tax Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="IF" class="block text-sm font-medium text-gray-700 mb-2">IF Number</label>
                            <input type="text" name="IF" id="IF" value="{{ old('IF', $agency->IF) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('IF')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="n_cnss" class="block text-sm font-medium text-gray-700 mb-2">CNSS Number</label>
                            <input type="text" name="n_cnss" id="n_cnss" value="{{ old('n_cnss', $agency->n_cnss) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('n_cnss')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ICE" class="block text-sm font-medium text-gray-700 mb-2">ICE Number</label>
                            <input type="text" name="ICE" id="ICE" value="{{ old('ICE', $agency->ICE) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ICE')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="n_compte_bancaire" class="block text-sm font-medium text-gray-700 mb-2">Bank Account</label>
                            <input type="text" name="n_compte_bancaire" id="n_compte_bancaire" value="{{ old('n_compte_bancaire', $agency->n_compte_bancaire) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('n_compte_bancaire')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center space-x-4">
                            @if($agency->logo)
                                <div class="flex-shrink-0">
                                    <img src="{{ Storage::url($agency->logo) }}" alt="Current logo" class="h-20 w-20 rounded-lg object-cover border-2 border-gray-300" id="current-logo">
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <div class="h-20 w-20 rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300" id="logo-preview">
                                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" id="logo" name="logo" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       onchange="previewLogo(this)">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Leave empty to keep current logo.</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $agency->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.agencies.show', $agency) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Agency
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo-preview');
            if (preview) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="h-20 w-20 rounded-lg object-cover">';
            }
            
            // Hide current logo if exists
            const currentLogo = document.getElementById('current-logo');
            if (currentLogo) {
                currentLogo.style.display = 'none';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
