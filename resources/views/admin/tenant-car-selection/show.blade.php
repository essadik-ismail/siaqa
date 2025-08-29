@extends('layouts.app')

@section('title', 'Manage Landing Cars - ' . $tenant->name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Landing Cars</h1>
            <p class="text-gray-600">{{ $tenant->name }} - {{ $tenant->domain }}</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.car-selection.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Tenants
            </a>
        </div>
    </div>

    <!-- Tenant Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $vehicles->count() }}</div>
                    <div class="text-sm text-gray-600">Total Vehicles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $vehicles->where('landing_display', true)->count() }}</div>
                    <div class="text-sm text-gray-600">On Landing Page</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $vehicles->where('landing_display', false)->count() }}</div>
                    <div class="text-sm text-gray-600">Hidden</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
            <div class="flex flex-wrap gap-3">
                <button id="select-all" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check-square mr-2"></i>Select All
                </button>
                <button id="deselect-all" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-square mr-2"></i>Deselect All
                </button>
                <button id="show-selected" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i>Show Selected
                </button>
                <button id="hide-selected" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-eye-slash mr-2"></i>Hide Selected
                </button>
                <button id="reset-order" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-sort mr-2"></i>Reset Order
                </button>
            </div>
        </div>
    </div>

    <!-- Vehicles Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicles</h3>
            
            <form id="vehicles-form" method="POST" action="{{ route('admin.car-selection.update', $tenant) }}">
                @csrf
                <div id="vehicles-container" class="space-y-4">
                    @foreach($vehicles as $vehicle)
                    <div class="vehicle-item border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" data-id="{{ $vehicle->id }}">
                        <div class="flex items-center space-x-4">
                            <!-- Drag Handle -->
                            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical text-lg"></i>
                            </div>
                            
                            <!-- Checkbox -->
                            <input type="checkbox" name="vehicles[{{ $vehicle->id }}][id]" value="{{ $vehicle->id }}" 
                                   class="vehicle-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            
                            <!-- Vehicle Image -->
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                @if($vehicle->image)
                                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fas fa-car text-gray-400 text-xl"></i>
                                @endif
                            </div>
                            
                            <!-- Vehicle Info -->
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $vehicle->name }}</h4>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="fas fa-tag mr-1"></i>{{ $vehicle->marque->marque ?? 'N/A' }}</span>
                                    <span><i class="fas fa-building mr-1"></i>{{ $vehicle->agence->nom_agence ?? 'No Agency' }}</span>
                                    <span><i class="fas fa-dollar-sign mr-1"></i>${{ number_format($vehicle->prix_jour) }}/day</span>
                                </div>
                            </div>
                            
                            <!-- Landing Display Toggle -->
                            <div class="flex items-center space-x-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="vehicles[{{ $vehicle->id }}][landing_display]" value="1" 
                                           {{ $vehicle->landing_display ? 'checked' : '' }}
                                           class="landing-display-toggle w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Show on Landing</span>
                                </label>
                                
                                <!-- Order Input -->
                                <div class="flex items-center space-x-2">
                                    <label class="text-sm text-gray-600">Order:</label>
                                    <input type="number" name="vehicles[{{ $vehicle->id }}][landing_order]" 
                                           value="{{ $vehicle->landing_order }}" min="0"
                                           class="order-input w-16 px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop
    const container = document.getElementById('vehicles-container');
    new Sortable(container, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            updateOrderNumbers();
        }
    });

    // Update order numbers after drag and drop
    function updateOrderNumbers() {
        const items = container.querySelectorAll('.vehicle-item');
        items.forEach((item, index) => {
            const orderInput = item.querySelector('.order-input');
            if (orderInput) {
                orderInput.value = index + 1;
            }
        });
    }

    // Select all vehicles
    document.getElementById('select-all').addEventListener('click', function() {
        document.querySelectorAll('.vehicle-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Deselect all vehicles
    document.getElementById('deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.vehicle-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Show selected vehicles
    document.getElementById('show-selected').addEventListener('click', function() {
        const selectedVehicles = getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            alert('Please select vehicles first');
            return;
        }
        
        selectedVehicles.forEach(vehicle => {
            const toggle = vehicle.querySelector('.landing-display-toggle');
            toggle.checked = true;
        });
    });

    // Hide selected vehicles
    document.getElementById('hide-selected').addEventListener('click', function() {
        const selectedVehicles = getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            alert('Please select vehicles first');
            return;
        }
        
        selectedVehicles.forEach(vehicle => {
            const toggle = vehicle.querySelector('.landing-display-toggle');
            toggle.checked = false;
        });
    });

    // Reset order
    document.getElementById('reset-order').addEventListener('click', function() {
        const items = container.querySelectorAll('.vehicle-item');
        items.forEach((item, index) => {
            const orderInput = item.querySelector('.order-input');
            if (orderInput) {
                orderInput.value = index + 1;
            }
        });
    });

    // Get selected vehicles
    function getSelectedVehicles() {
        const selectedCheckboxes = document.querySelectorAll('.vehicle-checkbox:checked');
        return Array.from(selectedCheckboxes).map(checkbox => {
            return checkbox.closest('.vehicle-item');
        });
    }

    // Form submission
    document.getElementById('vehicles-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes');
        });
    });
});
</script>
@endpush
