@extends('layouts.app')

@section('title', 'Add New Agency')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Agency</h1>
            <p class="text-gray-600">Create a new rental agency with complete business information</p>
        </div>
        <a href="{{ route('admin.agencies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Agencies
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Agency Information</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.agencies.store') }}" enctype="multipart/form-data" id="agencyForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom_agence" class="block text-sm font-medium text-gray-700 mb-2">Agency Name *</label>
                                <input type="text" id="nom_agence" name="nom_agence" value="{{ old('nom_agence') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nom_agence') border-red-500 @enderror"
                                       placeholder="Enter agency name">
                                @error('nom_agence')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant *</label>
                                <select id="tenant_id" name="tenant_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                                    <option value="">Select a tenant</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('adresse') border-red-500 @enderror"
                                       placeholder="Enter street address">
                                @error('adresse')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" id="ville" name="ville" value="{{ old('ville') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ville') border-red-500 @enderror"
                                       placeholder="Enter city name">
                                @error('ville')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="rc" class="block text-sm font-medium text-gray-700 mb-2">RC Number</label>
                                <input type="text" id="rc" name="rc" value="{{ old('rc') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rc') border-red-500 @enderror"
                                       placeholder="Enter RC number">
                                @error('rc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="patente" class="block text-sm font-medium text-gray-700 mb-2">Patente</label>
                                <input type="text" id="patente" name="patente" value="{{ old('patente') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('patente') border-red-500 @enderror"
                                       placeholder="Enter patente number">
                                @error('patente')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="IF" class="block text-sm font-medium text-gray-700 mb-2">IF Number</label>
                                <input type="text" id="IF" name="IF" value="{{ old('IF') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('IF') border-red-500 @enderror"
                                       placeholder="Enter IF number">
                                @error('IF')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="n_cnss" class="block text-sm font-medium text-gray-700 mb-2">CNSS Number</label>
                                <input type="text" id="n_cnss" name="n_cnss" value="{{ old('n_cnss') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('n_cnss') border-red-500 @enderror"
                                       placeholder="Enter CNSS number">
                                @error('n_cnss')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="ICE" class="block text-sm font-medium text-gray-700 mb-2">ICE Number</label>
                                <input type="text" id="ICE" name="ICE" value="{{ old('ICE') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ICE') border-red-500 @enderror"
                                       placeholder="Enter ICE number">
                                @error('ICE')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="n_compte_bancaire" class="block text-sm font-medium text-gray-700 mb-2">Bank Account</label>
                                <input type="text" id="n_compte_bancaire" name="n_compte_bancaire" value="{{ old('n_compte_bancaire') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('n_compte_bancaire') border-red-500 @enderror"
                                       placeholder="Enter bank account number">
                                @error('n_compte_bancaire')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-20 w-20 rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300" id="logo-preview">
                                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="logo" name="logo" accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('logo') border-red-500 @enderror"
                                           onchange="previewLogo(this)">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    @error('logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.agencies.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Create Agency
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Help & Guidelines</h4>
                
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Required Fields</h5>
                        <p class="text-sm text-gray-600">Agency name, tenant, address, and city are required fields</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Legal Numbers</h5>
                        <p class="text-sm text-gray-600">RC, Patente, IF, CNSS, and ICE are optional legal identification numbers</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Logo Requirements</h5>
                        <p class="text-sm text-gray-600">Upload a logo in PNG, JPG, or GIF format, maximum 2MB</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Tenant Assignment</h5>
                        <p class="text-sm text-gray-600">Required - determines which tenant the agency belongs to</p>
                    </div>
                </div>

                @if($recentAgencies->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium text-gray-800 mb-3">Recently Created Agencies</h5>
                    <div class="space-y-2">
                        @foreach($recentAgencies as $agency)
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ $agency->nom_agence }}</span>
                            <span class="text-gray-500 ml-2">{{ $agency->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    const file = input.files[0];
    
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPG, GIF)');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="h-full w-full object-cover rounded-lg">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<i class="fas fa-building text-gray-400 text-2xl"></i>';
    }
}

// Form validation
document.getElementById('agencyForm').addEventListener('submit', function(e) {
    const logo = document.getElementById('logo').files[0];
    
    if (logo && logo.size > 2 * 1024 * 1024) {
        e.preventDefault();
        alert('Logo file size must be less than 2MB');
        return false;
    }
    
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (logo && !validTypes.includes(logo.type)) {
        e.preventDefault();
        alert('Please select a valid image file (PNG, JPG, GIF)');
        return false;
    }
});
</script>
@endpush
