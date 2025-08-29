<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Vehicule;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $interventions = Intervention::paginate(10);
        return view('interventions.index', compact('interventions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('interventions.create', compact('vehicules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'type_intervention' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'required|in:planifiée,en_cours,terminée,annulée',
            'cout' => 'nullable|numeric|min:0',
            'technicien' => 'nullable|string|max:255',
        ]);

        Intervention::create($validated);

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Intervention $intervention): View
    {
        return view('interventions.show', compact('intervention'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention): View
    {
        return view('interventions.edit', compact('intervention'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intervention $intervention): RedirectResponse
    {
        $validated = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'type_intervention' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'required|in:planifiée,en_cours,terminée,annulée',
            'cout' => 'nullable|numeric|min:0',
            'technicien' => 'nullable|string|max:255',
        ]);

        $intervention->update($validated);

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention): RedirectResponse
    {
        $intervention->delete();

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention supprimée avec succès.');
    }

    /**
     * Start an intervention.
     */
    public function start(Intervention $intervention): RedirectResponse
    {
        $intervention->update(['statut' => 'en_cours']);

        return redirect()->back()
            ->with('success', 'Intervention démarrée.');
    }

    /**
     * Complete an intervention.
     */
    public function complete(Intervention $intervention): RedirectResponse
    {
        $intervention->update([
            'statut' => 'terminée',
            'date_fin' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Intervention terminée.');
    }

    /**
     * Display intervention statistics.
     */
    public function statistics(): View
    {
        $stats = [
            'total' => Intervention::count(),
            'en_cours' => Intervention::where('statut', 'en_cours')->count(),
            'terminees' => Intervention::where('statut', 'terminée')->count(),
            'planifiees' => Intervention::where('statut', 'planifiée')->count(),
        ];

        return view('interventions.statistics', compact('stats'));
    }
}
