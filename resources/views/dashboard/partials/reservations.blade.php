<div class="space-y-4">
    @forelse(isset($data) ? $data : [] as $reservation)
    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-purple-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">
                        {{ $reservation->client->getFullNameAttribute() }}
                    </h4>
                    <p class="text-sm text-gray-600">
                        {{ $reservation->vehicule->marque ?? 'Unknown' }} {{ $reservation->vehicule->modele }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($reservation->date_debut)->format('M d') }} - 
                        {{ \Carbon\Carbon::parse($reservation->date_fin)->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">
                    {{ number_format($reservation->prix_total) }} DH
                </div>
                <div class="text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $reservation->statut === 'confirmee' ? 'bg-green-100 text-green-800' : 
                           ($reservation->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                           ($reservation->statut === 'terminee' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                        {{ ucfirst($reservation->statut) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-check text-gray-400 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.no_reservations_found') }}</h3>
        <p class="text-gray-600">{{ __('app.reservations_will_appear_here') }}</p>
    </div>
    @endforelse
</div>

@if(isset($data) && method_exists($data, 'hasPages') && $data->hasPages())
<div class="mt-6">
    {{ $data->links('pagination.dashboard') }}
</div>
@endif
