<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $vehicule->marque->nom ?? 'Unknown' }} {{ $vehicule->modele }} - CarRental</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .status-available { @apply bg-green-100 text-green-800; }
        .status-rented { @apply bg-red-100 text-red-800; }
        .status-maintenance { @apply bg-yellow-100 text-yellow-800; }
        .reservation-form {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">C</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">CarRental</span>
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('landing.cars') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        View All Cars
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('landing.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Register
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="pt-20 pb-4 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('landing') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('landing.cars') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                                Cars
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-900 font-medium">{{ $vehicule->marque->nom ?? 'Unknown' }} {{ $vehicule->modele }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Car Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Car Image Placeholder -->
                    <div class="h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative">
                        <i class="fas fa-car text-8xl text-gray-400"></i>
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-2 rounded-full text-sm font-medium 
                                @if($vehicule->statut === 'disponible') status-available
                                @elseif($vehicule->statut === 'loue') status-rented
                                @else status-maintenance @endif">
                                {{ ucfirst($vehicule->statut) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Car Information -->
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                    {{ $vehicule->marque->nom ?? 'Unknown' }} {{ $vehicule->modele }}
                                </h1>
                                <p class="text-xl text-gray-600">{{ $vehicule->immatriculation }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-blue-600">${{ number_format($vehicule->prix_jour, 2) }}</div>
                                <div class="text-gray-500">per day</div>
                            </div>
                        </div>
                        
                        <!-- Car Specifications -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <i class="fas fa-building text-blue-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Agency: {{ $vehicule->agence->nom ?? 'Location TBD' }}</span>
                                </div>
                                @if($vehicule->annee)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-green-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Year: {{ $vehicule->annee }}</span>
                                </div>
                                @endif
                                @if($vehicule->couleur)
                                <div class="flex items-center">
                                    <i class="fas fa-palette text-purple-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Color: {{ $vehicule->couleur }}</span>
                                </div>
                                @endif
                                @if($vehicule->carburant)
                                <div class="flex items-center">
                                    <i class="fas fa-gas-pump text-orange-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Fuel: {{ $vehicule->carburant }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @if($vehicule->transmission)
                                <div class="flex items-center">
                                    <i class="fas fa-cog text-red-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Transmission: {{ $vehicule->transmission }}</span>
                                </div>
                                @endif
                                @if($vehicule->puissance)
                                <div class="flex items-center">
                                    <i class="fas fa-tachometer-alt text-indigo-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Power: {{ $vehicule->puissance }} HP</span>
                                </div>
                                @endif
                                @if($vehicule->kilometrage)
                                <div class="flex items-center">
                                    <i class="fas fa-road text-teal-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Mileage: {{ number_format($vehicule->kilometrage) }} km</span>
                                </div>
                                @endif
                                @if($vehicule->capacite)
                                <div class="flex items-center">
                                    <i class="fas fa-users text-pink-500 w-6 mr-3"></i>
                                    <span class="text-gray-700">Capacity: {{ $vehicule->capacite }} persons</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($vehicule->description)
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-600">{{ $vehicule->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Related Cars -->
                @if($relatedCars->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Vehicles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($relatedCars as $relatedCar)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="h-32 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-car text-3xl text-gray-400"></i>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    {{ $relatedCar->marque->nom ?? 'Unknown' }} {{ $relatedCar->modele }}
                                </h3>
                                <p class="text-gray-600 mb-2">${{ number_format($relatedCar->prix_jour, 2) }}/day</p>
                                <a href="{{ route('landing.car.show', $relatedCar) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Reservation Form -->
            <div class="lg:col-span-1">
                <div class="reservation-form rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Make a Reservation</h2>
                    
                    @if($vehicule->statut !== 'disponible')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <span class="text-red-700 font-medium">This vehicle is not available for reservation.</span>
                            </div>
                        </div>
                    @else
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-red-700 font-medium">Please fix the following errors:</span>
                                </div>
                                <ul class="text-red-600 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('landing.reservation.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="vehicule_id" value="{{ $vehicule->id }}">
                            
                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" required
                                           min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <!-- Personal Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="nom" value="{{ old('nom') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="adresse" rows="3" required
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('adresse') }}</textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold text-lg transition-colors">
                                Reserve This Vehicle
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                By making a reservation, you agree to our terms and conditions.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">C</span>
                        </div>
                        <span class="text-xl font-bold">CarRental</span>
                    </div>
                    <p class="text-gray-400">
                        Premium car rental service providing quality vehicles and exceptional customer experience.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('landing') }}" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('landing.cars') }}" class="text-gray-400 hover:text-white transition-colors">View Cars</a></li>
                        <li><a href="{{ route('landing.register') }}" class="text-gray-400 hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400">Car Rental</li>
                        <li class="text-gray-400">Long-term Leasing</li>
                        <li class="text-gray-400">Corporate Solutions</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@carrental.com</li>
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>123 Main St, City</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} CarRental. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @if(session('success'))
    <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    @endif

    <script>
        // Auto-calculate end date minimum based on start date
        document.querySelector('input[name="date_debut"]').addEventListener('change', function() {
            const startDate = this.value;
            if (startDate) {
                const endDateInput = document.querySelector('input[name="date_fin"]');
                const minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);
                endDateInput.min = minEndDate.toISOString().split('T')[0];
                
                // If current end date is before new minimum, clear it
                if (endDateInput.value && endDateInput.value < endDateInput.min) {
                    endDateInput.value = '';
                }
            }
        });
    </script>
</body>
</html>
