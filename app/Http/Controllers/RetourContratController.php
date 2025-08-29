<?php

namespace App\Http\Controllers;

use App\Models\RetourContrat;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class RetourContratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $retourContrats = RetourContrat::with(['contrat.vehicule', 'contrat.clientOne'])->paginate(15);
        return view('retour-contrats.index', compact('retourContrats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contrats = Contrat::where('etat_contrat', '!=', 'termine')->with(['vehicule', 'clientOne'])->get();
        return view('retour-contrats.create', compact('contrats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'date_retour' => 'required|date',
            'kilometrage_retour' => 'required|numeric|min:0',
            'niveau_carburant' => 'required|in:vide,1/4,1/2,3/4,plein',
            'etat_vehicule' => 'required|in:excellent,bon,moyen,mauvais',
            'observations' => 'nullable|string',
            'frais_supplementaires' => 'nullable|numeric|min:0',
        ]);

        try {
            $retourContrat = RetourContrat::create($validated);
            
            // Update contract status to completed
            $contrat = Contrat::find($validated['contrat_id']);
            $contrat->update(['etat_contrat' => 'termine']);
            
            // Update vehicle status to available
            $contrat->vehicule->update(['statut' => 'disponible']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retour de contrat enregistré avec succès',
                    'data' => $retourContrat
                ]);
            }

            return redirect()->route('retour-contrats.index')
                ->with('success', 'Retour de contrat créé avec succès.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RetourContrat $retourContrat): View
    {
        $retourContrat->load(['contrat.vehicule', 'contrat.clientOne', 'contrat.clientTwo']);
        return view('retour-contrats.show', compact('retourContrat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RetourContrat $retourContrat): View
    {
        $contrats = Contrat::with(['vehicule', 'clientOne'])->get();
        return view('retour-contrats.edit', compact('retourContrat', 'contrats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RetourContrat $retourContrat): RedirectResponse
    {
        $validated = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'date_retour' => 'required|date',
            'kilometrage_retour' => 'required|numeric|min:0',
            'niveau_carburant' => 'required|in:vide,1/4,1/2,3/4,plein',
            'etat_vehicule' => 'required|in:excellent,bon,moyen,mauvais',
            'observations' => 'nullable|string',
            'frais_supplementaires' => 'nullable|numeric|min=0',
        ]);

        $retourContrat->update($validated);

        return redirect()->route('retour-contrats.index')
            ->with('success', 'Retour de contrat mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RetourContrat $retourContrat): RedirectResponse
    {
        $retourContrat->delete();

        return redirect()->route('retour-contrats.index')
            ->with('success', 'Retour de contrat supprimé avec succès.');
    }

    /**
     * Process a contract return.
     */
    public function process(RetourContrat $retourContrat): RedirectResponse
    {
        $retourContrat->update(['statut' => 'traité']);

        return redirect()->back()
            ->with('success', 'Retour de contrat traité avec succès.');
    }

    /**
     * Get contract details for return form.
     */
    public function getContractDetails(Contrat $contrat): JsonResponse
    {
        $contrat->load(['vehicule', 'clientOne', 'clientTwo']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'contrat' => $contrat,
                'vehicule' => $contrat->vehicule,
                'client' => $contrat->clientOne,
                'client_secondary' => $contrat->clientTwo,
            ]
        ]);
    }
}
