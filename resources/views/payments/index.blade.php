@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Paiements</h1>
                    <p class="mt-2 text-gray-600 text-lg">Gérez les paiements de votre auto-école</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('payments.create') }}" class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Enregistrer un Paiement
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Revenus</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue ?? 0, 2) }} DH</p>
                    </div>
                    <div class="icon-container w-12 h-12">
                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Paiements du Mois</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $monthlyPayments ?? 0 }}</p>
                    </div>
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">En Attente</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingPayments ?? 0 }}</p>
                    </div>
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Taux de Réussite</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $successRate ?? 0 }}%</p>
                    </div>
                    <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="material-card p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher des paiements..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En Attente</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Méthode</label>
                    <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les Méthodes</option>
                        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                        <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Carte</option>
                        <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                        <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>Chèque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full material-button">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Payments List -->
        <div class="space-y-4">
            @forelse($payments as $payment)
                <div class="material-card p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="fas fa-money-bill-wave text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Paiement #{{ $payment->id }}</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><i class="fas fa-user mr-1"></i>{{ $payment->student->name ?? 'Aucun Étudiant' }}</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $payment->created_at->format('d M Y, H:i') }}</span>
                                    <span><i class="fas fa-credit-card mr-1"></i>{{ ucfirst(str_replace('_', ' ', $payment->method ?? 'N/A')) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-lg font-semibold text-gray-900">{{ number_format($payment->amount, 2) }} DH</div>
                                <div class="text-sm text-gray-500">{{ $payment->description ?? 'Paiement' }}</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                $payment->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($payment->status == 'failed' ? 'bg-red-100 text-red-800' : 
                                'bg-gray-100 text-gray-800')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $payment->status ?? 'pending')) }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('payments.show', $payment) }}" 
                                   class="material-button px-3 py-1 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir
                                </a>
                                <a href="{{ route('payments.edit', $payment) }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-24 h-24 icon-container mx-auto mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-money-bill-wave text-4xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement trouvé</h3>
                    <p class="text-gray-500 mb-6">Commencez par enregistrer votre premier paiement.</p>
                    <a href="{{ route('payments.create') }}" 
                       class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Enregistrer un Paiement
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection