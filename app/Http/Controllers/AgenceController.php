<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgenceRequest;
use App\Models\Agence;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Agence::where('tenant_id', auth()->user()->tenant_id);

        // Search functionality
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom_agence', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('adresse', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->has('ville') && !empty($request->get('ville'))) {
            $query->where('ville', 'like', "%{$request->get('ville')}%");
        }

        // Filter by RC
        if ($request->has('rc') && !empty($request->get('rc'))) {
            $query->where('rc', 'like', "%{$request->get('rc')}%");
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $agences = $query->paginate($request->get('per_page', 15));

        // Get unique cities for filter dropdown
        $cities = Agence::distinct()->pluck('ville')->filter()->sort()->values();
        
        // Get unique RC values for filter dropdown
        $rcValues = Agence::distinct()->pluck('rc')->filter()->sort()->values();

        return view('agences.index', compact('agences', 'cities', 'rcValues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('agences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgenceRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            
            // Get or create default tenant
            $tenant = $this->getOrCreateDefaultTenant();
            $data['tenant_id'] = $tenant->id;

            $agence = Agence::create($data);

            return redirect()->route('agences.index')
                ->with('success', 'Agence créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'agence: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Agence $agence): View
    {
        $agence->load(['vehicules']);
        
        return view('agences.show', compact('agence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agence $agence): View
    {
        return view('agences.edit', compact('agence'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgenceRequest $request, Agence $agence): RedirectResponse
    {
        try {
            $data = $request->validated();
            $agence->update($data);

            return redirect()->route('agences.index')
                ->with('success', 'Agence mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'agence: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agence $agence): RedirectResponse
    {
        try {
            // Check if agence has vehicles
            if ($agence->vehicules()->exists()) {
                return redirect()->route('agences.index')
                    ->with('error', 'Impossible de supprimer cette agence car elle a des véhicules associés');
            }

            $agence->delete();

            return redirect()->route('agences.index')
                ->with('success', 'Agence supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'agence: ' . $e->getMessage());
        }
    }

    /**
     * Display active agencies.
     */
    public function active(): View
    {
        $agences = Agence::where('is_active', true)
            ->orderBy('nom_agence', 'asc')
            ->paginate(15);

        return view('agences.active', compact('agences'));
    }

    /**
     * Toggle agency status.
     */
    public function toggleStatus(Agence $agence): RedirectResponse
    {
        try {
            $agence->update(['is_active' => !$agence->is_active]);

            $status = $agence->is_active ? 'activée' : 'désactivée';
            return redirect()->route('agences.index')
                ->with('success', "Agence {$status} avec succès");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }

    /**
     * Get or create default tenant.
     */
    private function getOrCreateDefaultTenant(): Tenant
    {
        // Try to get existing default tenant
        $tenant = Tenant::where('name', 'Default')->first();
        
        if (!$tenant) {
            // Create default tenant if none exists
            $tenant = Tenant::create([
                'name' => 'Default',
                'domain' => 'default.local',
                'database' => 'default',
                'subscription_plan' => 'starter',
                'is_active' => true,
            ]);
        }
        
        return $tenant;
    }
} 