<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Vehicule;

class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $visites = Visite::paginate(10);
        return view('visites.index', compact('visites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('visites.create', compact('vehicules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_visite' => 'required|date',
            'type_visite' => 'required|string|max:255',
            'statut' => 'required|in:planifiée,en_cours,terminée,annulée',
            'notes' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0',
        ]);

        Visite::create($validated);

        return redirect()->route('visites.index')
            ->with('success', 'Visite créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visite $visite): View
    {
        return view('visites.show', compact('visite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visite $visite): View
    {
        return view('visites.edit', compact('visite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visite $visite): RedirectResponse
    {
        $validated = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_visite' => 'required|date',
            'type_visite' => 'required|string|max:255',
            'statut' => 'required|in:planifiée,en_cours,terminée,annulée',
            'notes' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0',
        ]);

        $visite->update($validated);

        return redirect()->route('visites.index')
            ->with('success', 'Visite mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visite $visite): RedirectResponse
    {
        $visite->delete();

        return redirect()->route('visites.index')
            ->with('success', 'Visite supprimée avec succès.');
    }

    /**
     * Display visits that are due.
     */
    public function due(): View
    {
        $visites = Visite::where('date_visite', '<=', now()->addDays(7))
            ->where('statut', '!=', 'terminée')
            ->paginate(10);

        return view('visites.due', compact('visites'));
    }

    /**
     * Mark a visit as complete.
     */
    public function complete(Visite $visite): RedirectResponse
    {
        $visite->update(['statut' => 'terminée']);

        return redirect()->back()
            ->with('success', 'Visite marquée comme terminée.');
    }
}
