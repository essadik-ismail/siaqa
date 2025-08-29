<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Vehicule;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Charge::query();

        // Search functionality
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && !empty($request->get('type'))) {
            $query->where('designation', $request->get('type'));
        }

        // Filter by date range
        if ($request->has('date_debut') && !empty($request->get('date_debut'))) {
            $query->where('date', '>=', $request->get('date_debut'));
        }
        if ($request->has('date_fin') && !empty($request->get('date_fin'))) {
            $query->where('date', '<=', $request->get('date_fin'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $charges = $query->paginate($request->get('per_page', 15));

        // Get data for filters
        $vehicules = Vehicule::orderBy('name')->get();
        $types = Charge::distinct()->pluck('designation')->filter()->sort()->values();

        return view('charges.index', compact('charges', 'vehicules', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicules = Vehicule::orderBy('name')->get();
        $types = [
            'Carburant' => 'Carburant',
            'Maintenance' => 'Maintenance',
            'Assurance' => 'Assurance',
            'Réparation' => 'Réparation',
            'Autre' => 'Autre'
        ];

        return view('charges.create', compact('vehicules', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'fichier' => 'nullable|string|max:255',
        ]);

        Charge::create($validated);

        return redirect()->route('charges.index')
            ->with('success', 'Charge créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Charge $charge): View
    {
        $charge->load(['vehicule', 'agence']);
        return view('charges.show', compact('charge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Charge $charge): View
    {
        $vehicules = Vehicule::orderBy('name')->get();
        $types = [
            'Carburant' => 'Carburant',
            'Maintenance' => 'Maintenance',
            'Assurance' => 'Assurance',
            'Réparation' => 'Réparation',
            'Autre' => 'Autre'
        ];

        return view('charges.edit', compact('charge', 'vehicules', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Charge $charge): RedirectResponse
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'fichier' => 'nullable|string|max:255',
        ]);

        $charge->update($validated);

        return redirect()->route('charges.index')
            ->with('success', 'Charge mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Charge $charge): RedirectResponse
    {
        $charge->delete();

        return redirect()->route('charges.index')
            ->with('success', 'Charge supprimée avec succès.');
    }

    /**
     * Export charges to CSV
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = 'charges_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper French character display
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['Désignation', 'Date', 'Montant (€)', 'Description', 'Fichier']);
            
            // Data
            Charge::orderBy('date', 'desc')->chunk(1000, function($charges) use ($file) {
                foreach ($charges as $charge) {
                    fputcsv($file, [
                        $charge->designation,
                        $charge->date ? $charge->date->format('d/m/Y') : '',
                        number_format($charge->montant, 2),
                        $charge->description,
                        $charge->fichier
                    ]);
                }
            });
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display charge statistics.
     */
    public function statistics(): View
    {
        $stats = [
            'total' => Charge::sum('montant'),
            'this_month' => Charge::whereMonth('date_charge', now()->month)->sum('montant'),
            'this_year' => Charge::whereYear('date_charge', now()->year)->sum('montant'),
            'pending' => Charge::where('statut', 'en_attente')->sum('montant'),
            'paid' => Charge::where('statut', 'payée')->sum('montant'),
            'cancelled' => Charge::where('statut', 'annulée')->sum('montant'),
        ];

        // Monthly breakdown for current year
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = Charge::whereYear('date_charge', now()->year)
                ->whereMonth('date_charge', $i)
                ->sum('montant');
        }

        // Top charge types
        $topTypes = Charge::selectRaw('type_charge, SUM(montant) as total')
            ->groupBy('type_charge')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('charges.statistics', compact('stats', 'monthlyData', 'topTypes'));
    }
}
