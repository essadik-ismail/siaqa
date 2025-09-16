<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Vehicule;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Reservation::with(['client', 'vehicule.marque', 'vehicule.agence'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_reservation', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQ) use ($search) {
                      $clientQ->where('nom', 'like', "%{$search}%")
                              ->orWhere('prenom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicule', function ($vehiculeQ) use ($search) {
                      $vehiculeQ->where('immatriculation', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        // Filter by date range
        if ($request->filled('date_debut')) {
            $query->where('date_debut', '>=', $request->get('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->where('date_fin', '<=', $request->get('date_fin'));
        }

        // Filter by agency
        if ($request->filled('agence_id')) {
            $query->where('agence_id', $request->get('agence_id'));
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $clientFilter = $request->get('client_id');
            if ($clientFilter === 'blacklisted') {
                $query->whereHas('client', function ($q) {
                    $q->where(function ($subQ) {
                        $subQ->where('is_blacklisted', true)
                             ->orWhere('is_blacklist', true);
                    });
                });
            } else {
                $query->where('client_id', $clientFilter);
            }
        }

        // Filter by vehicle
        if ($request->filled('vehicule_id')) {
            $query->where('vehicule_id', $request->get('vehicule_id'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['date_debut', 'date_fin', 'statut', 'prix_total', 'numero_reservation', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $reservations = $query->paginate($request->get('per_page', 15));

        // Get data for filters
        $clients = Client::where('is_blacklisted', false)->orderBy('nom')->get();
        $vehicules = Vehicule::where('is_active', true)->orderBy('name')->get();
        $agences = \App\Models\Agence::where('is_active', true)->orderBy('nom_agence')->get();

        // Calculate statistics
        $allReservations = Reservation::where('tenant_id', auth()->user()->tenant_id)->get();
        $statistics = [
            'total' => $allReservations->count(),
            'confirmees' => $allReservations->where('statut', 'confirmee')->count(),
            'en_attente' => $allReservations->where('statut', 'en_attente')->count(),
            'annulees' => $allReservations->where('statut', 'annulee')->count(),
            'terminees' => $allReservations->where('statut', 'terminee')->count(),
        ];

        return view('reservations.index', compact('reservations', 'clients', 'vehicules', 'agences', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = Client::where('is_blacklisted', false)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('nom')->get();
        $vehicules = Vehicule::where('statut', '!=', 'hors_service')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->with(['marque', 'agence'])
            ->get();
        $agences = \App\Models\Agence::where('is_active', true)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('nom_agence')->get();

        return view('reservations.create', compact('clients', 'vehicules', 'agences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['tenant_id'] = auth()->user()->tenant_id;
        
        // Generate unique reservation number
        $data['numero_reservation'] = 'RES-' . date('Y') . '-' . str_pad(Reservation::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

        // Check if vehicle is available for the selected dates
        $vehicule = Vehicule::find($data['vehicule_id']);
        if (!$vehicule || $vehicule->statut !== 'disponible') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le véhicule sélectionné n\'est pas disponible');
        }

        // Check for date conflicts
        $conflictingReservation = Reservation::where('vehicule_id', $data['vehicule_id'])
            ->where('statut', '!=', 'annulee')
            ->where(function ($q) use ($data) {
                $q->whereBetween('date_debut', [$data['date_debut'], $data['date_fin']])
                  ->orWhereBetween('date_fin', [$data['date_debut'], $data['date_fin']])
                  ->orWhere(function ($subQ) use ($data) {
                      $subQ->where('date_debut', '<=', $data['date_debut'])
                           ->where('date_fin', '>=', $data['date_fin']);
                  });
            })
            ->first();

        if ($conflictingReservation) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le véhicule n\'est pas disponible pour les dates sélectionnées');
        }

        // Create the reservation
        $reservation = Reservation::create($data);

        // Update vehicle status to 'en_location' if reservation is confirmed
        if ($data['statut'] === 'confirmee') {
            $vehicule->update(['statut' => 'en_location']);
        }

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation créée avec succès');
        } catch (\Exception $e) {
            \Log::error('Reservation creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la réservation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): View
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        $reservation->load(['client', 'vehicule.marque', 'vehicule.agence', 'contrat']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation): View
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        $clients = Client::where('is_blacklisted', false)->orderBy('nom')->get();
        $vehicules = Vehicule::where('is_active', true)
            ->with(['marque', 'agence'])
            ->get();

        return view('reservations.edit', compact('reservation', 'clients', 'vehicules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        $data = $request->validated();

        // Check for date conflicts if vehicle or dates changed
        if ($data['vehicule_id'] !== $reservation->vehicule_id || 
            $data['date_debut'] !== $reservation->date_debut || 
            $data['date_fin'] !== $reservation->date_fin) {
            
            $conflictingReservation = Reservation::where('vehicule_id', $data['vehicule_id'])
                ->where('id', '!=', $reservation->id)
                ->where('statut', '!=', 'annulee')
                ->where(function ($q) use ($data) {
                    $q->whereBetween('date_debut', [$data['date_debut'], $data['date_fin']])
                      ->orWhereBetween('date_fin', [$data['date_debut'], $data['date_fin']])
                      ->orWhere(function ($subQ) use ($data) {
                          $subQ->where('date_debut', '<=', $data['date_debut'])
                               ->where('date_fin', '>=', $data['date_fin']);
                      });
                })
                ->first();

            if ($conflictingReservation) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Le véhicule n\'est pas disponible pour les dates sélectionnées');
            }
        }

        $reservation->update($data);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        // Check if reservation has associated contract
        if ($reservation->contrat()->exists()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Impossible de supprimer cette réservation car elle a un contrat associé');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation supprimée avec succès');
    }

    /**
     * Confirm a reservation.
     */
    public function confirm(Reservation $reservation): RedirectResponse
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        if ($reservation->statut !== 'en_attente') {
            return redirect()->route('reservations.index')
                ->with('error', 'Seules les réservations en attente peuvent être confirmées');
        }

        $reservation->update(['statut' => 'confirmee']);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation confirmée avec succès');
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        $request->validate([
            'motif_annulation' => 'required|string|max:500'
        ]);

        $reservation->update([
            'statut' => 'annulee',
            'motif_annulation' => $request->get('motif_annulation')
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès');
    }

    /**
     * Update reservation status.
     */
    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        // Ensure the reservation belongs to the current tenant
        if ($reservation->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Réservation non trouvée');
        }

        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_cours,terminee,annulee'
        ]);

        $reservation->update(['statut' => $request->get('statut')]);

        return redirect()->route('reservations.index')
            ->with('success', 'Statut de la réservation mis à jour avec succès');
    }

    /**
     * Display reservation statistics.
     */
    public function statistics(): View
    {
        $tenantId = auth()->user()->tenant_id;
        
        $stats = [
            'total_reservations' => Reservation::where('tenant_id', $tenantId)->count(),
            'en_attente' => Reservation::where('tenant_id', $tenantId)->where('statut', 'en_attente')->count(),
            'confirmees' => Reservation::where('tenant_id', $tenantId)->where('statut', 'confirmee')->count(),
            'en_cours' => Reservation::where('tenant_id', $tenantId)->where('statut', 'en_cours')->count(),
            'terminees' => Reservation::where('tenant_id', $tenantId)->where('statut', 'terminee')->count(),
            'annulees' => Reservation::where('tenant_id', $tenantId)->where('statut', 'annulee')->count(),
            'ce_mois' => Reservation::where('tenant_id', $tenantId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'ce_mois_revenus' => Reservation::where('tenant_id', $tenantId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('statut', '!=', 'annulee')
                ->sum('prix_total'),
        ];

        return view('reservations.statistics', compact('stats'));
    }
} 