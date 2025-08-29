@extends('layouts.landing-page')

@section('title', 'Ridex - Rent your favourite car')

@section('content')
<!-- Hero Section -->
<section class="section hero" id="home">
    <div class="container">
        <div class="hero-content">
            <h2 class="h1 hero-title">The easy way to takeover a lease</h2>
            <p class="hero-text">
                Live in New York, New Jerset and Connecticut!
            </p>
        </div>

        <div class="hero-banner"></div>

        <form action="{{ route('landing.cars') }}" method="GET" class="hero-form">
            <div class="input-wrapper">
                <label for="input-1" class="input-label">Car, model, or brand</label>
                <input type="text" name="search" id="input-1" class="input-field" 
                       placeholder="What car are you looking?" value="{{ request('search') }}">
                </div>
                
            <div class="input-wrapper">
                <label for="input-2" class="input-label">Max. monthly payment</label>
                <input type="number" name="prix_max" id="input-2" class="input-field" 
                       placeholder="Add an amount in $" value="{{ request('prix_max') }}">
                </div>
                
            <div class="input-wrapper">
                <label for="input-3" class="input-label">Make Year</label>
                <input type="number" name="annee" id="input-3" class="input-field" 
                       placeholder="Add a minimal make year" value="{{ request('annee') }}">
            </div>

            <button type="submit" class="btn">Search</button>
        </form>
    </div>
</section>

<!-- Featured Cars Section -->
<section class="section featured-car" id="featured-car">
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Featured cars</h2>
            <a href="{{ route('landing.cars') }}" class="featured-car-link">
                <span>View more</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
        </div>

        <ul class="featured-car-list">
            @forelse($featuredCars as $car)
            <li>
                <div class="featured-car-card">
                    <figure class="card-banner">
                        <img src="{{ $car->image_url ?? asset('app/Rent-Car2/assets/images/car-1.jpg') }}" 
                             alt="{{ $car->name }}" loading="lazy" width="440" height="300" class="w-100">
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
            @empty
            <li class="text-center py-8">
                <p>No featured cars available at the moment.</p>
            </li>
            @endforelse
        </ul>
            </div>
        </section>

<!-- Statistics Section -->
<section class="section stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-icon">
                    <ion-icon name="car-outline"></ion-icon>
                        </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $totalCars }}</h3>
                    <p class="stats-text">Total Vehicles</p>
                        </div>
                    </div>
                    
            <div class="stats-card">
                <div class="stats-icon">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                        </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $availableCars }}</h3>
                    <p class="stats-text">Available Cars</p>
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-icon">
                    <ion-icon name="business-outline"></ion-icon>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $totalAgencies }}</h3>
                    <p class="stats-text">Rental Agencies</p>
                        </div>
                    </div>
                    
            <div class="stats-card">
                <div class="stats-icon">
                    <ion-icon name="happy-outline"></ion-icon>
                            </div>
                <div class="stats-content">
                    <h3 class="stats-number">1000+</h3>
                    <p class="stats-text">Happy Customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Blog Section -->
<section class="section blog" id="blog">
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Latest Blog Posts</h2>
            <a href="#" class="blog-link">
                <span>View more</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
                
        <ul class="blog-list">
            <li>
                <div class="blog-card">
                    <figure class="card-banner">
                        <img src="{{ asset('app/Rent-Car2/assets/images/blog-1.jpg') }}" alt="Blog Post 1" loading="lazy" width="440" height="300" class="w-100">
                    </figure>
                    <div class="card-content">
                        <h3 class="h3 card-title">
                            <a href="#">Best Car Rental Tips for Travelers</a>
                        </h3>
                        <p class="card-text">Discover the essential tips for a smooth car rental experience...</p>
                        <a href="#" class="btn">Read More</a>
                    </div>
                </div>
            </li>

            <li>
                <div class="blog-card">
                    <figure class="card-banner">
                        <img src="{{ asset('app/Rent-Car2/assets/images/blog-2.jpg') }}" alt="Blog Post 2" loading="lazy" width="440" height="300" class="w-100">
                    </figure>
                    <div class="card-content">
                        <h3 class="h3 card-title">
                            <a href="#">Top 10 Most Popular Rental Cars</a>
                        </h3>
                        <p class="card-text">Find out which cars are most in demand for rentals...</p>
                        <a href="#" class="btn">Read More</a>
                    </div>
                            </div>
            </li>

            <li>
                <div class="blog-card">
                    <figure class="card-banner">
                        <img src="{{ asset('app/Rent-Car2/assets/images/blog-3.jpg') }}" alt="Blog Post 3" loading="lazy" width="440" height="300" class="w-100">
                    </figure>
                    <div class="card-content">
                        <h3 class="h3 card-title">
                            <a href="#">How to Choose the Right Rental Car</a>
                        </h3>
                        <p class="card-text">A comprehensive guide to selecting the perfect rental vehicle...</p>
                        <a href="#" class="btn">Read More</a>
                    </div>
                </div>
            </li>
        </ul>
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
/* Additional styles for modal and stats */
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

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.stats {
    background: var(--gradient);
    padding: var(--section-padding) 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    text-align: center;
}

.stats-card {
    background: white;
    padding: 30px 20px;
    border-radius: var(--radius-14);
    box-shadow: var(--shadow-1);
}

.stats-icon {
    width: 60px;
    height: 60px;
    background: var(--carolina-blue);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.stats-icon ion-icon {
    font-size: 30px;
    color: white;
}

.stats-number {
    font-size: var(--fs-1);
    font-weight: var(--fw-600);
    color: var(--space-cadet);
    margin-bottom: 10px;
}

.stats-text {
    color: var(--manatee);
    font-size: var(--fs-6);
}

.blog {
    padding: var(--section-padding) 0;
}

.blog-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.blog-card {
    background: white;
    border-radius: var(--radius-14);
    overflow: hidden;
    box-shadow: var(--shadow-1);
    transition: var(--transition);
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-2);
}

.blog-card .card-banner img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.blog-card .card-content {
    padding: 20px;
}

.blog-card .card-title a {
    color: var(--space-cadet);
    text-decoration: none;
    font-weight: var(--fw-600);
}

.blog-card .card-title a:hover {
    color: var(--carolina-blue);
}

.blog-card .card-text {
    color: var(--manatee);
    margin: 15px 0;
    line-height: 1.6;
}

.blog-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--carolina-blue);
    text-decoration: none;
    font-weight: var(--fw-600);
    transition: var(--transition);
}

.blog-link:hover {
    color: var(--deep-cerise);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .blog-list {
        grid-template-columns: 1fr;
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
    // Any additional initialization code
    console.log('Ridex landing page loaded successfully!');
        });
    </script>
@endpush
