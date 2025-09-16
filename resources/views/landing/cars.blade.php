@extends('layouts.landing-page')

@section('title', 'Explore Cars - Odys')

@section('content')
<!-- Search and Filters -->
<section class="section">
    <div class="container">
        <div class="search-filters">
            <div class="filter-form">
                <div class="filter-grid">
                    <div class="input-wrapper">
                        <label for="search" class="input-label">Search Cars</label>
                        <input type="text" id="search" class="input-field" 
                               placeholder="Car, model, or brand">
                    </div>

                    <div class="input-wrapper">
                        <label for="marque" class="input-label">Brand</label>
                        <select id="marque" class="input-field">
                            <option value="">All Brands</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque->marque }}">
                                    {{ $marque->marque }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-wrapper">
                        <label for="prix_min" class="input-label">Min Price</label>
                        <input type="number" id="prix_min" class="input-field" 
                               placeholder="Min price">
                    </div>

                    <div class="input-wrapper">
                        <label for="prix_max" class="input-label">Max Price</label>
                        <input type="number" id="prix_max" class="input-field" 
                               placeholder="Max price">
                    </div>

                    <div class="input-wrapper">
                        <label for="annee" class="input-label">Min Year</label>
                        <input type="number" id="annee" class="input-field" 
                               placeholder="Min year">
                    </div>

                    <div class="input-wrapper">
                        <button type="button" id="search-btn" class="btn btn-primary">Search</button>
                        <button type="button" id="clear-btn" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cars Grid -->
<section class="section featured-car">
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Available Cars (<span id="total-cars">{{ $cars->count() }}</span>)</h2>
        </div>

        @if($cars->count() > 0)
        <ul class="featured-car-list" id="cars-list">
            @foreach($cars as $car)
            <li class="car-item" 
                data-name="{{ strtolower($car->name) }}"
                data-brand="{{ strtolower($car->marque->marque ?? '') }}"
                data-description="{{ strtolower($car->description ?? '') }}"
                data-price="{{ $car->prix_location_jour }}"
                data-year="{{ $car->annee ?? 0 }}"
                data-status="{{ $car->statut }}"
                data-fuel="{{ strtolower($car->carburant ?? '') }}"
                data-transmission="{{ strtolower($car->transmission ?? '') }}"
                data-seats="{{ $car->nombre_places ?? 0 }}">
                <div class="featured-car-card">
                    <figure class="card-banner">
                        <img src="{{ $car->image_url }}" 
                             alt="{{ $car->name }}" loading="lazy" width="440" height="300" class="w-100">
                        <div class="status-badge {{ $car->statut == 'disponible' ? 'available' : 'unavailable' }}">
                            {{ ucfirst($car->statut) }}
                        </div>
                    </figure>

                    <div class="card-content">
                        <div class="card-title-wrapper">
                            <h3 class="h3 card-title">
                                <a href="{{ route('landing.car.show', $car) }}">{{ $car->name }}</a>
                            </h3>
                            <data class="year" value="{{ $car->annee ?? 'N/A' }}">{{ $car->annee ?? 'N/A' }}</data>
                        </div>

                        <ul class="card-list">
                            <li class="card-list-item">
                                <ion-icon name="people-outline"></ion-icon>
                                <span class="card-item-text">{{ $car->nombre_places ?? 4 }} People</span>
                            </li>

                            <li class="card-list-item">
                                <ion-icon name="flash-outline"></ion-icon>
                                <span class="card-item-text">{{ $car->carburant ?? 'Gasoline' }}</span>
                            </li>

                            <li class="card-list-item">
                                <ion-icon name="speedometer-outline"></ion-icon>
                                <span class="card-item-text">{{ $car->kilometrage ?? 'N/A' }} km</span>
                            </li>

                            <li class="card-list-item">
                                <ion-icon name="hardware-chip-outline"></ion-icon>
                                <span class="card-item-text">{{ ucfirst($car->transmission ?? 'Automatic') }}</span>
                            </li>
                        </ul>

                        <div class="card-price-wrapper">
                            <p class="card-price">
                                <strong>{{ number_format($car->prix_location_jour) }} DH</strong> / day
                            </p>

                            <button class="btn fav-btn" aria-label="Add to favourite list" 
                                    onclick="toggleFavorite({{ $car->id }})">
                                <ion-icon name="heart-outline" id="heart-{{ $car->id }}"></ion-icon>
                            </button>

                            @if($car->statut == 'disponible')
                            <button class="btn" onclick="showReservationModal({{ $car->id }})">Rent now</button>
                            @else
                            <button class="btn" disabled>{{ ucfirst($car->statut) }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

        <!-- Frontend Pagination -->
        <div class="pagination-wrapper" id="pagination-wrapper" style="display: none;">
            <!-- Results Summary -->
            <div class="pagination-info">
                <span>Showing</span>
                <span class="results-count" id="showing-from">0</span>
                <span>to</span>
                <span class="results-count" id="showing-to">0</span>
                <span>of</span>
                <span class="results-count" id="total-results">0</span>
                <span>results</span>
            </div>
            
            <!-- Previous/Next Navigation -->
            <div class="pagination-nav">
                <button class="nav-btn" id="prev-btn" disabled>« Previous</button>

                <!-- Page Numbers -->
                <div class="page-numbers" id="page-numbers">
                    <!-- Page numbers will be generated by JavaScript -->
                </div>

                <button class="nav-btn" id="next-btn" disabled>Next »</button>
            </div>
        </div>

        @else
        <div class="no-results">
            <div class="no-results-content">
                <ion-icon name="car-outline" class="no-results-icon"></ion-icon>
                <h3>No cars found</h3>
                <p>Try adjusting your search criteria or browse all available cars.</p>
                <a href="{{ route('landing.cars') }}" class="btn">View All Cars</a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Reservation Modal -->
<div id="reservationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reserve This Vehicle</h3>
            <span class="close" onclick="hideReservationModal()">&times;</span>
        </div>
        
        <form id="reservationForm" method="POST" action="{{ route('landing.reservation.store') }}" class="modal-body">
            @csrf
            <input type="hidden" id="vehicule_id" name="vehicule_id">
            
            <div class="form-group">
                <label for="nom">Full Name</label>
                <input type="text" id="nom" name="nom" required class="form-control" value="{{ old('nom') }}">
                <div class="error-message" id="nom-error" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required class="form-control" value="{{ old('email') }}">
                <div class="error-message" id="email-error" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="telephone">Phone</label>
                <input type="tel" id="telephone" name="telephone" required class="form-control" value="{{ old('telephone') }}">
                <div class="error-message" id="telephone-error" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="adresse">Address</label>
                <textarea id="adresse" name="adresse" required class="form-control">{{ old('adresse') }}</textarea>
                <div class="error-message" id="adresse-error" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="date_debut">Pickup Date</label>
                <input type="date" id="date_debut" name="date_debut" required min="{{ date('Y-m-d') }}" class="form-control" value="{{ old('date_debut') }}">
                <div class="error-message" id="date_debut-error" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="date_fin">Return Date</label>
                <input type="date" id="date_fin" name="date_fin" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control" value="{{ old('date_fin') }}">
                <div class="error-message" id="date_fin-error" style="display: none;"></div>
            </div>
            
            <!-- General error message for non-field specific errors -->
            <div class="error-message general-error" id="general-error" style="display: none;"></div>
            
            <div class="form-actions">
                <button type="button" onclick="hideReservationModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Reserve Vehicle</span>
                    <span class="btn-loading" style="display: none;">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all car items
    const allCars = Array.from(document.querySelectorAll('.car-item'));
    let filteredCars = [...allCars];
    let currentPage = 1;
    const ITEMS_PER_PAGE = 12;
    let totalPages = Math.ceil(allCars.length / ITEMS_PER_PAGE);

    // Get form elements
    const searchInput = document.getElementById('search');
    const brandSelect = document.getElementById('marque');
    const minPriceInput = document.getElementById('prix_min');
    const maxPriceInput = document.getElementById('prix_max');
    const yearInput = document.getElementById('annee');
    const searchBtn = document.getElementById('search-btn');
    const clearBtn = document.getElementById('clear-btn');

    // Pagination elements
    const paginationWrapper = document.getElementById('pagination-wrapper');
    const showingFrom = document.getElementById('showing-from');
    const showingTo = document.getElementById('showing-to');
    const totalResults = document.getElementById('total-results');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pageNumbers = document.getElementById('page-numbers');

    // Initialize
    initializeCars();
    setupEventListeners();

    function initializeCars() {
        displayCars();
        updatePagination();
    }

    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterAndDisplayCars();
            }, 300);
        });

        // Filter inputs
        [brandSelect, minPriceInput, maxPriceInput, yearInput].forEach(input => {
            input.addEventListener('change', filterAndDisplayCars);
        });

        // Search and clear buttons
        searchBtn.addEventListener('click', filterAndDisplayCars);
        clearBtn.addEventListener('click', clearFilters);

        // Pagination buttons
        prevBtn.addEventListener('click', goToPreviousPage);
        nextBtn.addEventListener('click', goToNextPage);
    }

    function filterAndDisplayCars() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedBrand = brandSelect.value.toLowerCase();
        const minPrice = parseFloat(minPriceInput.value) || 0;
        const maxPrice = parseFloat(maxPriceInput.value) || Infinity;
        const minYear = parseInt(yearInput.value) || 0;

        filteredCars = allCars.filter(car => {
            const name = car.dataset.name || '';
            const brand = car.dataset.brand || '';
            const description = car.dataset.description || '';
            const price = parseFloat(car.dataset.price) || 0;
            const year = parseInt(car.dataset.year) || 0;

            // Search filter
            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                brand.includes(searchTerm) || 
                description.includes(searchTerm);

            // Brand filter
            const matchesBrand = !selectedBrand || brand.includes(selectedBrand);

            // Price filter
            const matchesPrice = price >= minPrice && price <= maxPrice;

            // Year filter
            const matchesYear = year >= minYear;

            return matchesSearch && matchesBrand && matchesPrice && matchesYear;
        });

        currentPage = 1;
        totalPages = Math.ceil(filteredCars.length / ITEMS_PER_PAGE);
        displayCars();
        updatePagination();
    }

    function displayCars() {
        const carsList = document.getElementById('cars-list');
        const totalCarsSpan = document.getElementById('total-cars');
        
        // Hide all cars first
        allCars.forEach(car => {
            car.style.display = 'none';
        });

        // Show filtered cars for current page
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const carsToShow = filteredCars.slice(startIndex, endIndex);

        carsToShow.forEach(car => {
            car.style.display = 'block';
        });

        // Update total count
        totalCarsSpan.textContent = filteredCars.length;

        // Show/hide pagination
        if (filteredCars.length > ITEMS_PER_PAGE) {
            paginationWrapper.style.display = 'block';
        } else {
            paginationWrapper.style.display = 'none';
        }
    }

    function updatePagination() {
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const endIndex = Math.min(currentPage * ITEMS_PER_PAGE, filteredCars.length);

        showingFrom.textContent = filteredCars.length > 0 ? startIndex : 0;
        showingTo.textContent = endIndex;
        totalResults.textContent = filteredCars.length;

        // Update pagination buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;

        // Generate page numbers
        generatePageNumbers();
    }

    function generatePageNumbers() {
        pageNumbers.innerHTML = '';
        
        if (totalPages <= 1) return;

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-link ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => goToPage(i));
            pageNumbers.appendChild(pageBtn);
        }
    }

    function goToPage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayCars();
            updatePagination();
        }
    }

    function goToPreviousPage() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    }

    function goToNextPage() {
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    }

    function clearFilters() {
        searchInput.value = '';
        brandSelect.value = '';
        minPriceInput.value = '';
        maxPriceInput.value = '';
        yearInput.value = '';
        
        filteredCars = [...allCars];
        currentPage = 1;
        totalPages = Math.ceil(filteredCars.length / ITEMS_PER_PAGE);
        displayCars();
        updatePagination();
    }
});
</script>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('app/Rent-Car2/assets/css/style.css') }}">
<style>
/* Additional styles for cars page */
.search-filters {
    background: white;
    padding: 30px;
    border-radius: var(--radius-14);
    box-shadow: var(--shadow-1);
    margin-bottom: 40px;
}

.filter-form {
    width: 100%;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    align-items: end;
}

.input-wrapper {
    display: flex;
    flex-direction: column;
}

.input-wrapper:last-child {
    display: flex;
    flex-direction: row;
    gap: 10px;
}

.input-label {
    font-size: var(--fs-6);
    font-weight: var(--fw-600);
    color: var(--space-cadet);
    margin-bottom: 8px;
}

.input-field {
    padding: 12px 16px;
    border: 1px solid var(--alice-blue-4);
    border-radius: var(--radius-10);
    font-size: var(--fs-6);
    transition: var(--transition);
}

.input-field:focus {
    outline: none;
    border-color: var(--carolina-blue);
    box-shadow: 0 0 0 2px rgba(204, 91%, 53%, 0.2);
}

.btn-primary {
    background: var(--carolina-blue);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--radius-10);
    font-weight: var(--fw-600);
    cursor: pointer;
    transition: var(--transition);
}

.btn-primary:hover {
    background: var(--sapphire);
}

.btn-secondary {
    background: var(--manatee);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--radius-10);
    font-weight: var(--fw-600);
    cursor: pointer;
    transition: var(--transition);
}

.btn-secondary:hover {
    background: var(--independence);
}

.status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: var(--fs-7);
    font-weight: var(--fw-600);
    text-transform: uppercase;
}

.status-badge.available {
    background: var(--medium-sea-green);
    color: white;
}

.status-badge.unavailable {
    background: var(--red-salsa);
    color: white;
}

.pagination-wrapper {
    margin-top: 40px;
    text-align: center;
}

/* Custom Pagination Styling */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
}

.pagination li {
    margin: 0;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 8px 12px;
    margin: 0;
    line-height: 1;
    color: var(--space-cadet);
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.pagination .page-link:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
    color: var(--space-cadet);
}

.pagination .active .page-link {
    background-color: var(--carolina-blue);
    border-color: var(--carolina-blue);
    color: white;
    font-weight: 600;
}

.pagination .active .page-link:hover {
    background-color: var(--carolina-blue);
    border-color: var(--carolina-blue);
    color: white;
}

.pagination .disabled .page-link {
    color: #9ca3af;
    background-color: #f9fafb;
    border-color: #e5e7eb;
    cursor: not-allowed;
    opacity: 0.5;
}

.pagination .disabled .page-link:hover {
    background-color: #f9fafb;
    border-color: #e5e7eb;
    color: #9ca3af;
    transform: none;
    box-shadow: none;
}

/* Pagination info styling */
.pagination-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 0;
    background: transparent;
    font-size: 14px;
    color: #6b7280;
    font-weight: 400;
}

.pagination-info .results-count {
    color: var(--space-cadet);
    font-weight: 500;
}

/* Previous/Next Navigation */
.pagination-nav {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 20px;
}

.nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.nav-btn:hover {
    background-color: #e5e7eb;
    border-color: #9ca3af;
    color: #111827;
}

.nav-btn.disabled {
    background-color: #f9fafb;
    color: #9ca3af;
    border-color: #e5e7eb;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Page Numbers Container */
.pagination-numbers {
    display: flex;
    justify-content: center;
}

/* Pagination wrapper improvements */
.pagination-wrapper {
    margin-top: 40px;
    text-align: center;
    padding: 20px 0;
    border-top: 1px solid #e5e7eb;
}

/* Page numbers styling */
.page-numbers {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin: 0 20px;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 8px 12px;
    margin: 0;
    line-height: 1;
    color: var(--space-cadet);
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.page-link:hover:not(.active):not(:disabled) {
    background-color: #f3f4f6;
    border-color: #d1d5db;
    color: var(--space-cadet);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-link.active {
    background-color: var(--space-cadet);
    color: white;
    border-color: var(--space-cadet);
    transform: none;
    box-shadow: none;
}

.page-link:disabled {
    background-color: #f9fafb;
    border-color: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination {
        gap: 4px;
    }
    
    .pagination .page-link {
        min-width: 40px;
        height: 40px;
        padding: 6px 10px;
        font-size: 13px;
    }
    
    .pagination-info {
        flex-direction: column;
        gap: 8px;
        text-align: center;
        margin-bottom: 16px;
    }
    
    .pagination-nav {
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .nav-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .pagination-wrapper {
        padding: 16px 0;
    }
}

.no-results {
    text-align: center;
    padding: 60px 20px;
}

.no-results-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-results-icon {
    font-size: 80px;
    color: var(--manatee);
    margin-bottom: 20px;
}

.no-results h3 {
    font-size: var(--fs-3);
    color: var(--space-cadet);
    margin-bottom: 15px;
}

.no-results p {
    color: var(--manatee);
    margin-bottom: 25px;
    line-height: 1.6;
}

/* Modal styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--space-cadet);
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: var(--deep-cerise);
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: var(--space-cadet);
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--carolina-blue);
    box-shadow: 0 0 0 2px rgba(204, 91%, 53%, 0.2);
}

.form-control.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
}

/* Error message styles */
.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    padding: 5px 8px;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    display: flex;
    align-items: center;
}

.error-message::before {
    content: "⚠";
    margin-right: 5px;
    font-size: 14px;
}

.error-message.general-error {
    margin-bottom: 15px;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

/* Loading button styles */
.btn-loading {
    display: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .input-wrapper:last-child {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>
@endpush

@push('scripts')
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="{{ asset('app/Rent-Car2/assets/js/script.js') }}"></script>
<script>
// Reservation Modal Functions
function showReservationModal(vehiculeId) {
    // Clear any previous errors
    clearAllErrors();
    
    document.getElementById('vehicule_id').value = vehiculeId;
    document.getElementById('reservationModal').style.display = 'block';
}

function hideReservationModal() {
    document.getElementById('reservationModal').style.display = 'none';
    // Clear form and errors when closing
    clearAllErrors();
    document.getElementById('reservationForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reservationModal');
    if (event.target == modal) {
        hideReservationModal();
    }
}

// Clear all error messages
function clearAllErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(error => {
        error.style.display = 'none';
        error.textContent = '';
    });
    
    // Remove error class from form controls
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.classList.remove('error');
    });
}

// Show error for specific field
function showFieldError(fieldName, message) {
    const errorElement = document.getElementById(fieldName + '-error');
    const inputElement = document.getElementById(fieldName);
    
    if (errorElement && inputElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        inputElement.classList.add('error');
    }
}

// Show general error
function showGeneralError(message) {
    const errorElement = document.getElementById('general-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Favorite functionality
function toggleFavorite(vehiculeId) {
    const heartIcon = document.getElementById(`heart-${vehiculeId}`);
    if (heartIcon.getAttribute('name') === 'heart-outline') {
        heartIcon.setAttribute('name', 'heart');
        heartIcon.style.color = 'var(--deep-cerise)';
        // Here you could add AJAX call to save favorite
    } else {
        heartIcon.setAttribute('name', 'heart-outline');
        heartIcon.style.color = 'inherit';
        // Here you could add AJAX call to remove favorite
    }
}

// Form validation for dates
document.getElementById('date_debut').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('date_fin');
    endDateInput.min = startDate;
    
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
});

// Handle form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.getElementById('reservationForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearAllErrors();
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            
            // Get form data
            const formData = new FormData(reservationForm);
            
            // Submit via AJAX
            fetch(reservationForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success - close modal and show success message
                    hideReservationModal();
                    showSuccessMessage(data.message || 'Reservation submitted successfully!');
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorMessages = data.errors[field];
                            if (Array.isArray(errorMessages)) {
                                showFieldError(field, errorMessages[0]);
                            } else {
                                showFieldError(field, errorMessages);
                            }
                        });
                    } else if (data.message) {
                        showGeneralError(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showGeneralError('An error occurred while processing your reservation. Please try again or contact support.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            });
        });
    }
    
    console.log('Odys cars page loaded successfully!');
});

// Show success message
function showSuccessMessage(message) {
    // Create a temporary success message
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #d4edda;
        color: #155724;
        padding: 15px 20px;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        z-index: 10000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 300px;
    `;
    successDiv.innerHTML = `
        <div style="display: flex; align-items: center;">
            <span style="margin-right: 10px; font-size: 18px;">✓</span>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(successDiv);
    
    // Remove after 5 seconds
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.parentNode.removeChild(successDiv);
        }
    }, 5000);
}
</script>
@endpush
