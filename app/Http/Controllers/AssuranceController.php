<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuranceRequest;
use App\Models\Assurance;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Assurance::with(['vehicule.marque', 'vehicule.agence']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_police', 'like', "%{$search}%")
                  ->orWhere('compagnie', 'like', "%{$search}%")
                  ->orWhereHas('vehicule', function ($vehiculeQ) use ($search) {
                      $vehiculeQ->where('immatriculation', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'date_expiration');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $assurances = $query->paginate($request->get('per_page', 15));

        return view('assurances.index', compact('assurances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('assurances.create', compact('vehicules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssuranceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;

        $assurance = Assurance::create($data);

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assurance $assurance): View
    {
        // Ensure the assurance belongs to the current tenant
        if ($assurance->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Assurance non trouvée');
        }

        $assurance->load(['vehicule.marque', 'vehicule.agence']);

        return view('assurances.show', compact('assurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assurance $assurance): View
    {
        // Ensure the assurance belongs to the current tenant
        if ($assurance->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Assurance non trouvée');
        }

        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('assurances.edit', compact('assurance', 'vehicules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssuranceRequest $request, Assurance $assurance): RedirectResponse
    {
        // Ensure the assurance belongs to the current tenant
        if ($assurance->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Assurance non trouvée');
        }

        $data = $request->validated();
        $assurance->update($data);

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assurance $assurance): RedirectResponse
    {
        // Ensure the assurance belongs to the current tenant
        if ($assurance->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Assurance non trouvée');
        }

        $assurance->delete();

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance supprimée avec succès');
    }

    /**
     * Display expiring insurances.
     */
    public function expiring(): View
    {
        $assurances = Assurance::where('date_expiration', '<=', now()->addDays(30))
            ->where('date_expiration', '>=', now())
            ->with(['vehicule.marque', 'vehicule.agence'])
            ->orderBy('date_expiration')
            ->get();

        return view('assurances.expiring', compact('assurances'));
    }

    /**
     * Renew an insurance.
     */
    public function renew(Request $request, Assurance $assurance): RedirectResponse
    {
        // Ensure the assurance belongs to the current tenant
        if ($assurance->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Assurance non trouvée');
        }

        $request->validate([
            'date_expiration' => 'required|date|after:today',
            'montant' => 'required|numeric|min:0'
        ]);

        $assurance->update([
            'date_expiration' => $request->get('date_expiration'),
            'montant' => $request->get('montant'),
            'date_renouvellement' => now()
        ]);

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance renouvelée avec succès');
    }
} 