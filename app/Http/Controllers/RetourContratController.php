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
        $retourContrats = RetourContrat::with(['contrat.vehicule', 'contrat.clientOne'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
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
            // Ensure the contract belongs to the current tenant
            $contrat = Contrat::find($validated['contrat_id']);
            if (!$contrat || $contrat->tenant_id !== auth()->user()->tenant_id) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Contrat non trouvé ou non autorisé'
                    ], 404);
                }
                return redirect()->back()
                    ->with('error', 'Contrat non trouvé ou non autorisé');
            }

            $retourContrat = RetourContrat::create([
                ...$validated,
                'tenant_id' => auth()->user()->tenant_id
            ]);
            
            // Update contract status to completed
            $contrat->update(['etat_contrat' => 'termine']);
            
            // Update vehicle status to available
            $contrat->vehicule->update(['statut' => 'disponible']);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retour de contrat enregistré avec succès',
                    'data' => $retourContrat
                ]);
            }

            return redirect()->route('retour-contrats.index')
                ->with('success', 'Retour de contrat créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Retour contrat error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->wantsJson()) {
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
        // Ensure the retour contrat belongs to the current tenant
        if ($retourContrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Retour de contrat non trouvé');
        }

        $retourContrat->load(['contrat.vehicule', 'contrat.clientOne', 'contrat.clientTwo']);
        return view('retour-contrats.show', compact('retourContrat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RetourContrat $retourContrat): View
    {
        // Ensure the retour contrat belongs to the current tenant
        if ($retourContrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Retour de contrat non trouvé');
        }

        $contrats = Contrat::where('tenant_id', auth()->user()->tenant_id)
            ->with(['vehicule', 'clientOne'])
            ->get();
        return view('retour-contrats.edit', compact('retourContrat', 'contrats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RetourContrat $retourContrat): RedirectResponse
    {
        // Ensure the retour contrat belongs to the current tenant
        if ($retourContrat->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Retour de contrat non trouvé');
        }

        $validated = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'date_retour' => 'required|date',
            'kilometrage_retour' => 'required|numeric|min:0',
            'niveau_carburant' => 'required|in:vide,1/4,1/2,3/4,plein',
            'etat_vehicule' => 'required|in:excellent,bon,moyen,mauvais',
            'observations' => 'nullable|string',
            'frais_supplementaires' => 'nullable|numeric|min:0',
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
