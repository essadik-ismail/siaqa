<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContratRequest;
use App\Models\Contrat;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Contrat::with(['clientOne', 'clientTwo', 'vehicule.marque', 'vehicule.agence'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('number_contrat', 'like', "%{$search}%")
                  ->orWhere('numero_document', 'like', "%{$search}%")
                  ->orWhereHas('clientOne', function ($clientQ) use ($search) {
                      $clientQ->where('nom', 'like', "%{$search}%")
                              ->orWhere('prenom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicule', function ($vehiculeQ) use ($search) {
                      $vehiculeQ->where('immatriculation', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by contract status
        if ($request->filled('etat_contrat')) {
            $query->where('etat_contrat', $request->get('etat_contrat'));
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('client_one_id', $request->get('client_id'))
                  ->orWhere('client_two_id', $request->get('client_id'));
            });
        }

        // Filter by vehicle
        if ($request->filled('vehicule_id')) {
            $query->where('vehicule_id', $request->get('vehicule_id'));
        }

        // Filter by agency
        if ($request->filled('agence_id')) {
            $query->whereHas('vehicule', function ($q) use ($request) {
                $q->where('agence_id', $request->get('agence_id'));
            });
        }

        // Filter by date range
        if ($request->filled('date_debut')) {
            $query->where('date_contrat', '>=', $request->get('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->where('date_contrat', '<=', $request->get('date_fin'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['date_contrat', 'number_contrat', 'etat_contrat', 'prix', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $contrats = $query->paginate($request->get('per_page', 15));

        // Get data for filters
        $clients = Client::where('tenant_id', auth()->user()->tenant_id)->orderBy('nom')->get();
        $vehicules = Vehicule::where('tenant_id', auth()->user()->tenant_id)->orderBy('name')->get();
        $agences = Agence::where('tenant_id', auth()->user()->tenant_id)->orderBy('nom_agence')->get();

        return view('contrats.index', compact('contrats', 'clients', 'vehicules', 'agences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = Client::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_blacklisted', false)
            ->orderBy('nom')
            ->get();
        
        $vehicules = Vehicule::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->with(['marque', 'agence'])
            ->orderBy('name')
            ->get();
        
        $agences = Agence::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->orderBy('nom_agence')
            ->get();

        return view('contrats.create', compact('clients', 'vehicules', 'agences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContratRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;
        
        // Generate unique contract number
        $data['number_contrat'] = 'CON-' . date('Y') . '-' . str_pad(Contrat::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);
        
        // Generate document number
        $data['numero_document'] = 'DOC-' . date('Y') . '-' . str_pad(Contrat::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

        // Handle checkbox fields - set to false if not provided
        $checkboxFields = ['documents', 'cric', 'siege_enfant', 'roue_secours', 'poste_radio', 'plaque_panne', 'gillet', 'extincteur'];
        foreach ($checkboxFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }

        $contrat = Contrat::create($data);

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contrat $contrat): View
    {
        // Ensure the contrat belongs to the current tenant
        if ($contrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Contrat non trouvé');
        }

        $contrat->load(['clientOne', 'clientTwo', 'vehicule.marque', 'vehicule.agence']);

        return view('contrats.show', compact('contrat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contrat $contrat): View
    {
        // Ensure the contrat belongs to the current tenant
        if ($contrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Contrat non trouvé');
        }

        $clients = Client::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_blacklisted', false)
            ->orderBy('nom')
            ->get();
        
        $vehicules = Vehicule::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->with(['marque', 'agence'])
            ->orderBy('name')
            ->get();
        
        $agences = Agence::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->orderBy('nom_agence')
            ->get();

        return view('contrats.edit', compact('contrat', 'clients', 'vehicules', 'agences'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContratRequest $request, Contrat $contrat): RedirectResponse
    {
        // Ensure the contrat belongs to the current tenant
        if ($contrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Contrat non trouvé');
        }

        $data = $request->validated();
        $contrat->update($data);

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contrat $contrat): RedirectResponse
    {
        // Ensure the contrat belongs to the current tenant
        if ($contrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Contrat non trouvé');
        }

        $contrat->delete();

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat supprimé avec succès');
    }

    /**
     * Print the contract.
     */
    public function print(Contrat $contrat)
    {
        // Ensure the contrat belongs to the current tenant
        if ($contrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Contrat non trouvé');
        }

        $contrat->load(['clientOne', 'clientTwo', 'vehicule.marque', 'vehicule.agence']);

        // For now, return the print view (PDF generation will be fixed later)
        return view('contrats.print', compact('contrat'));
    }
} 