@extends('layouts.landing-page')

@section('title', 'Explore Cars - Ridex')

@section('content')
<!-- Header -->
<div class="section hero" style="padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="h1 hero-title">Explore Our Car Collection</h1>
            <p class="hero-text">Find the perfect car for your next journey from our extensive fleet.</p>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<section class="section">
    <div class="container">
        <div class="search-filters">
            <form action="{{ route('landing.cars') }}" method="GET" class="filter-form">
                <div class="filter-grid">
                    <div class="input-wrapper">
                        <label for="search" class="input-label">Search Cars</label>
                        <input type="text" name="search" id="search" class="input-field" 
                               placeholder="Car, model, or brand" value="{{ request('search') }}">
                    </div>

                    <div class="input-wrapper">
                        <label for="marque" class="input-label">Brand</label>
                        <select name="marque" id="marque" class="input-field">
                            <option value="">All Brands</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque->marque }}" {{ request('marque') == $marque->marque ? 'selected' : '' }}>
                                    {{ $marque->marque }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-wrapper">
                        <label for="prix_min" class="input-label">Min Price</label>
                        <input type="number" name="prix_min" id="prix_min" class="input-field" 
                               placeholder="Min price" value="{{ request('prix_min') }}">
                    </div>

                    <div class="input-wrapper">
                        <label for="prix_max" class="input-label">Max Price</label>
                        <input type="number" name="prix_max" id="prix_max" class="input-field" 
                               placeholder="Max price" value="{{ request('prix_max') }}">
                    </div>

                    <div class="input-wrapper">
                        <label for="annee" class="input-label">Min Year</label>
                        <input type="number" name="annee" id="annee" class="input-field" 
                               placeholder="Min year" value="{{ request('annee') }}">
                    </div>

                    <div class="input-wrapper">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('landing.cars') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Cars Grid -->
<section class="section featured-car">
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Available Cars ({{ $cars->total() }})</h2>
        </div>

        @if($cars->count() > 0)
        <ul class="featured-car-list">
            @foreach($cars as $car)
            <li>
                <div class="featured-car-card">
                    <figure class="card-banner">
                        <img src="{{ $car->image_url ?? asset('app/Rent-Car2/assets/images/car-1.jpg') }}" 
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
                                <strong>${{ number_format($car->prix_jour) }}</strong> / day
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

        <!-- Pagination -->
        @if($cars->hasPages())
        <div class="pagination-wrapper">
            {{ $cars->appends(request()->query())->links() }}
        </div>
        @endif

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
                <input type="text" id="nom" name="nom" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="telephone">Phone</label>
                <input type="tel" id="telephone" name="telephone" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="adresse">Address</label>
                <textarea id="adresse" name="adresse" required class="form-control"></textarea>
            </div>
            
            <div class="form-group">
                <label for="date_debut">Pickup Date</label>
                <input type="date" id="date_debut" name="date_debut" required min="{{ date('Y-m-d') }}" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="date_fin">Return Date</label>
                <input type="date" id="date_fin" name="date_fin" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control">
            </div>
            
            <div class="form-actions">
                <button type="button" onclick="hideReservationModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Reserve Vehicle</button>
            </div>
        </form>
    </div>
</div>
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
}

.form-control:focus {
    outline: none;
    border-color: var(--carolina-blue);
    box-shadow: 0 0 0 2px rgba(204, 91%, 53%, 0.2);
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
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
    document.getElementById('vehicule_id').value = vehiculeId;
    document.getElementById('reservationModal').style.display = 'block';
}

function hideReservationModal() {
    document.getElementById('reservationModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reservationModal');
    if (event.target == modal) {
        hideReservationModal();
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

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ridex cars page loaded successfully!');
});
</script>
@endpush
