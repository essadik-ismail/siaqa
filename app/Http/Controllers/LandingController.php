<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Marque;
use App\Models\Agence;
use App\Models\Tenant;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        // Get current tenant from domain or default to first active tenant
        $currentTenant = $this->getCurrentTenant();
        
        if ($currentTenant) {
            $featuredCars = Vehicule::with('marque', 'agence')
                ->where('tenant_id', $currentTenant->id)
                ->landingDisplay()
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
                
            $totalCars = Vehicule::where('tenant_id', $currentTenant->id)->count();
            $availableCars = Vehicule::where('tenant_id', $currentTenant->id)
                ->where('statut', 'disponible')
                ->count();
            $totalAgencies = Agence::where('tenant_id', $currentTenant->id)
                ->where('is_active', true)
                ->count();
            $totalCustomers = Client::where('tenant_id', $currentTenant->id)->count();
        } else {
            // Fallback for when no tenant is found
            $featuredCars = collect();
            $totalCars = 0;
            $availableCars = 0;
            $totalAgencies = 0;
            $totalCustomers = 0;
        }
        
        return view('landing.index', compact('featuredCars', 'totalCars', 'availableCars', 'totalAgencies', 'totalCustomers', 'currentTenant'));
    }
    
    public function cars(Request $request)
    {
        // Get current tenant from domain or default to first active tenant
        $currentTenant = $this->getCurrentTenant();
        
        if (!$currentTenant) {
            abort(404, 'Tenant not found');
        }
        
        $query = Vehicule::with('marque', 'agence')
            ->where('tenant_id', $currentTenant->id);
        
        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('marque', function($subQ) use ($request) {
                      $subQ->where('marque', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        // Apply filters
        if ($request->filled('marque')) {
            $query->whereHas('marque', function($q) use ($request) {
                $q->where('marque', 'like', '%' . $request->marque . '%');
            });
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('prix_min')) {
            $query->where('prix_jour', '>=', $request->prix_min);
        }
        
        if ($request->filled('prix_max')) {
            $query->where('prix_jour', '<=', $request->prix_max);
        }
        
        if ($request->filled('annee')) {
            $query->where('annee', '>=', $request->annee);
        }
        
        $cars = $query->landingDisplay()->orderBy('created_at', 'desc')->paginate(12);
        $marques = Marque::where('tenant_id', $currentTenant->id)->where('is_active', true)->get();
        
        return view('landing.cars', compact('cars', 'marques', 'currentTenant'));
    }
    
    public function showCar(Vehicule $vehicule)
    {
        // Check if vehicle should be displayed on landing page
        if (!$vehicule->landing_display || !$vehicule->is_active || $vehicule->statut !== 'disponible') {
            abort(404, 'Vehicle not found');
        }
        
        $vehicule->load('marque', 'agence');
        $relatedCars = Vehicule::with('marque')
            ->where('id', '!=', $vehicule->id)
            ->where('marque_id', $vehicule->marque_id)
            ->landingDisplay()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            
        return view('landing.car-show', compact('vehicule', 'relatedCars'));
    }
    
    public function showRegister()
    {
        return view('landing.register');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect directly to dashboard without success message
            return redirect()->route('dashboard');
        }
        
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'tenant_id' => 1, // Default tenant
        ]);
        
        Auth::login($user);
        
        return redirect()->route('landing')->with('success', 'Account created successfully! Welcome to Odys.');
    }
    
    public function storeReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        // Check if car is available for the selected dates
        $vehicule = Vehicule::findOrFail($request->vehicule_id);
        
        if ($vehicule->statut !== 'disponible') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['vehicule_id' => ['This car is not available for reservation.']]
                ], 422);
            }
            return back()->withErrors(['vehicule_id' => 'This car is not available for reservation.'])->withInput();
        }
        
        // Check for conflicting reservations
        $conflictingReservation = Reservation::where('vehicule_id', $request->vehicule_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                    ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                    ->orWhere(function($q) use ($request) {
                        $q->where('date_debut', '<=', $request->date_debut)
                            ->where('date_fin', '>=', $request->date_fin);
                    });
            })
            ->first();
            
        if ($conflictingReservation) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['dates' => ['This car is not available for the selected dates.']]
                ], 422);
            }
            return back()->withErrors(['dates' => 'This car is not available for the selected dates.'])->withInput();
        }
        
        // Calculate total amount
        $startDate = Carbon::parse($request->date_debut);
        $endDate = Carbon::parse($request->date_fin);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalAmount = $vehicule->prix_location_jour * $days;
        
        try {
            // Create or find client from form data
            $client = Client::firstOrCreate(
                ['email' => $request->email],
                [
                    'tenant_id' => 1, // Default tenant
                    'type' => 'client',
                    'nom' => $request->nom,
                    'email' => $request->email,
                    'telephone' => $request->telephone,
                    'adresse' => $request->adresse,
                ]
            );

            // Generate reservation number
            $numeroReservation = 'RES-' . date('Ymd') . '-' . str_pad(Reservation::count() + 1, 4, '0', STR_PAD_LEFT);

            $reservation = Reservation::create([
                'vehicule_id' => $request->vehicule_id,
                'client_id' => $client->id,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'numero_reservation' => $numeroReservation,
                'lieu_depart' => 'Main Office', // Default pickup location
                'lieu_retour' => 'Main Office', // Default return location
                'nombre_passagers' => 1, // Default number of passengers
                'prix_total' => $totalAmount,
                'caution' => 0, // Default deposit
                'statut' => 'en_attente',
                'tenant_id' => 1, // Default tenant
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Reservation creation failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create reservation. Please try again or contact support.'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to create reservation. Please try again or contact support.'])->withInput();
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reservation submitted successfully! We will contact you soon to confirm.'
            ]);
        }
        
        return redirect()->route('landing.car.show', $vehicule)
            ->with('success', 'Reservation submitted successfully! We will contact you soon to confirm.');
    }
    
    /**
     * Get the current tenant based on domain or default to first active tenant
     */
    private function getCurrentTenant()
    {
        $host = request()->getHost();
        
        // Try to find tenant by domain
        $tenant = Tenant::where('domain', $host)->first();
        
        if ($tenant && $tenant->is_active) {
            return $tenant;
        }
        
        // Fallback to first active tenant (for development/testing)
        return Tenant::where('is_active', true)->first();
    }
}
