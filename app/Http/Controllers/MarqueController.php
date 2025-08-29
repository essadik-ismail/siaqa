<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarqueRequest;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Marque::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('marque', 'like', "%{$search}%");
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'marque');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $marques = $query->paginate($request->get('per_page', 15));

        return view('marques.index', compact('marques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('marques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarqueRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;

        $marque = Marque::create($data);

        return redirect()->route('marques.index')
            ->with('success', 'Marque créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marque $marque): View
    {
        // Ensure the marque belongs to the current tenant
        if ($marque->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Marque non trouvée');
        }

        $marque->load(['vehicules']);

        return view('marques.show', compact('marque'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marque $marque): View
    {
        // Ensure the marque belongs to the current tenant
        if ($marque->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Marque non trouvée');
        }

        return view('marques.edit', compact('marque'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarqueRequest $request, Marque $marque): RedirectResponse
    {
        // Ensure the marque belongs to the current tenant
        if ($marque->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Marque non trouvée');
        }

        $data = $request->validated();
        $marque->update($data);

        return redirect()->route('marques.index')
            ->with('success', 'Marque mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marque $marque): RedirectResponse
    {
        // Ensure the marque belongs to the current tenant
        if ($marque->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Marque non trouvée');
        }

        // Check if marque has vehicles
        if ($marque->vehicules()->exists()) {
            return redirect()->route('marques.index')
                ->with('error', 'Impossible de supprimer cette marque car elle a des véhicules associés');
        }

        $marque->delete();

        return redirect()->route('marques.index')
            ->with('success', 'Marque supprimée avec succès');
    }

    /**
     * Display active marques.
     */
    public function active(): View
    {
        $marques = Marque::orderBy('marque', 'asc')
            ->paginate(15);

        return view('marques.active', compact('marques'));
    }

    /**
     * Toggle marque status.
     */
    public function toggleStatus(Marque $marque): RedirectResponse
    {
        // Ensure the marque belongs to the current tenant
        if ($marque->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Marque non trouvée');
        }

        // Since there's no status field, we'll just return a message
        return redirect()->route('marques.index')
            ->with('info', 'Fonctionnalité de statut non disponible pour les marques');
    }
} 