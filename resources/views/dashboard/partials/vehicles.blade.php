<div class="space-y-4" id="vehiclesContainer">
    @forelse(isset($data) ? $data : [] as $vehicle)
    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors vehicle-item" 
         data-name="{{ strtolower($vehicle->name) }}"
         data-brand="{{ strtolower($vehicle->marque->nom ?? '') }}"
         data-status="{{ strtolower($vehicle->statut) }}"
         data-price="{{ $vehicle->prix_location_jour }}">
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
    <div class="text-center py-8" id="noVehiclesMessage">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-car text-gray-400 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.no_vehicles_found') }}</h3>
        <p class="text-gray-600">{{ __('app.get_started_adding_vehicle') }}</p>
    </div>
    @endforelse
</div>

<!-- Frontend Pagination -->
<div id="vehiclesPagination" class="mt-6 flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
        <button id="prevVehiclesMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            {{ __('app.previous') }}
        </button>
        <button id="nextVehiclesMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            {{ __('app.next') }}
        </button>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                {{ __('app.showing') }} <span id="vehiclesShowingFrom">1</span> {{ __('app.to') }} <span id="vehiclesShowingTo">5</span> {{ __('app.of') }} <span id="vehiclesTotalItems">{{ isset($data) ? $data->count() : 0 }}</span> {{ __('app.results') }}
            </p>
        </div>
        <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button id="prevVehicles" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div id="vehiclesPageNumbers" class="flex">
                    <!-- Page numbers will be generated here -->
                </div>
                <button id="nextVehicles" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </nav>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const VEHICLES_PER_PAGE = 5;
    let currentVehiclesPage = 1;
    let allVehicles = [];
    let filteredVehicles = [];

    // Initialize vehicles pagination
    function initVehiclesPagination() {
        allVehicles = Array.from(document.querySelectorAll('.vehicle-item'));
        filteredVehicles = [...allVehicles];
        displayVehicles();
        setupVehiclesEventListeners();
    }

    // Setup vehicles event listeners
    function setupVehiclesEventListeners() {
        // Pagination buttons
        document.getElementById('prevVehicles').addEventListener('click', () => goToVehiclesPage(currentVehiclesPage - 1));
        document.getElementById('nextVehicles').addEventListener('click', () => goToVehiclesPage(currentVehiclesPage + 1));
        document.getElementById('prevVehiclesMobile').addEventListener('click', () => goToVehiclesPage(currentVehiclesPage - 1));
        document.getElementById('nextVehiclesMobile').addEventListener('click', () => goToVehiclesPage(currentVehiclesPage + 1));
    }

    // Display vehicles for current page
    function displayVehicles() {
        const startIndex = (currentVehiclesPage - 1) * VEHICLES_PER_PAGE;
        const endIndex = startIndex + VEHICLES_PER_PAGE;
        const vehiclesToShow = filteredVehicles.slice(startIndex, endIndex);

        // Hide all vehicles first
        allVehicles.forEach(vehicle => vehicle.style.display = 'none');

        // Show vehicles for current page
        vehiclesToShow.forEach(vehicle => vehicle.style.display = '');

        // Show/hide no vehicles message
        const noVehiclesMessage = document.getElementById('noVehiclesMessage');
        if (filteredVehicles.length === 0) {
            noVehiclesMessage.style.display = 'block';
        } else {
            noVehiclesMessage.style.display = 'none';
        }

        updateVehiclesPagination();
    }

    // Update vehicles pagination
    function updateVehiclesPagination() {
        const totalPages = Math.ceil(filteredVehicles.length / VEHICLES_PER_PAGE);
        const startIndex = (currentVehiclesPage - 1) * VEHICLES_PER_PAGE;
        const endIndex = Math.min(startIndex + VEHICLES_PER_PAGE, filteredVehicles.length);

        // Update showing info
        document.getElementById('vehiclesShowingFrom').textContent = filteredVehicles.length > 0 ? startIndex + 1 : 0;
        document.getElementById('vehiclesShowingTo').textContent = endIndex;
        document.getElementById('vehiclesTotalItems').textContent = filteredVehicles.length;

        // Update pagination buttons
        document.getElementById('prevVehicles').disabled = currentVehiclesPage === 1;
        document.getElementById('nextVehicles').disabled = currentVehiclesPage === totalPages;
        document.getElementById('prevVehiclesMobile').disabled = currentVehiclesPage === 1;
        document.getElementById('nextVehiclesMobile').disabled = currentVehiclesPage === totalPages;

        // Generate page numbers
        generateVehiclesPageNumbers(totalPages);
    }

    // Generate vehicles page number buttons
    function generateVehiclesPageNumbers(totalPages) {
        const container = document.getElementById('vehiclesPageNumbers');
        container.innerHTML = '';

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentVehiclesPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                i === currentVehiclesPage
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            }`;
            button.addEventListener('click', () => goToVehiclesPage(i));
            container.appendChild(button);
        }
    }

    // Go to specific vehicles page
    function goToVehiclesPage(page) {
        const totalPages = Math.ceil(filteredVehicles.length / VEHICLES_PER_PAGE);
        if (page >= 1 && page <= totalPages) {
            currentVehiclesPage = page;
            displayVehicles();
        }
    }

    // Initialize if vehicles container exists
    if (document.getElementById('vehiclesContainer')) {
        // Wait a bit to ensure DOM is fully loaded
        setTimeout(() => {
            initVehiclesPagination();
        }, 100);
    }
});
</script>
