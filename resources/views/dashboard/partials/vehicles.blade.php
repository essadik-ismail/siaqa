<div class="space-y-4">
    @forelse(isset($data) ? $data : [] as $vehicle)
    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg overflow-hidden">
                    <img src="{{ $vehicle->image_url }}" 
                         alt="{{ $vehicle->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">
                        {{ $vehicle->marque->nom ?? 'Unknown' }} {{ $vehicle->modele }}
                    </h4>
                    <p class="text-sm text-gray-600">
                        {{ $vehicle->immatriculation }} • {{ $vehicle->couleur }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">
                    {{ number_format($vehicle->prix_location_jour) }} DH/day
                </div>
                <div class="text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $vehicle->statut === 'disponible' ? 'bg-green-100 text-green-800' : 
                           ($vehicle->statut === 'en_location' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($vehicle->statut) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-car text-gray-400 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No vehicles found</h3>
        <p class="text-gray-600">Get started by adding your first vehicle to the fleet.</p>
    </div>
    @endforelse
</div>

@if(isset($data) && method_exists($data, 'hasPages') && $data->hasPages())
<div class="mt-6">
    {{ $data->links('pagination.dashboard') }}
</div>
@endif
