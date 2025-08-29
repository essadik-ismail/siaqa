@extends('layouts.landing-page')

@section('title', __('cars.search_results'))

@section('content')
    <!-- Header -->
    <div class="pt-20 pb-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('cars.search_results') }}</h1>
            <p class="text-gray-600">Search results for "{{ request('search') }}"</p>
        </div>
    </div>

    <!-- Search Results -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(isset($cars) && $cars->count() > 0)
            <!-- Results Count -->
            <div class="mb-6">
                <p class="text-gray-600">Found {{ $cars->total() }} result(s) for "{{ request('search') }}"</p>
            </div>

            <!-- Cars Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cars as $car)
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                    <div class="relative">
                        <img src="{{ $car->image_url ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=250&fit=crop&crop=center' }}" 
                             alt="{{ $car->name }}" 
                             class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium status-{{ $car->statut == 'disponible' ? 'available' : ($car->statut == 'en_location' ? 'rented' : 'maintenance') }}">
                                {{ ucfirst($car->statut) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $car->name }}</h3>
                            <span class="text-2xl font-bold text-blue-600">${{ number_format($car->prix_jour) }}</span>
                        </div>
                        
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag mr-3 text-blue-500"></i>
                                <span>{{ $car->marque->marque ?? 'Unknown Brand' }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-3 text-blue-500"></i>
                                <span>{{ $car->agence->nom_agence ?? 'Unknown Agency' }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-gas-pump mr-3 text-blue-500"></i>
                                <span>{{ $car->carburant ?? 'Unknown' }}</span>
                            </div>
                            @if($car->transmission)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-cog mr-3 text-blue-500"></i>
                                <span>{{ ucfirst($car->transmission) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('landing.car.show', $car) }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-center text-sm font-medium transition-colors">
                                {{ __('cars.car_details') }}
                            </a>
                            @if($car->statut == 'disponible')
                            <button onclick="showReservationModal({{ $car->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                {{ __('cars.rent_this_car') }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($cars->hasPages())
            <div class="mt-8">
                {{ $cars->appends(request()->query())->links() }}
            </div>
            @endif

        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">No results found</h3>
                <p class="text-gray-500 mb-6">No cars match your search criteria "{{ request('search') }}"</p>
                <div class="space-x-4">
                    <a href="{{ route('landing.cars') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                        Browse All Cars
                    </a>
                    <a href="{{ route('home') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('cars.reserve_this_vehicle') }}</h3>
                    <button onclick="hideReservationModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="reservationForm" method="POST" action="{{ route('landing.reservation.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" id="vehicule_id" name="vehicule_id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cars.pickup_date') }}</label>
                        <input type="date" name="date_debut" required min="{{ date('Y-m-d') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cars.return_date') }}</label>
                        <input type="date" name="date_fin" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex space-x-2">
                        <button type="button" onclick="hideReservationModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            {{ __('cars.reserve_this_vehicle') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showReservationModal(vehiculeId) {
    document.getElementById('vehicule_id').value = vehiculeId;
    document.getElementById('reservationModal').classList.remove('hidden');
}

function hideReservationModal() {
    document.getElementById('reservationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('reservationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideReservationModal();
    }
});
</script>
@endpush 