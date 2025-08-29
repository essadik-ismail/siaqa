<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculeRequest;
use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Vehicule::with(['marque', 'agence'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('immatriculation', 'like', "%{$search}%")
                  ->orWhere('couleur', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('marque', function ($marqueQuery) use ($search) {
                      $marqueQuery->where('marque', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        // Filter by brand
        if ($request->filled('marque_id')) {
            $query->where('marque_id', $request->get('marque_id'));
        }

        // Filter by agency
        if ($request->filled('agence_id')) {
            $query->where('agence_id', $request->get('agence_id'));
        }

        // Filter by fuel type
        if ($request->filled('type_carburant')) {
            $query->where('type_carburant', $request->get('type_carburant'));
        }

        // Filter by category
        if ($request->filled('categorie_vehicule')) {
            $query->where('categorie_vehicule', $request->get('categorie_vehicule'));
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active') === '1');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort fields
        $allowedSortFields = ['name', 'immatriculation', 'statut', 'prix_location_jour', 'kilometrage_actuel', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $vehicules = $query->paginate($request->get('per_page', 15));

        // Get marques and agences for filters
        $marques = Marque::where('is_active', true)->orderBy('marque')->get();
        $agences = Agence::where('is_active', true)->orderBy('nom_agence')->get();

        return view('vehicules.index', compact('vehicules', 'marques', 'agences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $marques = Marque::orderBy('marque', 'asc')->get();
        $agences = Agence::orderBy('nom_agence', 'asc')->get();
        
        return view('vehicules.create', compact('marques', 'agences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;

        // Handle single image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicules/images', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('vehicules/images', 'public');
            }
            $data['images'] = $images;
        }

        $vehicule = Vehicule::create($data);

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicule $vehicule): View
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $vehicule->load([
            'marque', 
            'agence', 
            'reservations', 
            'contrats', 
            'assurances',
            'vidanges',
            'visites',
            'interventions'
        ]);

        return view('vehicules.show', compact('vehicule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicule $vehicule): View
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $marques = Marque::orderBy('marque', 'asc')->get();
        $agences = Agence::orderBy('nom_agence', 'asc')->get();

        return view('vehicules.edit', compact('vehicule', 'marques', 'agences'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculeRequest $request, Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $data = $request->validated();
        $vehicule->update($data);

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        // Check if vehicule has active reservations or contracts
        if ($vehicule->reservations()->whereIn('statut', ['confirmée', 'en_cours'])->exists()) {
            return redirect()->route('vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule car il a des réservations actives');
        }

        if ($vehicule->contrats()->whereIn('statut', ['actif', 'en_cours'])->exists()) {
            return redirect()->route('vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule car il a des contrats actifs');
        }

        $vehicule->delete();

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule supprimé avec succès');
    }

    /**
     * Display available vehicles.
     */
    public function available(): View
    {
        $vehicules = Vehicule::where('statut', 'disponible')
            ->with(['marque', 'agence'])
            ->orderBy('immatriculation', 'asc')
            ->paginate(15);

        return view('vehicules.available', compact('vehicules'));
    }

    /**
     * Update vehicle status.
     */
    public function updateStatus(Request $request, Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $request->validate([
            'statut' => 'required|in:disponible,en_maintenance,hors_service,reservé'
        ]);

        $vehicule->update(['statut' => $request->statut]);

        return redirect()->route('vehicules.index')
            ->with('success', 'Statut du véhicule mis à jour avec succès');
    }

    /**
     * Display vehicle statistics.
     */
    public function statistics(): View
    {
        $totalVehicules = Vehicule::count();
        $disponibles = Vehicule::where('statut', 'disponible')->count();
        $enMaintenance = Vehicule::where('statut', 'en_maintenance')->count();
        $horsService = Vehicule::where('statut', 'hors_service')->count();
        $reserves = Vehicule::where('statut', 'reservé')->count();

        $vehiculesByMarque = Vehicule::selectRaw('marques.marque, count(*) as count')
            ->join('marques', 'vehicules.marque_id', '=', 'marques.id')
            ->groupBy('marques.id', 'marques.marque')
            ->orderBy('count', 'desc')
            ->get();

        $vehiculesByAgence = Vehicule::selectRaw('agences.nom_agence, count(*) as count')
            ->join('agences', 'vehicules.agence_id', '=', 'agences.id')
            ->groupBy('agences.id', 'agences.nom_agence')
            ->orderBy('count', 'desc')
            ->get();

        return view('vehicules.statistics', compact(
            'totalVehicules',
            'disponibles',
            'enMaintenance',
            'horsService',
            'reserves',
            'vehiculesByMarque',
            'vehiculesByAgence'
        ));
    }
} 