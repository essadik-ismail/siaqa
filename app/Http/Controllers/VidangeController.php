<?php

namespace App\Http\Controllers;

use App\Models\Vidange;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VidangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Vidange::with(['vehicule.marque', 'vehicule.agence']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_vidange', 'like', "%{$search}%")
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
        $sortBy = $request->get('sort_by', 'date_prevue');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $vidanges = $query->paginate($request->get('per_page', 15));

        return view('vidanges.index', compact('vidanges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('vidanges.create', compact('vehicules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_prevue' => 'required|date|after:today',
            'kilometrage_actuel' => 'required|numeric|min:0',
            'kilometrage_prochaine' => 'required|numeric|gt:kilometrage_actuel',
            'notes' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['statut'] = 'planifiee';

        $vidange = Vidange::create($data);

        return redirect()->route('vidanges.index')
            ->with('success', 'Vidange planifiée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vidange $vidange): View
    {
        // Ensure the vidange belongs to the current tenant
        if ($vidange->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Vidange non trouvée');
        }

        $vidange->load(['vehicule.marque', 'vehicule.agence']);

        return view('vidanges.show', compact('vidange'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vidange $vidange): View
    {
        // Ensure the vidange belongs to the current tenant
        if ($vidange->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Vidange non trouvée');
        }

        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('vidanges.edit', compact('vidange', 'vehicules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vidange $vidange): RedirectResponse
    {
        // Ensure the vidange belongs to the current tenant
        if ($vidange->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Vidange non trouvée');
        }

        $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_prevue' => 'required|date',
            'kilometrage_actuel' => 'required|numeric|min:0',
            'kilometrage_prochaine' => 'required|numeric|gt:kilometrage_actuel',
            'notes' => 'nullable|string|max:500'
        ]);

        $vidange->update($request->all());

        return redirect()->route('vidanges.index')
            ->with('success', 'Vidange mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vidange $vidange): RedirectResponse
    {
        // Ensure the vidange belongs to the current tenant
        if ($vidange->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Vidange non trouvée');
        }

        if ($vidange->statut === 'terminee') {
            return redirect()->route('vidanges.index')
                ->with('error', 'Impossible de supprimer une vidange terminée');
        }

        $vidange->delete();

        return redirect()->route('vidanges.index')
            ->with('success', 'Vidange supprimée avec succès');
    }

    /**
     * Display due oil changes.
     */
    public function due(): View
    {
        $vidanges = Vidange::where('date_prevue', '<=', now()->addDays(7))
            ->where('statut', 'planifiee')
            ->with(['vehicule.marque', 'vehicule.agence'])
            ->orderBy('date_prevue')
            ->get();

        return view('vidanges.due', compact('vidanges'));
    }

    /**
     * Complete an oil change.
     */
    public function complete(Request $request, Vidange $vidange): RedirectResponse
    {
        // Ensure the vidange belongs to the current tenant
        if ($vidange->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Vidange non trouvée');
        }

        $request->validate([
            'date_realisation' => 'required|date|before_or_equal:today',
            'kilometrage_realise' => 'required|numeric|min:0',
            'cout' => 'required|numeric|min:0',
            'notes_realisation' => 'nullable|string|max:500'
        ]);

        $vidange->update([
            'statut' => 'terminee',
            'date_realisation' => $request->get('date_realisation'),
            'kilometrage_realise' => $request->get('kilometrage_realise'),
            'cout' => $request->get('cout'),
            'notes_realisation' => $request->get('notes_realisation')
        ]);

        return redirect()->route('vidanges.index')
            ->with('success', 'Vidange terminée avec succès');
    }
} 