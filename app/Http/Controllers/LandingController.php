<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Reservation;
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
                ->limit(6)
                ->get();
                
            $totalCars = Vehicule::where('tenant_id', $currentTenant->id)->count();
            $availableCars = Vehicule::where('tenant_id', $currentTenant->id)
                ->where('statut', 'disponible')
                ->count();
            $totalAgencies = Agence::where('tenant_id', $currentTenant->id)
                ->where('is_active', true)
                ->count();
        } else {
            // Fallback for when no tenant is found
            $featuredCars = collect();
            $totalCars = 0;
            $availableCars = 0;
            $totalAgencies = 0;
        }
        
        return view('landing.index', compact('featuredCars', 'totalCars', 'availableCars', 'totalAgencies', 'currentTenant'));
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
        
        $cars = $query->where('is_active', 1)->where('statut', 'disponible')->paginate(12);
        $marques = Marque::where('tenant_id', $currentTenant->id)->where('is_active', true)->get();
        
        return view('landing.cars', compact('cars', 'marques', 'currentTenant'));
    }
    
    public function showCar(Vehicule $vehicule)
    {
        $vehicule->load('marque', 'agence');
        $relatedCars = Vehicule::with('marque')
            ->where('id', '!=', $vehicule->id)
            ->where('marque_id', $vehicule->marque_id)
            ->where('statut', 'disponible')
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
            return redirect()->back()->with('success', 'Login successful!');
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
        
        return redirect()->route('landing')->with('success', 'Account created successfully! Welcome to CarRental.');
    }
    
    public function storeReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Check if car is available for the selected dates
        $vehicule = Vehicule::findOrFail($request->vehicule_id);
        
        if ($vehicule->statut !== 'disponible') {
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
            return back()->withErrors(['dates' => 'This car is not available for the selected dates.'])->withInput();
        }
        
        // Calculate total amount
        $startDate = Carbon::parse($request->date_debut);
        $endDate = Carbon::parse($request->date_fin);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalAmount = $vehicule->prix_jour * $days;
        
        $reservation = Reservation::create([
            'vehicule_id' => $request->vehicule_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'montant_total' => $totalAmount,
            'statut' => 'en_attente',
            'tenant_id' => 1, // Default tenant
        ]);
        
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
